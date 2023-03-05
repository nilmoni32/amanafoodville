<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\FlashMessages;
use App\Models\Typeingredient;
use App\Models\Ingredient; 
use App\Models\Unit; 
use App\Models\IngredientPurchase;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Storage;

class IngredientController extends Controller
{
    use FlashMessages;

     /**
     * Listing all Ingredients       
     */
    public function index(){
        // Attaching pagetitle and subtitle to view.
        view()->share(['pageTitle' => 'Ingredients', 'subTitle' => 'List of all ingredients' ]);
        $ingredients = Ingredient::orderBy('created_at', 'desc')->get();        
        return view('admin.ingredients.index', compact('ingredients'));  // returning the admin.categories.index view with categories
    }
    /**
     * Add ingredient to the list
     */
    public function create(){
        // Attaching pagetitle and subtitle to view.
        view()->share(['pageTitle' => 'Ingredients', 'subTitle' => 'Add ingredient' ]);
        $ingredienttypes = Typeingredient::where('id', '<>', 1)->get(); //except the root category.  
        $units = Unit::all(); //except the root category.       
        return view('admin.ingredients.create', compact('ingredienttypes', 'units'));  
    }

    /**
     * Save the ingredient
     * @param Request $request  
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request){

        $validated = $request->validate([
            'name' => 'required|unique:ingredients,name|max:191',            
            'typeingredient_id'     => 'required|not_in:1',
            'alert_quantity'  => 'required|numeric',
            'measurement_unit' => 'required|string',             
            'pic' => 'nullable|mimes:jpeg,png,jpg,gif,svg,webp|max:200',
        ]);           
        
        // setting smallest measurement unit of the ingredient       
        $unit = Unit::where('measurement_unit', $request->measurement_unit)->first();
        $smallest_unit = $unit->smallest_measurement_unit;
                
        $ingredient= Ingredient::create([
            'name' => $request->name,
            'typeingredient_id' => $request->typeingredient_id,
            'description' => $request->description,
            'alert_quantity' =>   $request->alert_quantity, 
            'measurement_unit' =>   $request->measurement_unit,
            'smallest_unit' =>   $smallest_unit,
        ]);

        // uploading image for ingredient   
        if($request->hasFile('pic')) {           
            $imageName = $request->pic->getClientOriginalName();             
            $request->pic->storeAs('images', $imageName, 'public'); 
            // store image at storage/app/public/images. 
            // we need to create symbolic link to access public/storage/images/ : php artisan storage:link
            $ingredient->update(['pic'=> $imageName ]); // if image exists for that ingredient we just update it 
        } 

        
        if($ingredient){           

            // setting flash message using trait
            $this->setFlashMessage(' A new ingredient is added successfully', 'success');    
            $this->showFlashMessages(); 

            // Attaching pagetitle and subtitle to view.
            view()->share(['pageTitle' => 'Ingredients', 'subTitle' => 'List of all ingredients' ]); 

            // $ingredients = Ingredient::orderBy('created_at', 'DESC')->get();
            // return view('admin.ingredients.index', compact('ingredients'));

            return redirect()->route('admin.ingredient.index');

        }else{
            return $this->responseRedirectBack(' Error occurred while adding an ingredient .' ,'error', false, false);    
        }

    }

    public function edit($id){    
        $ingredient = Ingredient::find($id);        
        view()->share(['pageTitle' => 'Ingredients', 'subTitle' => 'Edit the ingredient: ' .$ingredient->name]);               
        $ingredienttypes = Typeingredient::all();
        return view('admin.ingredients.edit', compact('ingredienttypes', 'ingredient'));
    }

  

     /**
     * Update the ingredient
     * @param Request $request  
     * @throws \Illuminate\Validation\ValidationException
     */

    public function update(Request $request){

        $validated = $request->validate([
            'name' => 'required|string|max:191',            
            'typeingredient_id'     => 'required|not_in:1',
            'alert_quantity'  => 'required|numeric',
            'measurement_unit' => 'nullable|string',            
            'pic' => 'nullable|mimes:jpeg,png,jpg,gif,svg,webp|max:200',
        ]); 
        
         // Getting the ingredient record
         $ingredient= Ingredient::find($request->ingredient_id);
        // setting smallest measurement unit of the ingredient
        // checking purchase table has no ingredient purchase record or not
        if(!IngredientPurchase::where('ingredient_id', $ingredient->id)->count()){
            
            $unit = Unit::where('measurement_unit', $request->measurement_unit)->first();
            $smallest_unit = $unit->smallest_measurement_unit;
        }        
          
        // updating the ingredient data.
        $ingredient->name = $request->name;
        $ingredient->typeingredient_id = $request->typeingredient_id;
        $ingredient->description =  $request->description;  
        $ingredient->alert_quantity =  $request->alert_quantity;
        // when purchase table has no ingredient purchase record, only then we can update the $ingredient->measurement_unit
        if(!IngredientPurchase::where('ingredient_id', $ingredient->id)->count()){
            $ingredient->measurement_unit =  $request->measurement_unit;     
            $ingredient->smallest_unit =  $smallest_unit; 
        }         
        $ingredient->save();        

        // uploading the new image for ingredient   
        if($request->hasFile('pic')) {           
            $imageName = $request->pic->getClientOriginalName();   
            //deleting the image if it is stored in Storage/app/public/images
            if($ingredient->pic){
                Storage::delete('/public/images/'.$ingredient->pic);
            }          
            $request->pic->storeAs('images', $imageName, 'public'); 
            // store image at storage/app/public/images. 
            // we need to create symbolic link to access public/storage/images/ : php artisan storage:link
            $ingredient->update(['pic'=> $imageName ]); // if image exists for that ingredient we just update it 
        } 

        
        if($ingredient){           

            // setting flash message using trait
            $this->setFlashMessage(' Ingredient is updated successfully', 'success');    
            $this->showFlashMessages(); 

            // Attaching pagetitle and subtitle to view.
            view()->share(['pageTitle' => 'Ingredients', 'subTitle' => 'List of all ingredients' ]); 

            // $ingredients = Ingredient::orderBy('created_at', 'DESC')->get();
            // return view('admin.ingredients.index', compact('ingredients'));
            return redirect()->route('admin.ingredient.index');
        }else{
            return $this->responseRedirectBack(' Error occurred while updating the ingredient .' ,'error', false, false);    
        }

    }
    public function delete($id){
        $ingredient = Ingredient::find($id);
        //deleting the image if it is stored in Storage/app/public/images
        if($ingredient->pic){
            Storage::delete('/public/images/'.$ingredient->pic);
        }
        $ingredient->delete();

        if(!$ingredient){
            return  $this->responseRedirectBack(' Error occurred while deleting the category.', 'error', true, true);
         }
        $this->setFlashMessage(' Ingredient is deleted successfully', 'success');    
        $this->showFlashMessages();
        return redirect()->route('admin.ingredient.index');
    
    }

    /*
    * Ajax request
    */
    public function getIngredients(Request $request){

        $search = $request->search;

        if($search == ''){
            $ingredients = Ingredient::orderby('name','asc')
                                ->select('id','name')                                
                                ->limit(10)->get();
        }else{
            $ingredients = Ingredient::orderby('name','asc')
                        ->select('id','name')
                        ->where('name', 'like', '%' .$search . '%')                        
                        ->limit(10)
                        ->get();
        }

        $response = array();
        foreach($ingredients as $ingredient){            
            $response[] = array( "value" => $ingredient->id, "label" => $ingredient->name );            
        }

        return response()->json($response);   
    }
}
