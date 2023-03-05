<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductImage extends Model
{
    /**
     * @var string
     */
    protected $table = 'product_images';
    
    /**
     * @var array
     */
    protected $fillable = [
        'product_id', 'full'
    ];

    /**
     * @var array
     */
    protected $casts = [
        //'product_id' => integer,
    ];

    /**
     * Defining inverse relationship between products and productImages table.
     * it means this product image belongs to a particular product.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(){
        return $this->belongsTo(Product::class);        
    }
    
   

}
