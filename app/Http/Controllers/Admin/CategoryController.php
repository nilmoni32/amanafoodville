<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Contracts\CategoryContract;



/**
 * We are using BaseController instead of Controller class, so that we can use our own redirect methods & flash emthods.
 * In this application we are using repository pattern to create a bridge between models and controllers. 
 *  
 * Class CategoryController
 * @package App\Http\Controllers\Admin
 */

class CategoryController extends BaseController
{
    /**
     * @var $categoryRepository
     */
    protected $categoryRepository;   

    /**
     * injected the CategoryContract interface in constructor method.
     * CategoryController constructor
     * @param  CategoryContract $categoryRepository
     */
    public function __construct(CategoryContract $categoryRepository){
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Listing all the categories 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){

        $categories = $this->categoryRepository->listCategories(); // getting all the categories using repository pattern technique.

        $this->setPageTitle('Food Category', 'List of all categories'); // Attaching title and subtitle using BaseController setPageTitle function.
        return view('admin.categories.index', compact('categories'));  // returning the admin.categories.index view with categories
    }   

    /**
     * Create Category
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(){
        $categories = $this->categoryRepository->listCategories('id','asc');

        $this->setPageTitle('Food Category', 'Create Category');   
        return view('admin.categories.create', compact('categories'));
    }

    /**
     * Save the category
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request){

        $this->validate($request,[
            'category_name' => 'required|unique:categories,category_name|max:191',            
            'parent_id'     => 'required|not_in:0',
            'image'         => 'mimes:png,jpeg,jpg|max:1000',
        ]);
        
        $params = $request->except('_token'); // getting all the category form inputs except csrf token.        

        $category = $this->categoryRepository->createCategory($params);    
        
        if(!$category){
           return  $this->responseRedirectBack('Error occurred while creating category.', 'error', true, true);
        }
        return $this->responseRedirect('admin.categories.index', ' Category is added successfully' ,'success', false, false);
    
    }

    /**
     * Generating the Edit Form for particular Category id
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

     public function edit($id){

        $targetCategory = $this->categoryRepository->findCategoryById($id);        
        $categories = $this->categoryRepository->listCategories();

        $this->setPageTitle('Food Category' ,'Edit Category : '.$targetCategory->category_name);        
        return view('admin.categories.edit', compact('categories', 'targetCategory'));
        
     }

     /**
     * Update the category based on provided category-id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request){

        $this->validate($request,[
            'category_name' => 'required|max:191',
            'parent_id'     => 'required|not_in:0',
            'image'         => 'mimes:png,jpeg,jpg|max:1000',
        ]);
        
        $params = $request->except('_token'); // getting all the category form inputs except csrf token.        

        $category = $this->categoryRepository->updateCategory($params);  // called to updateCategory method of CategoryRepository instance 
        
        if(!$category){
           return  $this->responseRedirectBack(' Error occurred while updating category.', 'error', true, true);
        }
        return $this->responseRedirectBack(' Category is updated successfully' ,'success', false, false);    
    }

    /**
     * Delete the category based on given id
     * @param $id
     * @return \Illuminate\Http\RedirectResponse    
     * @throws \Illuminate\Validation\ValidationException 
     */
    public function delete($id){

        $category = $this->categoryRepository->deleteCategory($id); // called to deleteCategory method of CategoryRepository instance

        if(!$category){
            return  $this->responseRedirectBack(' Error occurred while deleting the category.', 'error', true, true);
         }
         return $this->responseRedirect('admin.categories.index',' Category is deleted successfully' ,'success', false, false); 
    }

}
