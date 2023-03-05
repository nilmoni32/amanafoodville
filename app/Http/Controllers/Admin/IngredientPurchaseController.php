<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Unit;
use App\Models\Recipe;
use App\Models\Userlog;
use App\Models\Ingredient; 
use Illuminate\Http\Request;
use App\Traits\FlashMessages;
use App\Models\RecipeIngredient;
use App\Models\IngredientPurchase;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController;

class IngredientPurchaseController extends BaseController
{
    use FlashMessages;

    /**
     * Listing all purchases of an ingredient    
     */
    public function index($id){
        //getting the ingredient        
        $ingredient = Ingredient::find($id);
        //listing all the purchaes for the current ingredient.       
        $purchases =  IngredientPurchase::orderBy('created_at', 'DESC')->where('ingredient_id', $ingredient->id)->take(200)->get(); 
        // Attaching pagetitle and subtitle to view.
        view()->share(['pageTitle' => 'Ingredient Purchase', 'subTitle' => 'Ingredient all purchases list' ]);
                
        return view('admin.ingredients.purchases.index', compact('purchases', 'ingredient'));  
        
    }

    public function create($id){
        //getting the ingredient        
        $ingredient = Ingredient::find($id);
        view()->share(['pageTitle' => 'Ingredient Purchase', 'subTitle' => 'Add purchase entry for the ingredient' ]);                
        return view('admin.ingredients.purchases.create', compact('ingredient'));         
    }

    /**
     * Save the ingredient
     * @param Request $request  
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request){
        
        $validated = $request->validate([
            'name' => 'required|string|max:191', 
            'quantity'  => 'required|numeric',
            'unit' => 'required|string', 
            'price'=>  'required|regex:/^\d+(\.\d{1,2})?$/',            
        ]);        
        //coverting date format from m-d-Y to Y-m-d as database stores date in 'Y-m-d' format
        $purchase_date = Carbon::createFromFormat('d-m-Y', $request->purchase_date)->format('Y-m-d');
        $expire_date = Carbon::createFromFormat('d-m-Y', $request->expire_date)->format('Y-m-d');

        $ingredientPurchase= IngredientPurchase::create([
            'name' => $request->name,
            'ingredient_id' => $request->ingredient_id,
            'quantity' => $request->quantity,
            'price' =>   $request->price, 
            'unit' =>   $request->unit,
            'purchase_date' =>   $purchase_date,
            'expire_date' => $expire_date,
            'added_by' => auth()->user()->name,
        ]);
      

        //getting the ingredient details       
        $ingredient = Ingredient::find($request->ingredient_id);
        // Setting ingredient total quantity, total price, per unit cost price 
        $ingredient_total_quantity = $ingredient->total_quantity;
        $ingredient_total_price = $ingredient->total_price;
        $ingredient_smallest_unit_price = $ingredient->smallest_unit_price;

        $unit_conversion = 0;
        // now calculating stock ingredient quantity & unit conversion.
        if($ingredient->measurement_unit == $ingredientPurchase->unit){
            $unit = Unit::where('measurement_unit', $ingredientPurchase->unit)->first();            
            $unit_conversion = $unit->unit_conversion;
            $ingredient_total_quantity += $ingredientPurchase->quantity; 
        }else{
            $unit = Unit::where('smallest_measurement_unit', $ingredientPurchase->unit)->first();            
            $unit_conversion = $unit->unit_conversion; 
            $ingredient_total_quantity += ($ingredientPurchase->quantity/$unit_conversion); 
        }  

        //when ingredient total qty is negative i.e more sales results ingredient qty become negative in the stock.
        if($ingredient->total_quantity < 0){ 

	     // calculating ingredient smallest unit price when $ingredient->total_price & $ingredient->total_quantity both are negative.
            if($ingredient->total_price < 0){
                if($ingredient->measurement_unit == $ingredientPurchase->unit){ 
                    $negative_ingredient_total_quantity = $ingredient->total_quantity - $ingredientPurchase->quantity;
                }else{
                    $negative_ingredient_total_quantity = $ingredient->total_quantity - ($ingredientPurchase->quantity/$unit_conversion);
                }                                
                $negative_ingredient_total_price = $ingredient->total_price - $ingredientPurchase->price;
                $ingredient_smallest_unit_price = abs($negative_ingredient_total_price/($negative_ingredient_total_quantity * $unit_conversion));
            } 
                       
            // when $ingredient->total_price = 0 after sales, considering the old $ingredient_smallest_unit_price  
             //now calculating stock ingredient total price.
            $ingredient_total_price = ($ingredient_total_quantity * $unit_conversion)* $ingredient_smallest_unit_price;            
        }else{            
            //calculating total ingredient price 
            $ingredient_total_price += $ingredientPurchase->price; 
            //calculating ingredient unit price
            $ingredient_smallest_unit_price = $ingredient_total_price/($ingredient_total_quantity * $unit_conversion);
        }  
      
        // updating the stock ingredient total quatity, total price and unit price
        $ingredient->total_quantity = $ingredient_total_quantity;        
        $ingredient->total_price = $ingredient_total_price;
        $ingredient->smallest_unit_price = $ingredient_smallest_unit_price;
        $ingredient->save();  
        
        // updating the ingredient unit price to recipe ingredients of the corresponding recipe.
        $recipeIngredients =  RecipeIngredient::where('ingredient_id', $request->ingredient_id)->get(); 
        foreach($recipeIngredients as $recipeIngredient){
            //updating the ingredient unit price and its total cost for the recipe.
            $recipeIngredient->ingredient_total_cost = $ingredient_smallest_unit_price * $recipeIngredient->quantity;
            $recipeIngredient->unit_price = $ingredient_smallest_unit_price;
            $recipeIngredient->save();
        } 
        
        // updating the product recipe cost.
        foreach($recipeIngredients as $recipeIngredient){
            
            $recipe_cost = 0;
            //getting the recipe
            $recipe = Recipe::find($recipeIngredient->recipe_id);
            //getting all the recipe ingredients.
            $recipeingredients = $recipe->recipeingredients;
            foreach($recipeingredients as $recipeingredient){
                $recipe_cost += $recipeingredient->ingredient_total_cost;
            }
            $recipe->production_food_cost = $recipe_cost;
            $recipe->save();
        }

        if($ingredientPurchase){           

            // setting flash message using trait
            $this->setFlashMessage(' Purchase item is added successfully', 'success');    
            $this->showFlashMessages(); 

            // Attaching pagetitle and subtitle to view.
            view()->share(['pageTitle' => 'Ingredient Purchase', 'subTitle' => 'Ingredient all purchases list' ]); 

            // $purchases =  IngredientPurchase::orderBy('created_at', 'DESC')->where('ingredient_id', $request->ingredient_id)->take(200)->get();
            // return view('admin.ingredients.purchases.index', compact('purchases', 'ingredient')); 
            return redirect()->route('admin.ingredient.purchase.index', $ingredient->id);

        }else{
            return $this->responseRedirectBack(' Error occurred while adding an ingredient .' ,'error', false, false);    
        }

    }

    public function edit($id){
        $purchase = IngredientPurchase::find($id); 
        view()->share(['pageTitle' => 'Ingredients', 'subTitle' => 'Edit the purchase ingredient: ' . $purchase->name ]);
        $ingredient = Ingredient::find($purchase->ingredient_id); 
        return view('admin.ingredients.purchases.edit', compact('purchase', 'ingredient'));
    }

    public function update(Request $request){

        $validated = $request->validate([
            'name' => 'required|string|max:191', 
            'quantity'  => 'required|numeric',
            'unit' => 'required|string', 
            'price'=>  'required|regex:/^\d+(\.\d{1,2})?$/',            
        ]);
        
        //coverting date format from m-d-Y to Y-m-d as database stroes date in 'Y-m-d' format
        $purchase_date = Carbon::createFromFormat('d-m-Y', $request->purchase_date)->format('Y-m-d');
        $expire_date = Carbon::createFromFormat('d-m-Y', $request->expire_date)->format('Y-m-d');      

        //Getting the ingredient purchase details.
        $ingredientPurchase = IngredientPurchase::find($request->purchase_id);
        //getting the ingredient details       
        $ingredient = Ingredient::find($ingredientPurchase->ingredient_id);       
        $ingredient_total_quantity = $ingredient->total_quantity;
        $ingredient_total_price = $ingredient->total_price;
        $ingredient_smallest_unit_price = $ingredient->smallest_unit_price;

        //dd($ingredient_total_quantity);
        //saving log for the changing ingredient purchase price up & down. 
        $old_price = $ingredientPurchase->price;
        $new_price = $request->price;
        $name = $request->name;        
        //saving log for the changing ingredient purchase price up & down.
        if($old_price != $new_price ){            
            Userlog::Ingredient_purchase_price_up_down($name, $request->purchase_id, $old_price, $new_price);
        } 
        
        //Substracting ingredient price before adding the new ingredient_purchase price
        $ingredient_total_price -= $ingredientPurchase->price;  

        //Subtracting ingredient quantity before adding the new quantity
        if($ingredient->measurement_unit == $ingredientPurchase->unit){
            $ingredient_total_quantity -= $ingredientPurchase->quantity;
        }else{
            // getting unit conversion value 
            $unit = Unit::where('smallest_measurement_unit', $ingredientPurchase->unit)->first();            
            $unit_conversion = $unit->unit_conversion; 
            $ingredient_total_quantity -= ($ingredientPurchase->quantity/$unit_conversion); 
        }
        //dd($ingredient_total_quantity);
        //now updating the ingredient_purchase details
        $ingredientPurchase->name = $request->name;
        $ingredientPurchase->quantity = $request->quantity;
        $ingredientPurchase->price = $request->price;
        $ingredientPurchase->unit = $request->unit;
        $ingredientPurchase->purchase_date =  $purchase_date;
        $ingredientPurchase->expire_date = $expire_date;
        $ingredientPurchase->save();
        
        //when ingredient total qty is negative i.e more sales results ingredient qty become negative in the stock.
        if($ingredient_total_quantity < 0){            
                        
             $unit_conversion =0;
            // calculating unit version 
            if($ingredient->measurement_unit == $ingredientPurchase->unit){
                $unit = Unit::where('measurement_unit', $ingredientPurchase->unit)->first();            
                $unit_conversion = $unit->unit_conversion;                
            }else{
                $unit = Unit::where('smallest_measurement_unit', $ingredientPurchase->unit)->first();            
                $unit_conversion = $unit->unit_conversion;                
            }  
            
            // calculating ingredient smallest unit price when $ingredient->total_price & $ingredient->total_quantity both are negative.
            if($ingredient_total_price < 0){
                if($ingredient->measurement_unit == $ingredientPurchase->unit){ 
                    $negative_ingredient_total_quantity = $ingredient_total_quantity - $ingredientPurchase->quantity;
                }else{
                    $negative_ingredient_total_quantity = $ingredient_total_quantity - ($ingredientPurchase->quantity/$unit_conversion);
                }                                
                $negative_ingredient_total_price = $ingredient_total_price - $ingredientPurchase->price;
                $ingredient_smallest_unit_price = abs($negative_ingredient_total_price/($negative_ingredient_total_quantity * $unit_conversion));
            }

            // now calculating stock ingredient quantity 
            if($ingredient->measurement_unit == $ingredientPurchase->unit){                
                $ingredient_total_quantity += $ingredientPurchase->quantity; 
            }else{
                $ingredient_total_quantity += ($ingredientPurchase->quantity/$unit_conversion); 
            }             
   
             //now calculating stock ingredient total price.        
            $ingredient_total_price = ($ingredient_total_quantity * $unit_conversion)* $ingredient_smallest_unit_price;
        }else{            
            //calculating total ingredient price 
            $ingredient_total_price += $ingredientPurchase->price; 

            // now calculating stock ingredient quantity & unit conversion.
            if($ingredient->measurement_unit == $ingredientPurchase->unit){
                $unit = Unit::where('measurement_unit', $ingredientPurchase->unit)->first();            
                $unit_conversion = $unit->unit_conversion;
                $ingredient_total_quantity += $ingredientPurchase->quantity; 
                //calculating ingredient unit price
                $ingredient_smallest_unit_price = $ingredient_total_price/($ingredient_total_quantity * $unit_conversion);
            }else{
                $unit = Unit::where('smallest_measurement_unit', $ingredientPurchase->unit)->first();            
                $unit_conversion = $unit->unit_conversion; 
                $ingredient_total_quantity += ($ingredientPurchase->quantity/$unit_conversion); 
                //calculating ingredient unit price
                 $ingredient_smallest_unit_price = $ingredient_total_price/($ingredient_total_quantity * $unit_conversion);
            }
            
        }        
            
        // updating the stock ingredient total quatity, total price and unit price
        $ingredient->total_quantity = $ingredient_total_quantity;        
        $ingredient->total_price = $ingredient_total_price;
        $ingredient->smallest_unit_price = $ingredient_smallest_unit_price;
        $ingredient->save(); 

        // updating the ingredient unit price to recipe ingredients of the corresponding recipe.
        $recipeIngredients =  RecipeIngredient::where('ingredient_id', $ingredientPurchase->ingredient_id)->get(); 
        foreach($recipeIngredients as $recipeIngredient){
            //updating the ingredient unit price and its total cost for the recipe.
            $recipeIngredient->ingredient_total_cost = $ingredient_smallest_unit_price * $recipeIngredient->quantity;
            $recipeIngredient->unit_price = $ingredient_smallest_unit_price;
            $recipeIngredient->save();
        }

        // updating the product recipe cost.
        foreach($recipeIngredients as $recipeIngredient){
            
            $recipe_cost = 0;
            //getting the recipe
            $recipe = Recipe::find($recipeIngredient->recipe_id);
            //getting all the recipe ingredients.
            $recipeingredients = $recipe->recipeingredients;
            foreach($recipeingredients as $recipeingredient){
                $recipe_cost += $recipeingredient->ingredient_total_cost;
            }
            $recipe->production_food_cost = $recipe_cost;
            $recipe->save();
        }

        if($ingredientPurchase){           

            // setting flash message using trait
            $this->setFlashMessage(' Purchase item is updated successfully', 'success');    
            $this->showFlashMessages(); 

            // Attaching pagetitle and subtitle to view.
            view()->share(['pageTitle' => 'Ingredient Purchase', 'subTitle' => 'Ingredient all purchases list' ]); 

            // $purchases =  IngredientPurchase::orderBy('created_at', 'DESC')->where('ingredient_id', $ingredientPurchase->ingredient_id)->take(200)->get(); 
            // return view('admin.ingredients.purchases.index', compact('purchases', 'ingredient'));  
            return redirect()->route('admin.ingredient.purchase.index', $ingredient->id);
        }else{
            return $this->responseRedirectBack(' Error occurred while updating the ingredient purchase .' ,'error', false, false);    
        }

    }

    /**
     *  Before deleting the purchase record, we need to substract the total quantity and total price from ingredient    stock. and also recalculate the per unit cost.
     */
    public function delete($id){
        $ingredientPurchase = IngredientPurchase::find($id);
        //getting the ingredient details       
        $ingredient = Ingredient::find($ingredientPurchase->ingredient_id);       
        $ingredient_total_quantity = $ingredient->total_quantity;
        $ingredient_total_price = $ingredient->total_price;

        //Substracting ingredient price before deleting the new ingredient_purchase price
        $ingredient_total_price -= $ingredientPurchase->price;  
        //checking the ingredient is only ingredient then total price of the stock item will be equal to ingredient_purchase price
        if($ingredient_total_price){
             //Subtracting ingredient quantity before deleting the new quantity and Recalculating per unit cost
            if($ingredient->measurement_unit == $ingredientPurchase->unit){
                // getting unit conversion value 
                $unit = Unit::where('measurement_unit', $ingredientPurchase->unit)->first();            
                $unit_conversion = $unit->unit_conversion;
                $ingredient_total_quantity -= $ingredientPurchase->quantity;
                $ingredient_smallest_unit_price = $ingredient_total_price/($ingredient_total_quantity * $unit_conversion);
            }else{
                // getting unit conversion value 
                $unit = Unit::where('smallest_measurement_unit', $ingredientPurchase->unit)->first();            
                $unit_conversion = $unit->unit_conversion; 
                $ingredient_total_quantity -= ($ingredientPurchase->quantity/$unit_conversion); 
                $ingredient_smallest_unit_price = $ingredient_total_price/($ingredient_total_quantity * $unit_conversion);
            }
    
            // updating the ingredient total quatity, total price and unit price
            $ingredient->total_quantity = $ingredient_total_quantity;        
            $ingredient->total_price = $ingredient_total_price;
            $ingredient->smallest_unit_price = $ingredient_smallest_unit_price;
            $ingredient->save();
        }

        
        // Deleting the ingredient purchase record.
        $ingredientPurchase->delete();

        if(!$ingredientPurchase){
            return  $this->responseRedirectBack(' Error occurred while deleting the category.', 'error', true, true);
        }
        $this->setFlashMessage(' Ingredient purchase record is deleted successfully', 'success');    
        $this->showFlashMessages();
        // Attaching pagetitle and subtitle to view.
        view()->share(['pageTitle' => 'Ingredient Purchase', 'subTitle' => 'Ingredient all purchases list' ]); 
        // $purchases =  IngredientPurchase::orderBy('created_at', 'DESC')->where('ingredient_id', $ingredient->id)->take(200)->get();
        // return view('admin.ingredients.purchases.index', compact('purchases', 'ingredient'));   
        return redirect()->route('admin.ingredient.purchase.index', $ingredient->id);   

    }

  
}
