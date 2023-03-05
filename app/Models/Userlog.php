<?php

namespace App\Models;

use App\Models\Admin;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class Userlog extends Model
{
    protected $fillable = ['admin_id', 'product_id','done_by', 'log_date', 'description'];

    public function admin(){
        return $this->belongsTo(Admin::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }

    /***
     * store signin or logout log info using EventServiceProvider
     */
    // public static function sign_in_out($description){

    //     $user_log = new Userlog();           
    //     $user_log->log_date = \Carbon\Carbon::now()->toDateTimeString();
    //     $user_log->description = $description;
    //     $user_log->save();
    // }

    /**
     * New User Create or Delete and Existing user Role update
     */
    public static function user_role($type, $description){

        //saving log for the creation of new user.
        $user_log = new Userlog();
        $user_log->admin_id = auth()->user()->id; 
        $user_log->done_by =  auth()->user()->name; 
        $user_log->log_type = $type; 
        $user_log->log_date = \Carbon\Carbon::now()->toDateTimeString();
        $user_log->description =  $description; 
        $user_log->save();
    }

    /**
     * Product Price up and down log.
     */
     public static function product_price_up_down($name, $id, $old_price, $new_price, $old_discount_price, $new_discount_price){

        if($old_price != $new_price && $old_discount_price == $new_discount_price){
            $description = "The Price of Food '{$name}' is changed from ".($old_price ? $old_price : 0)." tk to ".
            ($new_price ? $new_price : 0) ." tk by ".auth()->user()->name;

        }else if($old_price == $new_price && $old_discount_price != $new_discount_price){
            $description = "The Discount Price of Food {$name} is changed from ". ($old_discount_price ? $old_discount_price : 0)
            ." tk to ".($new_discount_price ? $new_discount_price : 0)."  tk by ".auth()->user()->name;
        }        

        $user_log = new Userlog();
        $user_log->admin_id = auth()->user()->id; 
        $user_log->product_id = $id;      //product id   
        $user_log->done_by =  auth()->user()->name;
        $user_log->log_type = "Food Price Change"; 
        $user_log->log_date = \Carbon\Carbon::now()->toDateTimeString();
        $user_log->description =  $description; 
        $user_log->save();

     }

     public static function Ingredient_purchase_price_up_down($name, $id, $old_price, $new_price){

        $description = "The ingredient '{$name}' purchase price is changed from ".round($old_price,2). " tk to ". round($new_price,2)." tk by ".auth()->user()->name .".";

        $user_log = new Userlog();
        $user_log->admin_id = auth()->user()->id; 
        $user_log->product_id = Null;  // ingredient id       
        $user_log->done_by =  auth()->user()->name;
        $user_log->log_type = "Ingredient Price Change"; 
        $user_log->log_date = \Carbon\Carbon::now()->toDateTimeString();
        $user_log->description =  $description; 
        $user_log->save();
           
     }

    

}
