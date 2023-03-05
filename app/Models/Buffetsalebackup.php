<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Admin;
use App\Models\Product;
use App\Models\Buffet;
use App\Models\Buffetorder;

class Buffetsalebackup extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'buffet_id', 'product_id', 'admin_id', 'buffetorder_id', 'product_name', 'product_quantity',
         'unit_price', 'production_food_cost', 'order_cancel', 'order_tbl_no'
    ];

    public function admin(){
        return $this->belongsTo(Admin::class);
    }

    public function product(){

        return $this->belongsTo(Product::class);
    }

    public function buffet(){

        return $this->belongsTo(Buffet::class);
    }

    public function buffetorder(){

        return $this->belongsTo(Buffetorder::class);
    }
}
