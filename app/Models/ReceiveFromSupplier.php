<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReceiveFromSupplier extends Model
{
       /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'supplier_id', 'admin_id', 'requisition_to_supplier_id','chalan_no', 'chalan_date', 'payment_date', 'requisition_date', 
        'expected_delivery', 'purpose', 'remarks', 'applyDiscount', 'totalFreeQuantity', 'total_quantity',
        'total_amount',
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
    public function requisition(){
        return $this->belongsTo(RequisitionToSupplier::class);        
    }

    /**
    * Defining One to Many Relations 
    * @return \Illuminate\Database\Eloquent\Relations\HasMany      
    */
    public function chalan_ingredients(){
        return $this->hasMany(ReceiveIngredientList::class);
    }

    /**
    * Defining One to Many Relations 
    * @return \Illuminate\Database\Eloquent\Relations\HasMany      
    */
    public function returnIngredients(){
        return $this->hasMany(ReturnToSupplier::class);
    }

    public function admin(){
        return $this->belongsTo(Admin::class);
    }


}

