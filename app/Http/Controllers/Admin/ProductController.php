<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Userlog;
use Illuminate\Http\Request;
use App\Contracts\ProductContract;
use App\Contracts\CategoryContract;
use App\Http\Controllers\BaseController;
use App\Http\Requests\StoreProductFormRequest;

class ProductController extends BaseController
{
    protected $categoryRepository;
    protected $productRepository;

    public function __construct(CategoryContract $categoryRepository, ProductContract $productRepository){

        $this->productRepository =  $productRepository;
        $this->categoryRepository = $categoryRepository;
    }
    
    public function index(){

        $products = $this->productRepository->listProducts(); // getting all the products using repository pattern technique.
        $this->setPageTitle('Food Menu', 'List of Food items'); // Attaching title and subtitle using BaseController setPageTitle function.        
        return view('admin.products.index', compact('products')); // returning the admin.products.index view with products     

    }

    public function create(){
        $categories = $this->categoryRepository->listCategories('category_name','asc'); // fetching all categories name with ascending order.
        $this->setPageTitle('Food Menu', 'Add Food item');
        return view('admin.products.create', compact('categories'));
    }

    
    public function store(Request $request){
        // Instead of using Illuminate\Http\Request class, we are using StoreProductFormRequest class
        // validation logic is done by laravel form request app/Http/Requests

        if($request->discount_price){
            $this->validate($request,[                       
                'name'               =>  'required|unique:products,name|max:191',
                'price'              =>  'required|regex:/^\d+(\.\d{1,2})?$/',
                'discount_price'     =>  'regex:/^\d+(\.\d{1,2})?$/',  
            ]);   
        }  
        else{
            $this->validate($request,[                       
                'name'               =>  'required|unique:products,name|max:191',
                'price'              =>  'required|regex:/^\d+(\.\d{1,2})?$/',                  
            ]);
        }             

        $params = $request->except('_token'); // getting all inputs
        $product = $this->productRepository->createProduct($params);
        
        if (!$product) {
            return $this->responseRedirectBack('Error occurred while creating product.', 'error', true, true);
        }
        return $this->responseRedirect('admin.products.index', ' Product is added successfully' ,'success',false, false);
    }

    public function edit($id){
        // loading the editable product using the findProductById() method of product repository then loadin the edit view.
        $product = $this->productRepository->findProductById($id);
        // loading all categories name
        $categories = $this->categoryRepository->listCategories('category_name', 'asc');

        $this->setPageTitle('Food Menu', ' Edit Food item: '.$product->name);       
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request){

        if($request->discount_price){
            $this->validate($request,[                       
                'name'               =>  'required|max:191',
                'price'              =>  'required|regex:/^\d+(\.\d{1,2})?$/',
                'discount_price'     =>  'regex:/^\d+(\.\d{1,2})?$/',  
            ]);   
        }  
        else{
            $this->validate($request,[                       
                'name'               =>  'required|max:191',
                'price'              =>  'required|regex:/^\d+(\.\d{1,2})?$/',                  
            ]);
        }  
        
        //saving log for the changing product price up & down.  
        $old_price = Product::where('id', $request->product_id)->first()->price;
        $old_discount_price = Product::where('id', $request->product_id)->first()->discount_price;
        $new_price = $request->price;
        $new_discount_price = $request->discount_price;
        $name = $request->name;
        // getting all the inputs
        $params = $request->except('_token'); 
        $product = $this->productRepository->updateProduct($params);
        
        if (!$product) {
            return $this->responseRedirectBack('Error occurred while updating product.', 'error', true, true);
        }
        //saving log for the changing product price up & down.
        if($old_price != $new_price || $old_discount_price != $new_discount_price){
            Userlog::product_price_up_down($name, $request->product_id, $old_price, $new_price, $old_discount_price,$new_discount_price);
        }        

        return $this->responseRedirect('admin.products.index', ' Product updated successfully' ,'success',false, false);
    }

    public function delete($id){

        $product = $this->productRepository->deleteProduct($id);

        if (!$product) {
            return $this->responseRedirectBack('Error occurred while deleting product.', 'error', true, true);
        }
        return $this->responseRedirect('admin.products.index', ' Product deleted successfully' ,'success',false, false);
    }
}
