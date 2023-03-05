<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'name', 'phone', 'address', 'instantPayment', 'activeSupplier', 
    ];

    /**
    * Defining One to Many Relations 
    * @return \Illuminate\Database\Eloquent\Relations\HasMany      
    */
    public function requisitions(){
        return $this->hasMany(RequisitionToSupplier::class);
    }

    /**
    * Defining One to Many Relations 
    * @return \Illuminate\Database\Eloquent\Relations\HasMany      
    */
    public function receive_chalans(){
        return $this->hasMany(ReceiveFromSupplier::class);
    }

    /**
    * Defining One to Many Relations 
    * @return \Illuminate\Database\Eloquent\Relations\HasMany      
    */
    public function returnSupplierIngredients(){
        return $this->hasMany(ReturnToSupplier::class);
    }

    /**
    * Defining One to Many Relations 
    * @return \Illuminate\Database\Eloquent\Relations\HasMany      
    */
    public function supplierProducts(){
        return $this->hasMany(SupplierStock::class);
    }

    
}
