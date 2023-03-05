<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use Auth;
use App\Models\ProductAttribute;
use App\Models\Product;

class CartController extends Controller
{
    /**
     * Display a listing of the cart items
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('site.pages.cart');
    }
   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {         
        if(!(Auth::check())){
            $cookie_name = "user_id";
            $user_id = $_COOKIE[$cookie_name];
        }	

        if($request->attribute_id){ // checking product attribute table data

            if(Auth::check()){ //Auth::check() to check if the user is logged in
                // when logged user adds products to cart. 
                $cart = Cart::where('user_id', Auth::id())
                            ->where('product_id', $request->product_id)
                            ->where('product_attribute_id', $request->attribute_id) // when attribute table, cart product id should be attribute_id.
                            ->where('has_attribute', 1) 
                            ->where('order_id', NULL)                           
                            ->first();
            }else{
                // when a guest adds product to cart.           
                $cart = Cart::where('ip_address',  $user_id)
                        ->where('product_id', $request->product_id)
                        ->where('product_attribute_id', $request->attribute_id) // when attribute table, cart product id should be attribute_id.
                        ->where('has_attribute', 1)                      
                        ->first();
            }   

        }else{ // checking product table data

            if(Auth::check()){ //Auth::check() to check if the user is logged in
                // when logged user adds products to cart. 
                $cart = Cart::where('user_id', Auth::id())
                            ->where('product_id', $request->product_id)
                            ->where('order_id', NULL) 
                            ->where('has_attribute', 0)                            
                            ->first();
            }else{
                // when a guest adds product to cart.           
                $cart = Cart::where('ip_address',  $user_id)
                        ->where('product_id', $request->product_id)
                        ->where('has_attribute', 0)                    
                        ->first();
            }   


        }
            
        // when a product is already added and need to increase product's quantity        
        if(!is_null($cart)){
            $cart->increment('product_quantity');
        }else{
             //when a guest or logged user adds a new product to cart  
            $cart = new Cart();
            //adding user id to cart for logged user.
            if(Auth::check()){
                $cart->user_id = Auth::id();                
            }else{
            // when user is not logged in and we store the unkown guest ip address to cart
                $cart->ip_address = $user_id;
            }

            if(!$request->attribute_id){  // if no product attribute is present
                $cart->product_id = $request->product_id; // getting the product id when add to cart button is clicked.
                // setting product price into the cart.
                if(Product::find($request->product_id)->discount_price){
                    $cart->unit_price = Product::find($request->product_id)->discount_price;
                }else{
                    $cart->unit_price = Product::find($request->product_id)->price;
                }
              
            }
            else{ // if product attribute is present then we set has_attribute to 1 and product_id to attribute product id.
                $cart->product_id = $request->product_id; 
                $cart->product_attribute_id = $request->attribute_id; // getting the product attribute id when add to cart button is clicked. 
                $cart->has_attribute = 1; // setting the attribute flag to 1.
                // setting product attribute price into the cart.
                if(ProductAttribute::find($request->attribute_id)->special_price){
                    $cart->unit_price = ProductAttribute::find($request->attribute_id)->special_price;
                }else{
                    $cart->unit_price = ProductAttribute::find($request->attribute_id)->price;
                }

            }             
            $cart->save(); 
        }
              
        return json_encode([ 'status' => 'success', 'message' => 'Item is added to cart', 'total_carts' => Cart::totalCarts()->count()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {  
         $id = $request->cart_id;        
         $cart = Cart::find($id);//primary key id
         $cart->product_quantity = $request->product_quantity;           
         $cart->save();

         //calculating total single cart price
         if($cart->has_attribute){ 
            if(ProductAttribute::find($cart->product_attribute_id)->special_price){
                    $total_unit_price = ProductAttribute::find($cart->product_attribute_id)->special_price * $cart->product_quantity;
            }else{
                    $total_unit_price = ProductAttribute::find($cart->product_attribute_id)->price * $cart->product_quantity;
            }
        }else{  //if has_attribute = 0 then we face data from product table
            if($cart->product->discount_price){
                    $total_unit_price = $cart->product->discount_price * $cart->product_quantity;
            }else{
                    $total_unit_price = $cart->product->price * $cart->product_quantity;
            }
        }                  
        
         
         return json_encode([ 'status' => 'success', 'message' => 'Item is updated to cart', 'total_items' => Cart::totalItems(), 'total_unit_price' => $total_unit_price, 'sub_total' => Cart::calculateSubtotal()]);      
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $cart = Cart::find($request->cart_id);
        if(!is_null($cart)){            
            $cart->delete();
            return json_encode([ 'status' => 'success', 'message' => 'Item is deleted', 'total_items' => Cart::totalItems(), 'total_carts' => Cart::totalCarts()->count(), 'sub_total' => Cart::calculateSubtotal()]); 
        }
        return json_encode(['status' => 'error', 'message' => 'An error is occurred while deleting the cart' ]);
    }   


    
}
