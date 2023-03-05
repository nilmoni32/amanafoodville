<?php

namespace App\Models;

use App\Models\Buffetsale;
use App\Models\Admin;
use App\Models\Client;
use App\Models\Ordersalepayment;
use Illuminate\Database\Eloquent\Model;

class Buffetorder extends Model
{
   /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'admin_id', 'client_id', 'director_id', 'order_number', 'grand_total', 'order_date', 'order_tableNo',
         'status', 'discount','reward_discount','payment_method', 'cash_pay', 'card_pay','mobile_banking_pay', 'card_discount', 'mobile_discount',
         'fraction_discount', 'gpstarmobile_no', 'gpstar_discount','vat'
    ];

    public function admin(){
        return $this->belongsTo(Admin::class);
    }

    public function client(){
        return $this->belongsTo(Client::class);
    }

    /**
     * Order have many carts
     */
    public function buffetsales(){
        return $this->hasMany(Buffetsale::class);        
    }

     /**
     * Order have many payment options
     */
    public function salepayments(){
        return $this->hasMany(Ordersalepayment::class);        
    }
}
