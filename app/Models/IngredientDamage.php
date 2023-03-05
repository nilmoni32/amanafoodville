<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IngredientDamage extends Model
{
    protected $fillable = [
        'ingredient_id','name', 'quantity', 'unit', 'price', 'reported_by', 'reported_date'        
    ];

    /**
     * Defining inverse relationship  
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ingredient(){
        return $this->belongsTo(Ingredient::class);        
    }
}
