<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $fillable = [
        'typeingredient_id','name', 'description', 'total_quantity', 'total_price', 'alert_quantity',
        'measurement_unit', 'smallest_unit', 'smallest_unit_price', 'status', 'pic', 
    ];

    protected $casts = [
       // 'typeingredient_id' =>  'integer',     
    ];


    /**
     * Defining inverse relationship  
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function typeingredient(){
        return $this->belongsTo(Typeingredient::class);        
    }

    /**
    * Defining One to Many Relations 
    * @return \Illuminate\Database\Eloquent\Relations\HasMany      
    */
    public function purchases(){
        return $this->hasMany(IngredientPurchase::class);
    }

    /**
    * Defining One to Many Relations 
    * @return \Illuminate\Database\Eloquent\Relations\HasMany      
    */
    public function damages(){
        return $this->hasMany(IngredientDamage::class);
    }

    /**
    * Get the Food menu recipes ingredient lists.
    */
    public function recipeingredients(){
        return $this->hasMany(RecipeIngredient::class);
    }



}
