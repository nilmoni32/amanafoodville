<?php

namespace App\Repositories;

use App\Models\Product;
use App\Traits\UploadAble; 
use Illuminate\Http\UploadedFile;
use App\Contracts\ProductContract; 
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

/**
 * Class ProductRepository
 * @package App\Repositories
 */

class ProductRepository extends BaseRepository implements ProductContract{

    use UploadAble;

    public function __construct(Product $model){        
        parent::__construct($model); // calling base class (BaseRepository) constructor.        
    }

    /**
     * this method return all the products from the databases using all() method inherited from the BaseRepository class.
     * we are not using Product::all() here.
     * @param string $order
     * @param string $sort
     * @param array $columns
     * @return mixed 
     */
    public function listProducts(string $order = 'id', string $sort = 'desc', array $columns = ['*']){
        return $this->all($columns, $order, $sort);
    }

    /**
     * Return a single Product by id
     * @param int $id
     * @return mixed
     * @throws ModelNotFoundException
     */
    
    public function findProductById(int $id){

        try{
            return $this->findOneOrFail($id); // this method is provided from BaseRepository.
        }
        catch (ModelNotFoundException $e){
            throw new ModelNotFoundException($e);
        }
    }

    /**
     * Add a new product
     * @param $params
     * @return Product|mixed
     */

    public function createProduct(array $params){
        // php collection: It is based on simple in PHP's non-associative arrays. learn more: https://coderwall.com/p/4inomq/php-collections
        try{
            $collection = collect($params); // converting to collection using collect helper function.
            // checking for featured and status attribute & reassigning the boolean values based on their presence
            // has key determines if a given key exists in the collection.
            $featured = $collection->has('featured') ? 1 : 0;
            $status = $collection->has('status') ? 1 : 0;
            // compact creates an array from variables like ['name' => 'Peter', 'age' => 41]
            // merging all values in the existing collection.
            $merge = $collection->merge(compact('status', 'featured'));            
            // this is similar to php artisan tinker command [$model = new App\model();] data is inserted by 'key' => $value
            // here all data input is provided by an collection.
            $product = new Product($merge->all());            
            
            $product->save();
            //the sync method to construct many-to-many associations. 
            //The sync method accepts $params['categories'] ID to place on the intermediate table. 
            if($collection->has('categories')){
                $product->categories()->sync($params['categories']);
            }
            return $product;

        }catch(QueryException $exception){
            throw new InvalidArgumentException($exception->getMessage());            
        }
     }

    /**
     * Update the product
     * @param array $params
     * @return Category|mixed
     */

    public function updateProduct(array $params){
     
        $product = $this->findProductById($params['product_id']); // getting the single product model        

        $collection = collect($params)->except('_token'); // making collection with the exception of csrf token               

        $featured = $collection->has('featured') ? 1 : 0;
        $status = $collection->has('status') ? 1 : 0;

        $merge = $collection->merge(compact('status', 'featured'));
                
        // $merge->all() collection returns an array
        $product->update($merge->all()); // passing an array argument 
        
        //the sync method to construct many-to-many associations. 
        //The sync method accepts $params['categories'] ID to place on the intermediate table.
        //using  Product instance calling categories method for the associations.
        if($collection->has('categories')){
            $product->categories()->sync($params['categories']);
        }        

        return $product;
     }

    /**
     * @param $id
     * @return bool|mixed
     */
    public function deleteProduct($id)
    {
        $product = $this->findProductById($id);

        $product->delete();

        return $product;
    }

}