<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Admin;
use App\Models\Complimentarysale;

class ComplimentaryOrdersale extends Model
{
    protected $fillable = ['admin_id',  'order_number', 'grand_total', 'order_date', 'order_tableNo'];

    public function admin(){
        return $this->belongsTo(Admin::class);
    }
    
    /**
     * One to many relation: Complimentary Order have many sales
     * 
     * Note: we need to specify the foreign key when laravel failed to determine the proper foreign key column automatically.
     * for example laravel assumes foreign key name should be 'complimentaryordersales_id' but we have the foreign key 'complimentary_ordersales_id'. 
     * this is why, we need to specify the foreign key explicitly. this scenario is called snake case.
     */
    public function complimentarysales(){
        return $this->hasMany('App\Models\Complimentarysale', 'complimentary_ordersales_id');        
    }
}
 