<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Ordersale;
use Auth;
use App\Traits\FlashMessages; 
use App\Models\Recipe;
use App\Models\Unit;
// this controller is no more used instead kotRestaurantController used
class KotRestaurantController extends Controller
{
    use FlashMessages;

    public function index(){
        // Attaching pagetitle and subtitle to view.        
        view()->share(['pageTitle' => 'Kitchen Order Ticketing System', 'subTitle' => 'Food Management: Add Foods to a particular table' ]);
        return view('admin.sales.restaurant.index');
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
        //before adding foods to a table, it is needed to select a table first.
        if(!$request->tblId){
            return json_encode([ 'status' => 'info', 'message' => "Please select a table to add foods" ]);
        }

        // checking the food is added to the recipe
        if(!Recipe::where('product_id', $request->foodId)->first()){
            return json_encode([ 'status' => 'info', 'message' => "Please add '". $request->foodName ."' food recipe before you sale." ]);           
         }// redipe is added but recipe ingredients is not added for the food
         elseif(!Recipe::where('product_id', $request->foodId)->first()->recipeingredients->count()){          
             return json_encode([ 'status' => 'info', 'message' => "Please add '". $request->foodName ."' food recipe ingredients before you  sale." ]);
         }

         $product = Product::find($request->foodId);
         //checking discount price is enabled for this product
         if($product->discount_price){
             $sale_price = $product->discount_price;
         }else{
             $sale_price = $product->price;
         }

        //checking the product whether it is already added to the sale cart, if so we sent the message this product is already added to the cart.
        $sale = Sale::where('product_id', $request->foodId)
                    ->where('order_tbl_no', $request->tblId)
                    ->where('ordersale_id', NULL) 
                    ->first();
        if(!is_null($sale)){
            $message = "This food is already added to the Table No. " . $request->tblId;
            return json_encode([ 'status' => 'info', 'message' => $message ]);
        }
        else{
            // adding new item to the sale cart
            $sale = new Sale();
            $sale->admin_id = Auth::id(); 
            $sale->product_id = $request->foodId;
            $sale->product_name = $request->foodName;
            $sale->order_tbl_no = $request->tblId;
            $sale->unit_price = $sale_price;
            $sale->product_quantity = 1;
            $sale->save();

            return json_encode([ 'status' => 'success', 'foodname' => $sale->product_name , 'price' => $sale->unit_price, 'qty' => $sale->product_quantity, 'id' => $sale->id , 'sub_total' => $this->calculateSubtotal($request->tblId) ]);
            
        }          
        
    }

    public function update(Request $request)
    {  
        $id = $request->sale_id;  
        $tblId = $request->tblId;
        $sale = Sale::find($id);//primary key id
        $sale->product_quantity = $request->product_quantity;           
        $sale->save();
        
        if($sale->product->discount_price){
                $total_unit_price = $sale->product->discount_price * $sale->product_quantity;
        }else{
                $total_unit_price = $sale->product->price * $sale->product_quantity;
        }
         
        return json_encode([ 'status' => 'success', 'total_unit_price' => $total_unit_price, 'sub_total' => $this->calculateSubtotal($tblId) ]);      
    }

    public function destroy(Request $request)
    {
        $sale = Sale::find($request->sale_id);
        $tblId = $request->tblId;
        if(!is_null($sale)){            
            $sale->delete();
            return json_encode([ 'status' => 'success', 'message' => 'Item is deleted', 'sub_total' => $this->calculateSubtotal($tblId) ]); 
        }
        return json_encode(['status' => 'error', 'message' => 'An error is occurred while deleting the cart' ]);
    }  
    
    public function calculateSubtotal($tblId){
        // calculating subtotal
        $total_taka=0.0;
        foreach(Sale::where('order_tbl_no', $tblId)->where('ordersale_id',NULL)->get() as $sale){      
          
            if($sale->product->discount_price){
                    $total_taka += $sale->product->discount_price * $sale->product_quantity;
            }else{
                    $total_taka += $sale->product->price * $sale->product_quantity;
            }         
        }
        return $total_taka;
    }
    /*
     * Getting list of foods for a table. 
     */
    public function getTableFoods($tblId){
        $carts = Sale::where('order_tbl_no', $tblId)->where('ordersale_id',NULL)->get();
        return json_encode($carts); // Returns a collection to a string containing the JSON representation of value.
    }
}
