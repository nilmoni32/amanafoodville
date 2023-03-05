<?php

namespace App\Repositories;

use App\Models\Category;
use App\Traits\UploadAble; 
use Illuminate\Http\UploadedFile;
use App\Contracts\CategoryContract; 
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

/**
 * Implementing category contract interface and extending BaseRepository in this class
 * Class CategoryRepository
 * @package App\Repositories
 *  
 */

class CategoryRepository extends BaseRepository implements CategoryContract{
    
    use UploadAble;  // for uploading files we use UploadAble trait.

    /**
     * Injecting Category model class using constructor.
     * CategoryRepository constructor
     * @param Category $model
     * 
     */
    
    public function __construct(Category $model){
        parent::__construct($model); // calling base class (BaseRepository) constructor.        
       // $this->model = $model;     
    }

    /**
     * this method return all the categories from the databases using all() method inherited from the BaseRepository class.
     * we are not using Category::all() here.
     * @param string $order
     * @param string $sort
     * @param array $columns
     * @return mixed 
     */
    public function listCategories(string $order = 'id', string $sort = 'desc', array $columns = ['*']){
        return $this->all($columns, $order, $sort);
    }

    /**
     * Return a single category by id
     * @param int $id
     * @return mixed
     * @throws ModelNotFoundException
     */
    public function findCategoryById(int $id){

        try{
            return $this->findOneOrFail($id); // this method is provided from BaseRepository.
        }
        catch (ModelNotFoundException $e){
            throw new ModelNotFoundException($e);
        }
    }

    /**
     * @param $params
     * @return Category|mixed
     */

     public function createCategory(array $params){
        // php collection: It is based on simple in PHP's non-associative arrays. learn more: https://coderwall.com/p/4inomq/php-collections
        try{
            $collection = collect($params); // converting to collection using collect helper function.
            $image = null;
            // has key determines if a given key exists in the collection.
            // an uploaded file from a request is always a UploadedFile instance, here  $params['key'] is a image file               
           if($collection->has('image') && ($params['image'] instanceof UploadedFile)){                  

                $image = $this->uploadOne($params['image'], 'categories'); // UploadAble trait for upload images( image file, folder name)
            }
            // checking for featured and menu attribute & reassigning the boolean values based on their presence
            //$featured = $collection->has('featured') ? 1 : 0;
            $menu = $collection->has('menu') ? 1 : 0;
            // compact creates an array from variables like ['name' => 'Peter', 'age' => 41]
            // merging all values in the existing collection.
            $merge = $collection->merge(compact('menu', 'image'));
            //$merge = $collection->merge(compact('menu', 'image', 'featured'));
            // this is similar to php artisan tinker command [$model = new App\model();] data is inserted by 'key' => $value
            // here all data input is provided by an collection.
            $category = new Category($merge->all());

            $category->save();

            return $category;

        }catch(QueryException $exception){
            throw new InvalidArgumentException($exception->getMessage());            
        }
     }

    /**
     * @param array $params
     * @return Category|mixed
     */

     public function updateCategory(array $params){
     
        $category = $this->findCategoryById($params['id']); // getting the single category model

        $collection = collect($params)->except('_token'); // making collection with csrf token exception

        if($collection->has('image') && ($params['image'] instanceof UploadedFile)){    

            if($category->image != null){   // if category image presents then we delete it
              $this->deleteOne($category->image); //calling to UploadAble trait for deleting images; here $category->image is path info for images.
            }

            $image = $this->uploadOne($params['image'], 'categories'); // UploadAble trait for upload images( image file, folder name)
        }

        //$featured = $collection->has('featured') ? 1 : 0;
        $menu = $collection->has('menu') ? 1 : 0;

        if($collection->has('image')){
            $merge = $collection->merge(compact('menu', 'image'));
        }else{
            $merge = $collection->merge(compact('menu'));
        }
        //$merge = $collection->merge(compact('menu', 'image', 'featured'));
        // $merge->all() collection returns an array
        $category->update($merge->all()); // passing an array argument 

        return $category;
     }

     /**
      * @param $id
      * @return bool | mixed
      */
      public function deleteCategory($id){

        $category = $this->findCategoryById($id); // it returns a category model.

        if($category->image != null){
            $this->deleteOne($category->image); // using UploadAble trait deleteOne()
        }

        $category->delete(); //deleting the target category

        return $category;
      }

    /**
     * used to find category by slug in fornt section.
     * @param $slug
     * @return mixed
     */
    public function findBySlug($slug){
        // getting the category list that are attached with products        
        return  Category::with('products')->where('slug', $slug)->where('menu', 1)->first();
    }

}