<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecipeIngredient extends Model
{
   /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'recipe_id', 'ingredient_id', 'quantity', 'measure_unit', 'unit_price', 'ingredient_total_cost'
    ];

     /**
     * Get the Food menu Name or Recipe name associated with the Recipes table.
     */
    public function recipe()
    {
        return $this->belongsTo(Recipe::class);  
    }

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);  
    }
}
