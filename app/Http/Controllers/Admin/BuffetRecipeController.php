<?php

namespace App\Http\Controllers\Admin;

use App\Models\Unit;
use App\Models\Buffet;
use App\Models\Recipe;
use App\Models\RecipeIngredient;
use App\Models\Ingredient;
use App\Models\BuffetRecipe;
use Illuminate\Http\Request;
use App\Traits\FlashMessages;
use App\Models\SupplierStock;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController;
      
class BuffetRecipeController extends BaseController
{
    use FlashMessages;

    public function index($id){                  
        $buffet_foods =  BuffetRecipe::orderBy('created_at', 'DESC')->where('buffet_id', $id)->get(); 
        $buffet = Buffet::find($id);
        // Attaching pagetitle and subtitle to view.        
        view()->share(['pageTitle' => 'Buffets- '.$buffet->buffet_name, 'subTitle' => 'List of all foods']);
        return view('admin.buffets.recipes.index', compact('buffet_foods', 'buffet'));        
    }

    public function create($id){
        //getting the recipe 
        $buffet = Buffet::find($id);      
        view()->share(['pageTitle' => 'Buffets', 'subTitle' => 'Add Food to the buffet']); 
        return view('admin.buffets.recipes.create', compact('buffet'));     
    }

    public function store(Request $request){  
        
        $validated = $request->validate([
            'recipe_id' => 'required|numeric',                  
        ]);
        //dd($request->all());
        //checking the food whether it is already added to the Recipe.        
        $buffet_recipe = BuffetRecipe::where('recipe_id', $request->recipe_id)->where('buffet_id', $request->buffet_id)->first();
        if(!is_null($buffet_recipe)){
            //return json_encode([ 'status' => 'info', 'message' => ""  ]);
            return $this->responseRedirectBack(' This food is already added to the Recipe table.' ,'error', false, false); 
        }else{
           
           $recipe = Recipe::where('id', $request->recipe_id)->first();
            // if this food is not added, we will create it
           $buffet_recipe= BuffetRecipe::create([
                'recipe_id'             => $request->recipe_id,
                'buffet_id'             => $request->buffet_id, // actually it conatains the ingredient id.
                'recipe_cost_price'     => $recipe->production_food_cost,
                'recipe_sale_price'     => $recipe->product->price, 
            ]);  
            
            //getting the buffet price.
            $buffet = Buffet::find($request->buffet_id);
            $buffet->unit_cost_price += $recipe->production_food_cost; 
            $buffet->save();
            
            if($buffet_recipe){  
                // setting flash message using trait
                $this->setFlashMessage(' Recipe is added successfully', 'success');    
                $this->showFlashMessages();            
                return redirect()->route('admin.buffet.recipe.index', $request->buffet_id); 
            }else{
                return $this->responseRedirectBack(' Error occurred while adding recipe .' ,'error', false, false);    
            }

        }

    }

    public function edit($id){        
        $buffet_recipe = BuffetRecipe::find($id);
        $buffet = Buffet::find($buffet_recipe->buffet_id);
        // Attaching pagetitle and subtitle to view.        
        view()->share(['pageTitle' => 'Buffets- '.$buffet->buffet_name, 'subTitle' => 'Change Buffet Food :' . $buffet_recipe->recipe->product->name ]);
        return view('admin.buffets.recipes.edit', compact('buffet_recipe', 'buffet'));   
    }

    public function update(Request $request){
        $validated = $request->validate([
            'recipe_id' => 'required|numeric',             
        ]);
        //getting the buffet.
        $buffet = Buffet::find($request->buffet_id);
        //getting the buffet recipe.        
        $buffet_recipe = BuffetRecipe::find($request->buffet_recipe_id);        
        //Before updating the food  we will deduct food cost from buffet unit cost price.
        $buffet->unit_cost_price -= $buffet_recipe->recipe_cost_price;
        //getting the latest food name seleted by the user
        $recipe = Recipe::where('id', $request->recipe_id)->first();
        //now updating buffetRecipe with updated data.  
        $buffet_recipe->buffet_id         = $request->buffet_id; 
        $buffet_recipe->recipe_id         = $request->recipe_id;
        $buffet_recipe->recipe_cost_price = $recipe->production_food_cost;
        $buffet_recipe->recipe_sale_price = $recipe->product->price;
        $buffet_recipe->save();

        // now updating the buffet production food cost.        
        $buffet->unit_cost_price += $recipe->production_food_cost; 
        $buffet->save();               

        if($buffet_recipe){          

            // setting flash message using trait
            $this->setFlashMessage(' Buffet food is updated successfully', 'success');    
            $this->showFlashMessages();
            return redirect()->route('admin.buffet.recipe.index', $buffet->id);            

        }else{
            return $this->responseRedirectBack(' Error occurred while updating the ingredient .' ,'error', false, false);    
        }

    }


    public function delete($id){
        //getting the buffet recipe. 
        $buffet_recipe = BuffetRecipe::find($id);        
        //getting the buffet. 
        $buffet = Buffet::find($buffet_recipe->buffet_id); 
        //Before updating the food  we will deduct food cost from buffet unit cost price.
        $buffet->unit_cost_price -= $buffet_recipe->recipe_cost_price;

        $buffet->save();
        // Deleting buffet specific food record.
        $buffet_recipe->delete();

        if(!$buffet_recipe){
            return  $this->responseRedirectBack(' Error occurred while deleting the buffet food item.', 'error', true, true);
        }
        $this->setFlashMessage(' Buffet food record is deleted successfully', 'success');    
        $this->showFlashMessages(); 
        
        return redirect()->route('admin.buffet.recipe.index', $buffet->id);  

    }

}

