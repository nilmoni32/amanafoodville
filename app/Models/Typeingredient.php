<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Typeingredient extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'parent_id'
    ];

    protected $casts = [
        'parent_id' =>  'integer',     
    ];

    //adding mutator for Typeingredient model for name field.
    //This mutator will be automatically called when we attempt to set the value of the name attribute 
    //this mutator will save the slug field automatically whenever we create or save a category.
    public function setNameAttribute($value){
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    //creating parent-child relationship for category

    // to get the parent category of a category to define the parent relationship
    public function parent(){
        return $this->belongsTo(Typeingredient::class, 'parent_id');
    }
     // to get the children of a category
    public function children(){
        return $this->hasMany(Typeingredient::class,'parent_id');
    }

    /**
    * Defining One to Many Relations 
    * @return \Illuminate\Database\Eloquent\Relations\HasMany      
    */
    public function ingredients(){
        return $this->hasMany(Ingredient::class);
    }


}
