<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuffetRecipe extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'buffet_id', 'recipe_id', 'recipe_cost_price', 'recipe_sale_price',
    ];

    /**
     * Get the Food menu Name or Recipe name associated with the Recipes table.
     */
    public function recipe()
    {
        return $this->belongsTo(Recipe::class);  
    }

    public function buffet()
    {
        return $this->belongsTo(Buffet::class);  
    }
}
