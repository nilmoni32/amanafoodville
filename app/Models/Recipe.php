<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id', 'production_food_cost'
    ];

    /**
     * Get the Food menu Name associated with the Products table.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);  
    }

    /**
    * Get the Food menu recipes ingredient lists.
    */
    public function recipeingredients(){
        return $this->hasMany(RecipeIngredient::class);
    }

    public static function recipeCost(){
        
    }

}
