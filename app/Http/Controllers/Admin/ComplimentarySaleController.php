<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Complimentarysale;
use App\Models\ComplimentaryOrdersale;
use App\Traits\FlashMessages; 
use Carbon\Carbon;
use DateTime;
use Auth;
use App\Models\Recipe;
use App\Models\Unit;
use App\Models\Ingredient;

class ComplimentarySaleController extends Controller
{
    use FlashMessages;

    public function index(){       
        //Attaching pagetitle and subtitle to view.
        view()->share(['pageTitle' => 'Complimentary Sales', 'subTitle' => 'Add Complimentary Foods to a particular table' ]);
        return view('admin.complimentary.sales.index');
    }

    /*
    * Ajax request
    */
    public function getFoods(Request $request){

        $search = $request->search;

        if($search == ''){
            $products = Product::orderby('name','asc')
                                ->select('id','name')
                                ->where('status', 1)
                                ->limit(10)->get();
        }else{
            $products = Product::orderby('name','asc')
                        ->select('id','name')
                        ->where('name', 'like', '%' .$search . '%')
                        ->where('status', 1)
                        ->limit(10)
                        ->get();
        }

        $response = array();
        foreach($products as $product){            
            $response[] = array( "value" => $product->id, "label" => $product->name );            
        }

        return response()->json($response);   
    }    

    public function addToSales(Request $request){

        $product = Product::find($request->foodId);
        //checking discount price is enabled for this product
        if($product->discount_price){
            $sale_price = $product->discount_price;
        }else{
            $sale_price = $product->price;
        }

        // checking the food is added to the recipe
        if(!Recipe::where('product_id', $request->foodId)->first()){
            return json_encode([ 'status' => 'info', 'message' => "Please add '". $request->foodName ."' food recipe before you sale." ]);           
         }// recipe is added but recipe ingredients is not added for the food
        elseif(!Recipe::where('product_id', $request->foodId)->first()->recipeingredients->count()){          
             return json_encode([ 'status' => 'info', 'message' => "Please add '". $request->foodName ."' food recipe ingredients before you  sale." ]);
         }
	else{
            //when stock ingredient total quantity is zero or negative after sales.
            foreach(Recipe::where('product_id', $request->foodId)->first()->recipeingredients as $ingredient){
               if(Ingredient::where('id', $ingredient->ingredient_id)->first()->total_quantity <= 0){
                    return json_encode([ 'status' => 'info', 'message' => "Please add purchase record for ingredient '". 
                    Ingredient::where('id', $ingredient->ingredient_id)->first()->name ."' of food '". $request->foodName ."' before sale." ]);
                }
            }
            
         }

        //checking the product whether it is already added to the sale cart, if so we sent the message this product is already added to the cart.
        $sale = Complimentarysale::where('admin_id', Auth::id())
                    ->where('product_id', $request->foodId)
                    ->where('complimentary_ordersales_id', NULL) 
                    ->first();
        if(!is_null($sale)){
            return json_encode([ 'status' => 'info', 'message' => "This food is already added to the cart."  ]);
        }
        else{
            // adding new item to the Complimentary sale cart
            $sale = new Complimentarysale();
            $sale->admin_id = Auth::id(); 
            $sale->product_id = $request->foodId;
            $sale->product_name = $request->foodName;
            $sale->unit_price = $sale_price;
            $sale->product_quantity = 1;
            $sale->save();

            return json_encode([ 'status' => 'success', 'foodname' => $sale->product_name , 'price' => $sale->unit_price, 'qty' => $sale->product_quantity, 'id' => $sale->id , 'sub_total' => $this->calculateSubtotal() ]);
            
        }          
        
    }

    public function calculateSubtotal(){
        // calculating subtotal
        $total_taka=0.0;
        foreach(Complimentarysale::where('admin_id', Auth::id())->where('complimentary_ordersales_id',NULL)->get() as $sale){      
          
            if($sale->product->discount_price){
                    $total_taka += $sale->product->discount_price * $sale->product_quantity;
            }else{
                    $total_taka += $sale->product->price * $sale->product_quantity;
            }         
        }
        return $total_taka;
    }

    public function update(Request $request)
    {  
        $id = $request->sale_id;                
        $sale = Complimentarysale::find($id);//primary key id
        $sale->product_quantity = $request->product_quantity;           
        $sale->save();
        
        if($sale->product->discount_price){
                $total_unit_price = $sale->product->discount_price * $sale->product_quantity;
        }else{
                $total_unit_price = $sale->product->price * $sale->product_quantity;
        }
         
        return json_encode([ 'status' => 'success', 'total_unit_price' => $total_unit_price, 'sub_total' => $this->calculateSubtotal() ]);      
    }

    public function destroy(Request $request)
    {
        $sale = Complimentarysale::find($request->sale_id);
        if(!is_null($sale)){            
            $sale->delete();
            return json_encode([ 'status' => 'success', 'message' => 'Item is deleted', 'sub_total' => $this->calculateSubtotal() ]); 
        }
        return json_encode(['status' => 'error', 'message' => 'An error is occurred while deleting the cart' ]);
    }

    public function orderplace(Request $request){

        // if no items are added to complimentary sale cart
        if(!$this->calculateSubtotal()){
        // setting flash message using trait
        $this->setFlashMessage(' Please add items to the cart', 'error');    
        $this->showFlashMessages(); 
        return redirect()->back();
        }

        // finding last order id: we use it for customer order id (customized) for billing purpose
        // it will be false only for the first record.
        if(!ComplimentaryOrdersale::orderBy('id', 'desc')->first()){
            $ord_id = 0;
        }
        else{
            $ord_id = ComplimentaryOrdersale::orderBy('id', 'desc')->first()->id; 
        }   
        $ord_id = '##0'.(($ord_id + 1));

        //placing a new order.
        $order = new ComplimentaryOrdersale(); // we use order_id as online transaction id.
        $order->admin_id = auth()->user()->id;     
        $order->order_number = $ord_id;         
        $order->order_date = \Carbon\Carbon::now()->toDateTimeString(); 
        $order->grand_total =$this->calculateSubtotal();
        $order->notes = $request->complimentary_notes;
        $order->save();
        // when order is placed we set complimentary_ordersales_id and order_tbl_no to complimentary Sale cart 
        foreach(Complimentarysale::where('admin_id', Auth::id())->where('complimentary_ordersales_id',NULL)->get() as $sale){
            $sale->complimentary_ordersales_id = $order->id;           
            $sale->save();
        }

        //Inventory Management: We will deduct product quantity and product total cost using product id from ingredient stock. 

         //finding the cart using order id... it may return many complimentary sale carts for pos system
        
         foreach($order->complimentarysales as $cart){
            //getting product quantity that user has purchased.
            $cart_product_quantity = $cart->product_quantity;
            //using product id finding the recipe and then finding the ingredients of the recipe
            foreach(Recipe::where('product_id', $cart->product_id)->first()->recipeingredients as $recipeingredient){
                //getting the ingredient.
                $ingredient = $recipeingredient->ingredient;                   
                //Subtracting ingredient total cost from ingredient stock consumed in recipe ingredients. 
                $ingredient->total_price -= ($recipeingredient->ingredient_total_cost * $cart_product_quantity);
                // if ingredient stock unit is equal to recipe ingredients... then we just deduct qty from ingredient stock.
                if($ingredient->measurement_unit == $recipeingredient->measure_unit){
                    $ingredient->total_quantity -= ($recipeingredient->quantity * $cart_product_quantity); 
                }else{
                    // getting unit conversion value from Unit 
                    $unit = Unit::where('smallest_measurement_unit', $recipeingredient->measure_unit)->first();            
                    $unit_conversion = $unit->unit_conversion; 
                    $ingredient->total_quantity -= ($recipeingredient->quantity * $cart_product_quantity/$unit_conversion);
                }
                $ingredient->save();

            }

        }
        
        // setting flash message using trait
        $this->setFlashMessage(' Complimentary Order is placed successfully', 'success');    
        $this->showFlashMessages(); 
        
        return redirect()->route('admin.complimentary.sales.index');   

    }



}
