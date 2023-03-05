<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Traits\FlashMessages;
use App\Models\Ingredient; 
use App\Models\IngredientDamage;
use Carbon\Carbon;
use App\Models\Unit;

class IngredientDamageController extends BaseController
{
    use FlashMessages;

    /**
     * Listing of all damage ingredients    
     */
    public function index($id){        
        //getting the ingredient        
        $ingredient = Ingredient::find($id);
        //listing all the purchaes for the current ingredient.       
        $damages =  IngredientDamage::orderBy('created_at', 'DESC')->where('ingredient_id', $ingredient->id)->take(200)->get(); 
        // Attaching pagetitle and subtitle to view.
        view()->share(['pageTitle' => 'Ingredient Damage', 'subTitle' => 'Ingredient all Damages list' ]);
        return view('admin.ingredients.damages.index', compact('damages', 'ingredient'));          
    }

    public function create($id){
        //getting the ingredient        
        $ingredient = Ingredient::find($id);
        view()->share(['pageTitle' => 'Ingredient Damage', 'subTitle' => 'Add damage entry for the ingredient' ]);               
        return view('admin.ingredients.damages.create', compact('ingredient'));   
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
        ]);        
        //coverting date format from m-d-Y to Y-m-d as database stroes date in 'Y-m-d' format
        $reported_date = Carbon::createFromFormat('d-m-Y', $request->reported_date)->format('Y-m-d');

        //getting the ingredient details from the ingredient stock.     
        $ingredient = Ingredient::find($request->ingredient_id);
        // Setting ingredient total quantity, total price, per unit cost price 
        $ingredient_total_quantity = $ingredient->total_quantity;
        $ingredient_total_price = $ingredient->total_price;
        $ingredient_smallest_unit_price = $ingredient->smallest_unit_price;
        $damage_price = 0;
        //calculating damage ingredient price from ingredient quantity and unit.           

        //subtracting ingredient quantity
        if($ingredient->measurement_unit == $request->unit){
            // if damage unit is stock unit, then we directly substract from ingredient quantity.                      
            $ingredient_total_quantity -= $request->quantity; 
            // getting unit conversion value 
            $unit = Unit::where('measurement_unit', $request->unit)->first();            
            $unit_conversion = $unit->unit_conversion; 
            $damage_price = $ingredient_smallest_unit_price * ($request->quantity * $unit_conversion);
        }elseif($ingredient->smallest_unit == $request->unit){
            // calculating damage product price 
            $damage_price = $ingredient_smallest_unit_price * $request->quantity;      
            // getting unit conversion value 
            $unit = Unit::where('smallest_measurement_unit', $request->unit)->first();            
            $unit_conversion = $unit->unit_conversion; 
            //converting gm to kg, if damage unit is smallest unit and then substract from ingredient quantity.
            $ingredient_total_quantity -= ($request->quantity/$unit_conversion);                  
        } 

        $ingredient_total_price -= $damage_price;

        // updating the ingredient total quatity, total price.
        $ingredient->total_quantity = $ingredient_total_quantity;        
        $ingredient->total_price = $ingredient_total_price;
        $ingredient->save();

        // now we have damage product price and we will store the damage ingredient record 
        $ingredientDamage= IngredientDamage::create([
            'name' => $request->name,
            'ingredient_id' => $request->ingredient_id,
            'quantity' => $request->quantity,
            'price' =>   $damage_price, 
            'unit' =>   $request->unit,
            'reported_date' =>   $reported_date,          
            'reported_by' => auth()->user()->name,
        ]);

        if($ingredientDamage){           

            // setting flash message using trait
            $this->setFlashMessage(' Damage ingredient is added successfully', 'success');    
            $this->showFlashMessages(); 

            // Attaching pagetitle and subtitle to view.
            view()->share(['pageTitle' => 'Ingredient Damage', 'subTitle' => 'Ingredient all damages list' ]); 

            // $damages =  IngredientDamage::orderBy('created_at', 'DESC')->where('ingredient_id', $request->ingredient_id)->take(200)->get(); 
            // return view('admin.ingredients.damages.index', compact('damages', 'ingredient'));
            return redirect()->route('admin.ingredient.damage.index', $ingredient->id); 
        }else{
            return $this->responseRedirectBack(' Error occurred while adding a damage ingredient .' ,'error', false, false);    
        }
        
    }

    public function edit($id){
        $damage = IngredientDamage::find($id); 
        view()->share(['pageTitle' => 'Ingredients', 'subTitle' => 'Edit the damage ingredient: ' . $damage->name ]);
        $ingredient = Ingredient::find($damage->ingredient_id); 
        return view('admin.ingredients.damages.edit', compact('damage', 'ingredient'));
    }

    public function update(Request $request){

        $validated = $request->validate([
            'name' => 'required|string|max:191', 
            'quantity'  => 'required|numeric',
            'unit' => 'required|string',             
        ]);        
        //coverting date format from m-d-Y to Y-m-d as database stroes date in 'Y-m-d' format
        $reported_date = Carbon::createFromFormat('d-m-Y', $request->reported_date)->format('Y-m-d');

        //Getting the ingredient details from the ingredient stock. 
        $ingredientDamage = IngredientDamage::find($request->damage_id);
        //getting the ingredient details       
        $ingredient = Ingredient::find($ingredientDamage->ingredient_id);       
        $ingredient_total_quantity = $ingredient->total_quantity;
        $ingredient_total_price = $ingredient->total_price;            
        $ingredient_smallest_unit_price = $ingredient->smallest_unit_price;
        //initializing new damage_price.
        $damage_price = 0;

        //Adding ingredient price before subtracting new updated damage ingredient price
        $ingredient_total_price += $ingredientDamage->price; 
        
        //Adding ingredient quantity before subtracting new updated damage ingredient quantity
        if($ingredient->measurement_unit == $ingredientDamage->unit){
            $ingredient_total_quantity += $ingredientDamage->quantity;
        }else{
            // getting unit conversion value 
            $unit = Unit::where('smallest_measurement_unit', $ingredientDamage->unit)->first();            
            $unit_conversion = $unit->unit_conversion; 
            $ingredient_total_quantity += ($ingredientDamage->quantity/$unit_conversion); 
        }           

        //now subtracting damage ingredient updated quantity 
        if($ingredient->measurement_unit == $request->unit){
            // if damage unit is stock unit, then we directly substract from ingredient quantity.                      
            $ingredient_total_quantity -= $request->quantity; 
            // getting unit conversion value 
            $unit = Unit::where('measurement_unit', $request->unit)->first();            
            $unit_conversion = $unit->unit_conversion; 
            $damage_price = $ingredient_smallest_unit_price * ($request->quantity * $unit_conversion);

        }elseif($ingredient->smallest_unit == $request->unit){
            // calculating damage product price 
            $damage_price = $ingredient_smallest_unit_price * $request->quantity;      
            // getting unit conversion value 
            $unit = Unit::where('smallest_measurement_unit', $request->unit)->first();            
            $unit_conversion = $unit->unit_conversion; 
            //converting gm to kg, if damage unit is smallest unit and then substract from ingredient quantity.
            $ingredient_total_quantity -= ($request->quantity/$unit_conversion);                  
        } 

        $ingredient_total_price -= $damage_price;

        // updating the ingredient total quantity, total price with updated value.
        $ingredient->total_quantity = $ingredient_total_quantity;        
        $ingredient->total_price = $ingredient_total_price;
        $ingredient->save();

        // now we will update the damageingredient record
        $ingredientDamage->name = $request->name;
        $ingredientDamage->quantity = $request->quantity;
        $ingredientDamage->price = $damage_price;
        $ingredientDamage->unit = $request->unit;
        $ingredientDamage->reported_date =  $reported_date;
        $ingredientDamage->reported_by = auth()->user()->name;
        $ingredientDamage->save(); 
       
        if($ingredientDamage){           

            // setting flash message using trait
            $this->setFlashMessage(' Damage ingredient is updated successfully', 'success');    
            $this->showFlashMessages(); 

            // Attaching pagetitle and subtitle to view.
            view()->share(['pageTitle' => 'Ingredient Damage', 'subTitle' => 'Ingredient all damages list' ]); 

            // $damages =  IngredientDamage::orderBy('created_at', 'DESC')->where('ingredient_id', $ingredientDamage->ingredient_id)->take(200)->get(); 
            // return view('admin.ingredients.damages.index', compact('damages', 'ingredient')); 
            return redirect()->route('admin.ingredient.damage.index', $ingredient->id);
        }else{
            return $this->responseRedirectBack(' Error occurred while adding a damage ingredient .' ,'error', false, false);    
        }
    }

    public function delete($id){

        $ingredientDamage = IngredientDamage::find($id);
        //getting the ingredient details       
        $ingredient = Ingredient::find($ingredientDamage->ingredient_id);       
        $ingredient_total_quantity = $ingredient->total_quantity;
        $ingredient_total_price = $ingredient->total_price;

        //Adding ingredient price before deleting damage ingredient record.
        $ingredient_total_price += $ingredientDamage->price;  

        //Adding ingredient quantity before deleting damage ingredient record.
        if($ingredient->measurement_unit == $ingredientDamage->unit){
            $ingredient_total_quantity += $ingredientDamage->quantity;            
        }else{
            // getting unit conversion value 
            $unit = Unit::where('smallest_measurement_unit', $ingredientDamage->unit)->first();            
            $unit_conversion = $unit->unit_conversion; 
            $ingredient_total_quantity += ($ingredientDamage->quantity/$unit_conversion);             
        }
  
        // updating the ingredient total quatity, total price and unit price
        $ingredient->total_quantity = $ingredient_total_quantity;        
        $ingredient->total_price = $ingredient_total_price;        
        $ingredient->save(); 
        // Deleting the ingredient purchase record.
        $ingredientDamage->delete();

        if(!$ingredientDamage){
            return  $this->responseRedirectBack(' Error occurred while deleting the category.', 'error', true, true);
        }
        $this->setFlashMessage(' Ingredient purchase record is deleted successfully', 'success');    
        $this->showFlashMessages();
        // Attaching pagetitle and subtitle to view.
        view()->share(['pageTitle' => 'Ingredient Damage', 'subTitle' => 'Ingredient all damages list' ]); 
        // $damages =  IngredientDamage::orderBy('created_at', 'DESC')->where('ingredient_id', $ingredient->id)->take(200)->get();
        // return view('admin.ingredients.damages.index', compact('damages', 'ingredient')); 
        return redirect()->route('admin.ingredient.damage.index', $ingredient->id);
      
    }
}
