<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Models\Sale;
use App\Models\Unit;
use App\Models\Recipe;
use App\Models\Product;
use App\Models\Category;
use App\Models\Ordersale;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use App\Traits\FlashMessages;
use App\Http\Controllers\Controller;

class PosRestaurantController extends Controller
{
    use FlashMessages;

    public function index($id){
        // Attaching pagetitle and subtitle to view.
        view()->share(['pageTitle' => 'Kitchen Order Ticketing System', 'subTitle' => 'Add Foods to a particular table' ]);
        return view('admin.sales.restaurant.index')->with('order_id', $id);
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

        /*commented out only only readymade food sale */
        // checking the food is added to the recipe
        // if(!Recipe::where('product_id', $request->foodId)->first()){
        //     return json_encode([ 'status' => 'info', 'message' => "Please add '". $request->foodName ."' food recipe before you sale." ]);
        // }// recipe is added but recipe ingredients is not added for the food
        //  elseif(!Recipe::where('product_id', $request->foodId)->first()->recipeingredients->count()){
        //      return json_encode([ 'status' => 'info', 'message' => "Please add '". $request->foodName ."' food recipe ingredients before you  sale." ]);
        // }
        //  else{
        //     //when stock ingredient total quantity is zero or negative after sales.
        //     foreach(Recipe::where('product_id', $request->foodId)->first()->recipeingredients as $ingredient){
        //        if(Ingredient::where('id', $ingredient->ingredient_id)->first()->total_quantity <= 0){
        //             return json_encode([ 'status' => 'info', 'message' => "Please add purchase record for ingredient '".
        //             Ingredient::where('id', $ingredient->ingredient_id)->first()->name ."' of food '". $request->foodName ."' before sale." ]);
        //         }
        //     }

        // }

        //checking the product whether it is already added to the sale cart, if so we sent the message this product is already added to the cart.
        $sale = Sale::where('admin_id', Auth::id())
                    ->where('product_id', $request->foodId)
                    ->where('ordersale_id', NULL)
                    ->first();
        if(!is_null($sale)){
            return json_encode([ 'status' => 'info', 'message' => "This food is already added to the cart."  ]);
        }
        else{
            // adding new item to the sale cart
            $sale = new Sale();
            $sale->admin_id = Auth::id();
            $sale->product_id = $request->foodId;
            $sale->product_name = $request->foodName;
            $sale->unit_price = $sale_price;
            $sale->product_quantity = 1;
            $sale->save();

            return json_encode([ 'status' => 'success', 'foodname' => $sale->product_name , 'price' => $sale->unit_price, 'qty' => $sale->product_quantity, 'id' => $sale->id , 'sub_total' => $this->calculateSubtotal() ]);

        }

    }

    public function update(Request $request)
    {
        $id = $request->sale_id;
        $sale = Sale::find($id);//primary key id
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
        $sale = Sale::find($request->sale_id);
        if(!is_null($sale)){
            $sale->delete();
            return json_encode([ 'status' => 'success', 'message' => 'Item is deleted', 'sub_total' => $this->calculateSubtotal() ]);
        }
        return json_encode(['status' => 'error', 'message' => 'An error is occurred while deleting the cart' ]);
    }

    public function calculateSubtotal(){
        // calculating subtotal
        $total_taka=0.0;
        foreach(Sale::where('admin_id', Auth::id())->where('ordersale_id',NULL)->get() as $sale){

            if($sale->product->discount_price){
                    $total_taka += $sale->product->discount_price * $sale->product_quantity;
            }else{
                    $total_taka += $sale->product->price * $sale->product_quantity;
            }
        }
        return $total_taka;
    }


    public function orderplace(Request $request){
        // if no items are added to sale cart
        if(!$this->calculateSubtotal()){
            // setting flash message using trait
           $this->setFlashMessage(' Please add items to the cart', 'error');
           $this->showFlashMessages();
           return redirect()->back();
        }

        // //before placement an order we need to check if the food recipe is added of the same food product or not.
        // foreach(Sale::where('admin_id', Auth::id())->where('ordersale_id',NULL)->get() as $sale){
        //     // if recipe is added for the food
        //     if(!Recipe::where('product_id', $sale->product_id)->first()){
        //          // setting flash message using trait
        //         $this->setFlashMessage(" You might forget to add '".$sale->product_name."' food recipe that you want to sale", 'error');
        //         $this->showFlashMessages();
        //         return redirect()->back();
        //     }// redipe is added but recipe ingredients is not added for the food
        //     elseif(!Recipe::where('product_id', $sale->product_id)->first()->recipeingredients->count()){
        //         // setting flash message using trait
        //         $this->setFlashMessage(" You might forget to add '".$sale->product_name."' food recipe ingredients which you want to sale", 'error');
        //         $this->showFlashMessages();
        //         return redirect()->back();
        //     }
        // }

        $this->validate($request,[
            'order_tableNo'    => 'required|string|max:10',
        ]);
        //checking the table no for usability
        if(Ordersale::where('order_tableNo', $request->order_tableNo)->first()){
            // setting flash message using trait
            $this->setFlashMessage(" Your selected table '".$request->order_tableNo."' is currently in use, please select another table", 'error');
            $this->showFlashMessages();
            return redirect()->back();
        }

        // finding last order id: we use it for customer order id (customized) for billing purpose
        // it will be false only for the first record.
        if(!Ordersale::orderBy('id', 'desc')->first()){
            $ord_id = 0;
        }
        else{
            $ord_id = Ordersale::orderBy('id', 'desc')->first()->id;
        }
        $ord_id = '#'.(10000 + ($ord_id + 1));

        $order = new Ordersale(); // we use order_id as online transaction id.
        $order->admin_id = auth()->user()->id;
        $order->order_number = $ord_id;
        $order->order_date = \Carbon\Carbon::now()->toDateTimeString();
        $order->order_tableNo = $request->order_tableNo;
        $order->save();
        // when order is placed we set ordersale_id and order_tbl_no to Sale cart
        foreach(Sale::where('admin_id', Auth::id())->where('ordersale_id',NULL)->get() as $sale){
            $sale->ordersale_id = $order->id;
            $sale->order_tbl_no = $order->order_tableNo;
            $sale->save();
        }
        // setting flash message using trait
        $this->setFlashMessage(' Order is placed successfully', 'success');
        $this->showFlashMessages();

        return redirect()->route('admin.restaurant.sales.index', $order->id);

    }



}
