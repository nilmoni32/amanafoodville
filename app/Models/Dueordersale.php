<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dueordersale extends Model
{ 
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'admin_id', 'client_id','director_id', 'order_number', 'grand_total', 'order_date', 'order_tableNo', 'status','discount',
        'reward_discount','payment_method', 'cash_pay', 'card_pay', 'mobile_banking_pay', 'card_discount','mobile_discount',
         'fraction_discount', 'gpstarmobile_no', 'gpstar_discount', 'booked_money','due_payable','order_total','receive_total'
         ,'payment_date','due_status','vat'];

    public function admin(){
        return $this->belongsTo(Admin::class);
    }

    public function client(){
        return $this->belongsTo(Client::class);
    }

    /**
     * Order have many sales carts
     */
    public function duesales(){
        return $this->hasMany(Duesale::class);        
    }

     /**
     * Order have many payment options
     */
    public function salepayments(){
        return $this->hasMany(Ordersalepayment::class);        
    }
}
