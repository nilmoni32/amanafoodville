<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisposalIngredientList extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ingredient_disposal_id', 'ingredient_id', 'supplier_stock_id', 'name', 'unit', 'unit_cost', 'quantity', 
        'stock', 'total', 
    ];


    /**
     * Defining inverse relationship  
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supplierProduct(){
        return $this->belongsTo(SupplierStock::class);        
    }


    /**
     * Defining inverse relationship  
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ingredientDisposal(){
        return $this->belongsTo(IngredientDisposal::class);        
    }

    /**
     * Defining inverse relationship  
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supplier_stock(){
        return $this->belongsTo(SupplierStock::class);        
    }



}

