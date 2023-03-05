<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IngredientDisposal extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'admin_id', 'remarks', 'reason',  
    ];

    /**
    * Defining One to Many Relations 
    * @return \Illuminate\Database\Eloquent\Relations\HasMany      
    */
    public function disposal_ingredients(){
        return $this->hasMany(DisposalIngredientList::class);
    }

    public function admin(){
        return $this->belongsTo(Admin::class);
    }
    

    
}

