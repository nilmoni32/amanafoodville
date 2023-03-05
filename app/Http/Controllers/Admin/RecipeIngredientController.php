<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Recipe;
use App\Models\RecipeIngredient;
use App\Models\Product;
use App\Models\Ingredient;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Validator;
use App\Traits\FlashMessages; 


class RecipeIngredientController extends BaseController
{
    use FlashMessages;

    public function index($id){      
        //getting the recipe        
        $recipe = Recipe::find($id);
        //listing all the ingredients for the current recipe.       
        $recipeIngredients =  RecipeIngredient::orderBy('created_at', 'DESC')->where('recipe_id', $recipe->id)->get(); 
        // Attaching pagetitle and subtitle to view.
        view()->share(['pageTitle' => 'Recipe '.$recipe->product->name, 'subTitle' => 'List of all Ingredients']);
        return view('admin.recipes.ingredients.index', compact('recipeIngredients', 'recipe')); 
     }

     public function create($id){
        //getting the recipe 
        $recipe = Recipe::find($id);    
        view()->share(['pageTitle' => 'Recipe '.$recipe->product->name, 'subTitle' => 'Add ingredient for the recipe']); 
        return view('admin.recipes.ingredients.create', compact('recipe'));     
     }

     public function store(Request $request){

        $validated = $request->validate([
            'ingredient_name' => 'required|numeric', 
            'quantity'  => 'required|numeric',
            'measurement_unit' => 'required|string', 
        ]);  
        
        //getting the ingredient details using ingredient_id.     
        $ingredient = Ingredient::find($request->ingredient_name);
        $unit_price = $ingredient->smallest_unit_price;
        $ingredient_total_cost =  $request->quantity * $unit_price;     
        
     
        //checking whether the ingredient is already added to the Recipe or not.
        $recipeIngredient = RecipeIngredient::where('ingredient_id', $request->ingredient_name)->where('recipe_id', $request->recipe_id)->first();
        
        if(!is_null($recipeIngredient)){
            return $this->responseRedirectBack(' This ingredient is already added to the Recipe.' ,'error', false, false); 
        }else{
            // if the ingredient is not added to the recipe.
            $recipeIngredient= RecipeIngredient::create([
                'recipe_id' => $request->recipe_id,
                'ingredient_id' => $request->ingredient_name, // actually it conatains the ingredient id.
                'quantity' => $request->quantity,
                'unit_price' =>   $unit_price, 
                'measure_unit' =>   $request->measurement_unit,
                'ingredient_total_cost' => $ingredient_total_cost            
            ]);
    
            //getting the recipe.
            $recipe = Recipe::find($request->recipe_id);
            $recipe->production_food_cost += $recipeIngredient->ingredient_total_cost; 
            $recipe->save();                
    
            if($recipeIngredient){          
    
                // setting flash message using trait
                $this->setFlashMessage(' Recipe ingredient is added successfully', 'success');    
                $this->showFlashMessages();
                return redirect()->route('admin.recipe.ingredient.index', $recipe->id);
    
            }else{
                return $this->responseRedirectBack(' Error occurred while adding an ingredient .' ,'error', false, false);    
            }

        }

        
     }

     public function edit($id){
        $recipeIngredient = RecipeIngredient::find($id); 
        $recipe = Recipe::find($recipeIngredient->recipe_id);
        view()->share(['pageTitle' => 'Recipe '. $recipe->product->name, 'subTitle' => 'Edit ingredient :' . $recipeIngredient->ingredient->name ]); 
        return view('admin.recipes.ingredients.edit', compact('recipeIngredient', 'recipe'));
     }

     public function update(Request $request){

        $validated = $request->validate([
            'ingredient_name' => 'required|numeric', 
            'quantity'  => 'required|numeric',
            'measurement_unit' => 'required|string', 
        ]); 
        
        //getting the recipe.
        $recipe = Recipe::find($request->recipe_id);
        //getting the recipe ingredient.        
        $recipeIngredient= RecipeIngredient::find($request->recipeIngredient_id);        
        //Before updating the ingredient we will deduct ingredient total cost from recipe production food cost.
        $recipe->production_food_cost -= $recipeIngredient->ingredient_total_cost;
        
        //getting the ingredient details using ingredient_id.     
        $ingredient = Ingredient::find($request->ingredient_name); // actually it is ingredient id.
        $unit_price = $ingredient->smallest_unit_price;
        $ingredient_total_cost =  $request->quantity * $unit_price;

        //now updating recipeIngredient with updated data.  
        $recipeIngredient->ingredient_id = $request->ingredient_name; 
        $recipeIngredient->quantity = $request->quantity;
        $recipeIngredient->unit_price =   $unit_price;
        $recipeIngredient->measure_unit =  $request->measurement_unit;
        $recipeIngredient->ingredient_total_cost = $ingredient_total_cost; 
        $recipeIngredient->save();

        // now updating the recipe production food cost.
        $recipe->production_food_cost += $recipeIngredient->ingredient_total_cost; 
        $recipe->save();                

        if($recipeIngredient){          

            // setting flash message using trait
            $this->setFlashMessage(' Recipe ingredient is updated successfully', 'success');    
            $this->showFlashMessages();
            return redirect()->route('admin.recipe.ingredient.index', $recipe->id);

        }else{
            return $this->responseRedirectBack(' Error occurred while updating the ingredient .' ,'error', false, false);    
        }

     }

     public function delete($id){
        //getting the recipe ingredient. 
        $recipeIngredient = RecipeIngredient::find($id);        
        //getting the recipe. 
        $recipe = Recipe::find($recipeIngredient->recipe_id); 
        //deducting ingredient total cost from production food cost.
        $recipe->production_food_cost -= $recipeIngredient->ingredient_total_cost;

        $recipe->save();
        // Deleting the ingredient purchase record.
        $recipeIngredient->delete();

        if(!$recipeIngredient){
            return  $this->responseRedirectBack(' Error occurred while deleting the ingredient.', 'error', true, true);
        }
        $this->setFlashMessage(' Recipe ingredient record is deleted successfully', 'success');    
        $this->showFlashMessages(); 

        return redirect()->route('admin.recipe.ingredient.index', $recipe->id);

     }

     function getunit(Request $request){
        //finding smallest measurement unit of the corresponding ingredient
        $small_measure_unit = Ingredient::where('id', $request->ingredient_id)->first()->smallest_unit;
        if(!is_null($small_measure_unit)){  
            return json_encode([ 'status' => 'success', 'small_unit' => $small_measure_unit ]); 
        }
     }


}
