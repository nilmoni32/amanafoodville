<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'category_name', 'slug', 'description', 'parent_id', 'menu', 'image'
    ];

    protected $casts = [
        'parent_id' =>  'integer',        
        'menu'      =>  'boolean'
    ];
    //adding mutator for category model for category name field.
    //This mutator will be automatically called when we attempt to set the value of the category_name attribute on the model:
    //this mutator will save the slug field automatically whenever we create or save a category.
    public function setCategoryNameAttribute($value){
       $this->attributes['category_name'] = $value;
       $this->attributes['slug'] = Str::slug($value);
    }
   
    //creating parent-child relationship for category

    // to get the parent category of a category
    public function parent(){
       return $this->belongsTo(Category::class, 'parent_id');
    }
    // to get the children of a category
    public function children(){
       return $this->hasMany(Category::class,'parent_id');
    }

    /**
     * Defining many to many relationships between Category and Products
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products(){
        return $this->belongsToMany(Product::class, 'product_categories', 'category_id', 'product_id' );
    }



}
