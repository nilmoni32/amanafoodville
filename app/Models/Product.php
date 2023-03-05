<?php

namespace App\Models;

use App\Models\Userlog;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * @var string
     */
    protected $table = 'products';

    /**
     * @var array
    */
    protected $fillable = [
        'name','slug','description','price','discount_price','status','featured'
    ];

    /**
     * @var array
     */
    
    protected $casts = [        
         'status' => 'boolean',
         'featured' => 'boolean',
    ];

    /**
     * Adding mutator for Product model for product name field.
     * This mutator will be called automatically whenever we set the value of the name attribute to the model.
     * this mutator will save the slug field automatically whenever we create a product or save it.
     * @var $value
     */

    public function setNameAttribute($value)
    {
    $this->attributes['name'] = $value;
    $this->attributes['slug'] = Str::slug($value);
    }

    /**
     * Defining One to Many Relations between Product and ProductImages
    * so we have more than one image for a product
    * @return \Illuminate\Database\Eloquent\Relations\HasMany      
    */
    public function images(){
        return $this->hasMany(ProductImage::class);
    }

    /**
     * Defining One to Many Relations between Product and ProductAttribute
    * so one product may have many attributes
    * @return \Illuminate\Database\Eloquent\Relations\HasMany      
    */
    public function attributes(){
        return $this->hasMany(ProductAttribute::class);
    }

    /**
     * Defining many to many relationships between Category and Products
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories(){
        return $this->belongsToMany(Category::class, 'product_categories', 'product_id', 'category_id');
    }

    /**
     * Get the recipe and its all ingredients .
     */
    public function recipe()
    {
        return $this->hasOne(Recipe::class);
    }

    /**
     * Product has many logs
     */
    public function userlog(){
        return $this->hasMany(Userlog::class);        
    }

}
