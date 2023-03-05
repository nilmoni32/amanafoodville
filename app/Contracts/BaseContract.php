<?php
namespace App\Contracts;

/**
 * Interface BaseContract for BaseRepository for all models
 * @package App\Contracts
 */


 interface BaseContract{

    /**
     * Creating a model instance
     * @param array $attributes
     * @return mixed
     */

    public function create(array $attributes);


    /**
     * Updating a model instance
    * @param array $attributes
    * @param int $id 
    * @return mixed
    */

    public function update(array $attributes, int $id);

    /**
     * Return all model rows
    * to select all the fields of a model we use array('*') or ['*']
    * @param array $columns
    * @param string $orderBy
    * @param string $sortBy
    * @return mixed
    */

    public function all($columns = ['*'], string $orderBy = 'id', string $sortBy = 'desc');

    /**
     * Find single row data by an id
     * @param int $id
     * @return mixed
     */
    public function find(int $id);

    /**
     * Find single row by an id or throw an exception
     * @param int $id
     * @return mixed
     */

    public function findOneOrFail(int $id);

    /**
     * Find all based on a different column 
     * @param array $data
     * @return mixed
     */
    public function findBy(array $data);

    /**
     * Find one based on a different column
     * @param array $data
     * @return mixed
     */
    public function findOneBy(array $data);

    /**
     * Find one based on a different column or through an exception
     * @param array $data
     * @return mixed
     */
    public function findOneByOrFail(array $data);

    /**
     * Delete one by an Id
     * @param int $id
     * @return mixed
     */

    public function delete(int $id);

 }