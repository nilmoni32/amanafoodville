<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gpstardiscount extends Model
{
    /**
     * @var array
    */
    protected $fillable = ['gp_star_name', 'discount_percent','status','discount_upper_limit', 'discount_lower_limit'];

    
}
