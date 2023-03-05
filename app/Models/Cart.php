<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Cart;
use App\Models\ProductAttribute;
use Illuminate\Http\Request;
use Auth;

class Cart extends Model
{
    
    protected $fillable = ['product_id', 'user_id', 'order_id', 'product_attribute_id','product_quantity', 'ip_address', 'has_attribute', 'unit_price', 'order_cancel', 'production_food_cost' ];

    public function user(){

        return $this->belongsTo(User::class);
    }

    public function product(){

        return $this->belongsTo(Product::class);
    }
    
    public function subproduct(){

        return $this->belongsTo(ProductAttribute::class);
    }

    public function order(){

        return $this->belongsTo(Order::class);
    }

    /**
     * Getting the cart model
     * @return integer the cart model
     */
    public static function totalCarts(){

        if(Auth::check()){
            $cart = Cart::where('user_id', Auth::id())->where('order_id', NULL)->get();
           
        }
        else{
	     $cookie_name = "user_id";
             $cart = Cart::where('ip_address', $_COOKIE[$cookie_name])->get(); // getting all cart rows for the guest user.
        }
        return $cart;
    }

    /**
     * Total items in the cart for a particular user
     * @return integer 
     */

    public static function totalItems(){
        if(Auth::check()){
            $carts = Cart::where('user_id', Auth::id())->where('order_id', NULL)->get(); // getting all cart rows for the logged user.       
        }
        else{
	    $cookie_name = "user_id";
            if(!isset($_COOKIE[$cookie_name])){
                $cookie_value = uniqid();
                setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); 
                return 0;
            }else {
                $carts = Cart::where('ip_address', $_COOKIE[$cookie_name])->get(); // getting all cart rows for the guest user.      
            }

            //$carts = Cart::where('ip_address', request()->ip())->get(); // getting all cart rows for the guest user.              
        }

        $total_cartitems =0 ;

        foreach($carts as $cart){
            $total_cartitems += $cart->product_quantity;   
        }

        return $total_cartitems;
    }

    public static function calculateSubtotal(){
        // calculating subtotal
        $total_taka=0.0;
        foreach(Cart::totalCarts() as $cart){
            //if has_attribute = 1 then we face data from product attribute
            if($cart->has_attribute){ 
                if(ProductAttribute::find($cart->product_attribute_id)->special_price){
                        $total_taka += ProductAttribute::find($cart->product_attribute_id)->special_price * $cart->product_quantity;
                }else{
                        $total_taka += ProductAttribute::find($cart->product_attribute_id)->price * $cart->product_quantity;
                }
            }else{  //if has_attribute = 0 then we face data from product table
                if($cart->product->discount_price){
                        $total_taka += $cart->product->discount_price * $cart->product_quantity;
                }else{
                        $total_taka += $cart->product->price * $cart->product_quantity;
                }
            } 
        }

        return $total_taka;

    }
    /**
     * when guest add items to cart and then he is logged in or sign in then we need to set that user id to cart
     * so that he can see the recently added products in the cart after sign in.
     */

    public static function guestAuthenticatedCart(){
        // finding those carts where user id and order id are null.
       // and we will set this user id for these carts.
       $cookie_name = "user_id";
       $carts = Cart::where('ip_address',  $_COOKIE[$cookie_name])->where('user_id', NULL)->where('order_id', NULL)->get();
       
       if($carts){
            foreach($carts as $cart){
                $cart->user_id = Auth::id();
                $cart->ip_address = NULL;  // ip_address is used only for guest users so we set ip_address = null for the authenticated users.
                $cart->save(); 
            }
       }
       // deleting the cookie
       // Set the expiration date to one hour ago
          setcookie("user_id", "", time() - 3600);
	
    }

}
