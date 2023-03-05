<?php
namespace App\Contracts;

/**
 * Interface ProductContract : have various method signatures.
 * @package App\Contracts
 */

 interface ProductContract{

    /**
     * To select all the fields of a model we use array('*') or ['*']
     * @param string $order
     * @param string $sort
     * @param array $columns
     * @return mixed  
     */
    public function listProducts(string $order = 'id', string $sort = 'desc', array $columns = ['*']);

    /**
     * @param int $id
     * @return mixed
     */
    public function findProductById(int $id);
    
    /**
     * @param array $params
     * @return mixed
     */
    public function createProduct(array $params);
    
    /**
     * @param array $params
     * @return mixed
     */
    public function updateProduct(array $params);
    
    /**
     * @param $id
     * @return bool
     */
    public function deleteProduct($id);
    

 }