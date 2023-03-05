<?php

namespace App\Models;

use App\Models\Ordersale;
use Illuminate\Database\Eloquent\Model;

class Ordersalepayment extends Model
{
   /**
     * @var array
    */
    protected $fillable = [
        'ordersale_id','payment_method','bank_name','store_paidamount','cash_exchange', 'customer_paid_amount', 'card_mobile_discount'
    ];

    /*
     * All payments have a Ordersale No
     */

    public function ordersale(){
        return $this->belongsTo(Ordersale::class);
    }
}
