<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReceiveIngredientList extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'receive_from_supplier_id', 'ingredient_id', 'supplier_stock_id', 'name', 'unit', 'unit_cost', 'quantity',
        'stock', 'total', 'recipe_ingredient_unit', 'recipe_ingredient_quantity'
    ];

    /**
     * Defining inverse relationship  
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function chalanSupplier(){
        return $this->belongsTo(ReceiveFromSupplier::class);        
    }

    
    /**
     * Defining inverse relationship  
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supplier_stock(){
        return $this->belongsTo(SupplierStock::class);        
    }


}
