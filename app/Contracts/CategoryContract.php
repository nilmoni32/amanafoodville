<?php
namespace App\Contracts;

/**
 * Interface CategoryContract : have various method signatures.
 * @package App\Contracts
 */

 interface CategoryContract
 {
    /**
     * To select all the fields of a model we use array('*') or ['*']
     * @param string $order
     * @param string $sort
     * @param array $columns
     * @return mixed  
     */
    public function listCategories(string $order = 'id', string $sort = 'desc', array $columns = ['*']);

    /**
     * @param int $id
     * @return mixed
     */
    public function findCategoryById(int $id);

    /**
     * @param array $params
     * @return mixed
     */
    public function createCategory(array $params);

    /**
     * @param array $params
     * @return mixed
     */
    public function updateCategory(array $params);

    /**
     * @param $id
     * @return bool
     */
    public function deleteCategory($id);


     /**
     * used to find category by slug in fornt section.
     * @param $slug
     * @return mixed
     */
    public function findBySlug($slug);


 }