<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IngredientPurchase extends Model
{
    protected $fillable = [
        'ingredient_id','name', 'purchase_date', 'expire_date', 'quantity', 'unit',
        'price', 'added_by'
    ];

    /**
     * Defining inverse relationship  
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ingredient(){
        return $this->belongsTo(Ingredient::class);        
    }

}
