<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Duesale extends Model
{
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'product_id', 'admin_id', 'dueordersale_id', 'product_name','product_quantity', 'unit_price', 'order_cancel', 'order_tbl_no'
    ];

    public function admin(){
        return $this->belongsTo(Admin::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function dueordersale(){
        return $this->belongsTo(Dueordersale::class);
    }
}
