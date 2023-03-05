<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buffet extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'buffet_name', 'unit_cost_price', 'unit_sale_price', 'buffet_made_for_no_of_people', 'buffet_served_for_no_of_people'
    ];
    

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function buffetRecipes()
    {
        return $this->hasMany(BuffetRecipe::class);
    }
}
