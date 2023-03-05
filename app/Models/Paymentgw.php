<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paymentgw extends Model
{
     /**
     * @var array
    */
    protected $fillable = [
        'bank_name','bank_type','discount_percent','discount_upper_limit','discount_lower_limit'
    ];
    
}
