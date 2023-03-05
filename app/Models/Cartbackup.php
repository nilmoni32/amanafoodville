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


class Cartbackup extends Model
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
}
