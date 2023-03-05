<?php

namespace App\Repositories;

use App\Contracts\BaseContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * class BaseRepository implements BaseContract interface
 * @package App\Repositories
 */

class BaseRepository implements BaseContract{

    /**
     * @var $model
     */

    protected $model;

    public function __construct(Model $model){
        $this->model = $model;                  // injecting eloquent model class using the class constructor.
    }

    /**
     * Creating a model instance attributes
     * @param array $attributes
     * @return mixed
     */

    public function create(array $attributes)
    {

        return $this->model->create($attributes);
    }

    /**
    * Updating a model instance
    * @param array $attributes
    * @param int $id 
    * @return bool
    */

    public function update(array $attributes, int $id):bool
    {
        return $this->model->find($id)->update($attributes);
    }

    /**
    * Return all model rows
    * to select all the fields of a model we use array('*') or ['*']
    * @param array $columns
    * @param string $orderBy
    * @param string $sortBy
    * @return mixed
    */

    public function all($columns = ['*'], string $orderBy = 'id', string $sortBy = 'desc'){
        
        return $this->model->orderBy($orderBy, $sortBy)->get($columns);
    }

    /**
     * Find single row data by an id
     * @param int $id
     * @return mixed
     */
    public function find(int $id){

        return $this->model->find($id);
    }

    /**
     * Find single row by an id or throw a ModelNotFoundException exception
     * @param int $id
     * @return mixed
     * The findOrFail or firstOrFail methods will retrieve the first result of the query; however, if not found, an exception will be thrown:
     */

    public function findOneOrFail(int $id){

        return $this->model->findOrFail($id);         
    }

    /**
     * Find all based on a different column 
     * @param array $data
     * @return mixed
     */
    public function findBy(array $data){
        //the get() method is used when a condition is applied.
        // we can also use all() method here.
        return $this->model->where($data)->get();
    }

    /**
     * Find one based on a different column
     * @param array $data
     * @return mixed
     */
    public function findOneBy(array $data){
        return $this->model->where($data)->first();
    }

    /**
     * Find one based on a different column or through a ModelNotFoundException exception
     * @param array $data
     * @return mixed
     */
    public function findOneByOrFail(array $data){
        return $this->model->where($data)->firstOrFail();
    }

    /**
     * Delete one by an Id
     * @param int $id
     * @return bool
     */

    public function delete(int $id):bool
    {
        return $this->model->find($id)->delete();
    }






}