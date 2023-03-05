<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Admin;
use App\Models\Product;
use App\Models\ComplimentaryOrdersale;

class Complimentarysale extends Model
{
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = ['product_id', 'admin_id', 'complimentary_ordersales_id', 'product_name','product_quantity', 'unit_price','order_tbl_no'];

    public function admin(){
        return $this->belongsTo(Admin::class);
    }

    public function product(){

        return $this->belongsTo(Product::class);
    }

    public function complimentaryordersale(){

        return $this->belongsTo('App\Models\ComplimentaryOrdersale','complimentarysales_id');
    }
}
