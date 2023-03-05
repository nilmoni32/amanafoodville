<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Recipe;
use App\Models\Product;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Validator;
use App\Traits\FlashMessages; 

class RecipeController extends BaseController
{
    use FlashMessages;

    public function index(){
       // Attaching pagetitle and subtitle to view.
       view()->share(['pageTitle' => 'Funville Recipes', 'subTitle' => 'List of all Recipes']);
       $recipes = Recipe::orderBy('created_at', 'desc')->get();        
       return view('admin.recipes.index', compact('recipes')); 
    }

    public function create(){ 
        // Attaching pagetitle and subtitle to view.
        view()->share(['pageTitle' => 'Funville Recipes', 'subTitle' => 'Add Food Recipe' ]);
        $products = Product::all();       
        return view('admin.recipes.create',compact('products')); 
    }

    public function store(Request $request){

        $validated = $request->validate([
            'recipe' => 'required|numeric',                  
        ]);

        //checking the product whether it is already added to the Recipe.
        $recipe = Recipe::where('product_id', $request->recipe)->first();
        if(!is_null($recipe)){
            //return json_encode([ 'status' => 'info', 'message' => ""  ]);
            return $this->responseRedirectBack(' This food is already added to the Recipe table.' ,'error', false, false); 
        }else{
            // if this product is not added to the recipe table
            $recipe= Recipe::create([
                'product_id' => $request->recipe,
                'production_food_cost'=> 0.0,       
            ]);
            if($recipe){  
                // setting flash message using trait
                $this->setFlashMessage(' Recipe is added successfully', 'success');    
                $this->showFlashMessages();            
                return redirect()->route('admin.recipe.index'); 
            }else{
                return $this->responseRedirectBack(' Error occurred while adding recipe .' ,'error', false, false);    
            }

        }

    }

    public function edit($id){
        $recipe = Recipe::find($id);    
        $products = Product::all();     
        view()->share(['pageTitle' => 'Funville Recipes', 'subTitle' => 'Edit the recipe: ' .$recipe->product->name]);  
        return view('admin.recipes.edit', compact('recipe', 'products'));
    }

    public function update(Request $request){
        $validated = $request->validate([
            'recipe' => 'required|numeric',                  
        ]);

        // Getting the Recipe record
        $recipe= Recipe::find($request->recipe_id);
        $recipe->product_id = $request->recipe;
        $recipe->save();

        if($recipe){  
            // setting flash message using trait
            $this->setFlashMessage(' Recipe Name is updated successfully', 'success');    
            $this->showFlashMessages();            
            return redirect()->route('admin.recipe.index'); 
        }else{
            return $this->responseRedirectBack(' Error occurred while updating recipe .' ,'error', false, false);    
        }

    }

    public function delete($id){

        $recipe = Recipe::find($id);      
        $recipe->delete();
        if(!$recipe){
            return  $this->responseRedirectBack(' Error occurred while deleting the recipe.', 'error', true, true);
         }
        $this->setFlashMessage(' Recipe is deleted successfully', 'success');    
        $this->showFlashMessages();
        return redirect()->route('admin.recipe.index');
    }
    

}
