<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\FlashMessages; 
use App\Models\Typeingredient;
use App\Http\Controllers\BaseController;

class IngredientTypesController extends BaseController
{
    use FlashMessages;

    /**
     * Listing all the Ingredient categories      
     */
    public function index(){
        // Attaching pagetitle and subtitle to view.
        view()->share(['pageTitle' => 'Ingredient Types', 'subTitle' => 'List of all ingredient types' ]);
        $ingredienttypes = Typeingredient::all();
        return view('admin.ingredient_types.index', compact('ingredienttypes'));  // returning the admin.categories.index view with categories
    }

    /**
     * Create Category     
     */
    public function create(){
        // Attaching pagetitle and subtitle to view.
        view()->share(['pageTitle' => 'Ingredient Types', 'subTitle' => 'Create ingredient types' ]);
        $ingredienttypes = Typeingredient::all();
        return view('admin.ingredient_types.create', compact('ingredienttypes'));  // returning the admin.categories.index view with categories
    }

    /**
     * Save the category
     * @param Request $request  
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request){
        $this->validate($request,[
            'name' => 'required|unique:typeingredients,name|max:191',            
            'parent_id'     => 'required|not_in:0'            
        ]);

        $ingredienttype= Typeingredient::create([
            'name' => $request->name,
            'description' => $request->description,
            'parent_id' =>   $request->parent_id              
        ]);
        
        if($ingredienttype){           

            // setting flash message using trait
            $this->setFlashMessage(' Ingredient type is created successfully', 'success');    
            $this->showFlashMessages(); 

            // Attaching pagetitle and subtitle to view.
            view()->share(['pageTitle' => 'Ingredient Types', 'subTitle' => 'List of all ingredient types' ]); 

            // $ingredienttypes = Typeingredient::all();
            // return view('admin.ingredient_types.index', compact('ingredienttypes')); 
            return redirect()->route('admin.ingredienttypes.index');
            
        }else{
            return $this->responseRedirectBack(' Error occurred while creating category.' ,'error', false, false);    
        }

    }

     /**
     * Generating the Edit Form for particular Category id
     * @param $id     
     */
    public function edit($id){
        $targetCategory = Typeingredient::find($id);
        view()->share(['pageTitle' => 'Ingredient Types', 'subTitle' => 'Ingredient Types: ' .$targetCategory->name]);               
        $ingredienttypes = Typeingredient::all();
        return view('admin.ingredient_types.edit', compact('ingredienttypes', 'targetCategory'));   
    }

    /**
     * Update the category based on provided category-id
     * @param Request $request   
     * @throws \Illuminate\Validation\ValidationException
     */

    public function update(Request $request){

        $this->validate($request,[
            'name' => 'required|string|max:191',            
            'parent_id'     => 'required|not_in:0'            
        ]);

        $ingredienttype = Typeingredient::find($request->id);
        $ingredienttype->name = $request->name;
        $ingredienttype->description = $request->description;
        $ingredienttype->parent_id = $request->parent_id;
        $ingredienttype->save();

        // setting flash message using trait
        $this->setFlashMessage(' Ingredient type is updated successfully', 'success');    
        $this->showFlashMessages(); 

        // Attaching pagetitle and subtitle to view.
        view()->share(['pageTitle' => 'Ingredient Types', 'subTitle' => 'List of all ingredient types' ]); 

        // $ingredienttypes = Typeingredient::all();
        // return view('admin.ingredient_types.index', compact('ingredienttypes')); 
        return redirect()->route('admin.ingredienttypes.index');
    }

     /**
     * Delete the category based on given id
     * @param $id
     * @return \Illuminate\Http\RedirectResponse    
     * @throws \Illuminate\Validation\ValidationException 
     */
    public function delete($id){
        $ingredienttype = Typeingredient::find($id);
        $ingredienttype->delete();

        if(!$ingredienttype){
            return  $this->responseRedirectBack(' Error occurred while deleting the category.', 'error', true, true);
         }
        $this->setFlashMessage(' Ingredient type is deleted successfully', 'success');    
        $this->showFlashMessages();
        return redirect()->route('admin.ingredienttypes.index');
    }
}
