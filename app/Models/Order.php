<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Cart;


class Order extends Model
{
    protected $fillable = [
        'user_id', 'order_number', 'status', 'payment_status','payment_method', 'grand_total', 'item_count', 'name', 'email', 'phone_no', 'address','district', 'zone', 'order_date', 'delivery_date', 'tran_date', 'tran_id',
        'val_id', 'amount', 'store_amount', 'bank_tran_id',
        'currency_type', 'currency_amount', 'error', 'card_type', 'card_no', 'card_brand', 'card_issuer',
        'card_issuer_country', 'card_issuer_country_code',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    /**
     * Order have many carts
     */
    public function carts(){
        return $this->hasMany(Cart::class);        
    }

}
