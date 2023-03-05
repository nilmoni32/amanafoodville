<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Unit;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Validator;
use App\Traits\FlashMessages; 


class IngredientUnitController extends Controller
{
    use FlashMessages;

    public function index(){
         // Attaching pagetitle and subtitle to view.
         view()->share(['pageTitle' => 'Ingredient Units', 'subTitle' => 'List of all ingredient units']);
         $ingredient_units = Unit::orderBy('created_at', 'desc')->get();        
         return view('admin.units.index', compact('ingredient_units')); 
    }

    public function create(){  
        // Attaching pagetitle and subtitle to view.
        view()->share(['pageTitle' => 'Ingredient Units', 'subTitle' => 'Add ingredient unit' ]);       
        return view('admin.units.create');  
    }

    public function store(Request $request){

        $this->validate($request,[
            'measurement_unit' => 'required|unique:units,measurement_unit',            
            'smallest_measurement_unit' => 'required|string',
            'unit_conversion'  => 'required|numeric',           
        ]);

        $ingredientUnit= Unit::create([
            'measurement_unit' => $request->measurement_unit,
            'smallest_measurement_unit' => $request->smallest_measurement_unit,
            'unit_conversion' =>   $request->unit_conversion,              
        ]);
        
        if($ingredientUnit){          

            // setting flash message using trait
            $this->setFlashMessage(' Ingredient unit is created successfully', 'success');    
            $this->showFlashMessages(); 

            // Attaching pagetitle and subtitle to view.
            view()->share(['pageTitle' => 'Ingredient Units', 'subTitle' => 'List of all ingredient units']);
            
            // $ingredient_units = Unit::orderBy('created_at', 'desc')->get();        
            // return view('admin.units.index', compact('ingredient_units'));
            return redirect()->route('admin.ingredientunit.index');
        }else{
            return $this->responseRedirectBack(' Error occurred while adding Ingredient unit.' ,'error', false, false);    
        }
    }

    public function edit($id){
        $ingredientUnit = Unit::find($id);
        view()->share(['pageTitle' => 'Ingredient Units', 'subTitle' => 'Edit Ingredient Unit']); 
        return view('admin.units.edit', compact('ingredientUnit'));   
    }

    public function update(Request $request){

        $this->validate($request,[
            'measurement_unit' => 'required|string',            
            'smallest_measurement_unit' => 'required|string',
            'unit_conversion'  => 'required|numeric',           
        ]);

        $ingredientUnit = Unit::find($request->id);        
        $ingredientUnit->measurement_unit = $request->measurement_unit;
        $ingredientUnit->smallest_measurement_unit = $request->smallest_measurement_unit;
        $ingredientUnit->unit_conversion = $request->unit_conversion;
        $ingredientUnit->save();

        // setting flash message using trait
        $this->setFlashMessage(' Ingredient Unit is updated successfully', 'success');    
        $this->showFlashMessages(); 

        // Attaching pagetitle and subtitle to view.
        view()->share(['pageTitle' => 'Ingredient Types', 'subTitle' => 'List of all ingredient types' ]); 

        // $ingredient_units = Unit::orderBy('created_at', 'desc')->get();        
        // return view('admin.units.index', compact('ingredient_units')); 
        return redirect()->route('admin.ingredientunit.index');

    }

    public function delete($id){
        
        $ingredientUnit = Unit::find($id); 
        $ingredientUnit->delete();

        if(!$ingredientUnit){
            return  $this->responseRedirectBack(' Error occurred while deleting the ingredient unit.', 'error', true, true);
         }
        $this->setFlashMessage(' Ingredient unit is deleted successfully', 'success');    
        $this->showFlashMessages();
        return redirect()->route('admin.ingredientunit.index');
    }
}
