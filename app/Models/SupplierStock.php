<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierStock extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ingredient_id', 'supplier_id', 'typeingredient_id', 'supplier_product_name', 'measurement_unit', 'has_differ_product_unit', 
        'product_unit','product_qty','unit_cost', 'total_qty', 'total_cost',
    ];

     /**
     * Get thesupplier Name associated with the supplier products table.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);  
    }


    /**
     * Get the supplier Name associated with the supplier products table.
    */
    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);  
    }


    /**
    * Get the requision supplier product lists.
    */
    public function RequisitionProducts(){
        return $this->hasMany(RequisitionIngredientList::class);
    }

    /**
    * Get the receive supplier product lists.
    */
    public function supplierChalanProducts(){
        return $this->hasMany(ReceiveIngredientList::class);
    }

    /**
    * Get the receive supplier product lists.
    */
    public function supplierReturnProducts(){
        return $this->hasMany(ReturnIngredientList::class);
    }

    
}
