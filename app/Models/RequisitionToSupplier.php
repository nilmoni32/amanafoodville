<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequisitionToSupplier extends Model
{
      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'supplier_id', 'admin_id', 'requisition_date', 'expected_delivery', 'purpose', 'remarks', 
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
    * Defining One to Many Relations 
    * @return \Illuminate\Database\Eloquent\Relations\HasMany      
    */
    public function receiveChalans(){
        return $this->hasMany(ReceiveFromSupplier::class);
    }

    /**
    * Defining One to Many Relations 
    * @return \Illuminate\Database\Eloquent\Relations\HasMany      
    */
    public function Requisition_ingredients(){
        return $this->hasMany(RequisitionIngredientList::class);
    }

    public function admin(){
        return $this->belongsTo(Admin::class);
    }



}

