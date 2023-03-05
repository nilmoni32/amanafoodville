<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Admin;
use App\Models\Product;
use App\Models\Ordersale;

class Salebackup extends Model   
{   // salebackup is backup for pos sale cart
      
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'product_id', 'admin_id', 'ordersale_id', 'product_name','product_quantity', 'unit_price', 'production_food_cost', 'order_cancel', 'order_tbl_no'
    ];

    public function ordersale(){
        return $this->belongsTo(Ordersale::class);
    }

    public function admin(){
        return $this->belongsTo(Admin::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
