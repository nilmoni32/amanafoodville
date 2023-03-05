<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnToSupplier extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'supplier_id', 'receive_from_supplier_id', 'admin_id','chalan_date', 'purpose', 'remarks',
        'total_quantity', 'total_amount', 
        ];

    /**
     * Defining inverse relationship  
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supplier(){
        return $this->belongsTo(Supplier::class);        
    }

    /**
     * Defining inverse relationship  
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function receiveChalan(){
        return $this->belongsTo(ReceiveFromSupplier::class);        
    }

    /**
    * Defining One to Many Relations 
    * @return \Illuminate\Database\Eloquent\Relations\HasMany      
    */
    public function returnIngredients(){
        return $this->hasMany(ReturnIngredientList::class);
    }

    public function admin(){
        return $this->belongsTo(Admin::class);
    }

}
