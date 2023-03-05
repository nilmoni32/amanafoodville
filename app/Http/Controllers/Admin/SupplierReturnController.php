<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Ingredient;
use App\Models\Supplier;
use App\Models\ReceiveFromSupplier;
use App\Models\ReturnToSupplier;
use App\Models\Unit; 
use App\Models\SupplierStock;
use App\Models\RecipeIngredient;
use App\Models\Recipe;
use App\Models\ReturnIngredientList;
use Illuminate\Http\Request;
use App\Traits\FlashMessages;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Collection;
use PDF;

class SupplierReturnController extends Controller
{
    //
    use FlashMessages;
    public function index(){
        // Attaching pagetitle and subtitle to view.
        view()->share(['pageTitle' => 'Return Products to Supplier', 'subTitle' => 'All return product invoice list' ]);        
        return view('admin.supplierReturn.index');
    }

    public function create(){
        // Attaching pagetitle and subtitle to view.
        view()->share(['pageTitle' => 'Create Return Invoice', 'subTitle' => 'To Supplier' ]);
        $suppliers = Supplier::all();
        return view('admin.supplierReturn.create', compact('suppliers'));
    }  

    public function getAllSupplierProducts($id){        
        $items = SupplierStock::where('supplier_id', $id)->get();  
        //define a collection.
        $stock_items = collect();        
        foreach($items as $supplier_stock){            
            $recipe_stock_unit = strtolower($supplier_stock->ingredient->measurement_unit);
            $recipe_stock_small_unit = strtolower($supplier_stock->ingredient->smallest_unit);            
            $productUnit = $supplier_stock->has_differ_product_unit ? strtolower($supplier_stock->product_unit) : strtolower($supplier_stock->measurement_unit);
            //supplier product unit is cross checked with recipe stock product unit        
            if($productUnit == $recipe_stock_unit || $productUnit == $recipe_stock_small_unit || $productUnit == Unit::where('smallest_measurement_unit', $recipe_stock_small_unit)->first()->measurement_unit ){
               //adding items to a collection
                $stock_items->push([ 
                    'id'                        => $supplier_stock->id,
                    'ingredient_id'             => $supplier_stock->ingredient_id,
                    'supplier_id'               => $supplier_stock->supplier_id,
                    'typeingredient_id'         => $supplier_stock->typeingredient_id,
                    'supplier_product_name'     => $supplier_stock->supplier_product_name,
                    'measurement_unit'          => $supplier_stock->measurement_unit,
                    'has_differ_product_unit'   => $supplier_stock->has_differ_product_unit,
                    'product_unit'              => $supplier_stock->product_unit,
                    'product_qty'               => $supplier_stock->product_qty,
                    'unit_cost'                 => $supplier_stock->unit_cost,
                    'total_qty'                 => $supplier_stock->total_qty,
                    'total_cost'                => $supplier_stock->total_cost
                   ]); 
           }
        } 
        
        return response()->json(['success'=>true, 'items' =>$stock_items ]);      
    }

    public function store(Request $request){

        $this->validate($request,[
            'chalan_date'          => 'required|date_format:"d-m-Y"',
            'purpose'              => 'nullable|string|max:191',       
            'customer_notes'       => 'nullable|string|max:191',    
            'total_quantity'       => 'required|numeric|gt:0',
            'total_amount'         => 'required|numeric|gt:0',
        ]);        
        /*
        * storing requisition all the product details to a supplier.
        */
        $product_lists = '';
        //Convert JSON String to PHP Array 
        $product_lists = json_decode($request->product_lists, true);
        //Before returning the product checking recipe stock product quantity is empty or not 
        foreach($product_lists as $product){
            $recipe_product_stock = Ingredient::find(SupplierStock::find($product['supplier_stock_id'])->ingredient_id)->total_quantity;
            if($recipe_product_stock < 0 || $recipe_product_stock < $product['recipe_stk_qty']){
                $this->setFlashMessage($product['name'] .' have corresponding recipe stock quantity is become less or empty', 'info');    
                $this->showFlashMessages();
                return redirect()->back();
            }
        }
        
        $return_to_supplier = ReturnToSupplier::create([
            'supplier_id'               => $request->supplier_id,
            'admin_id'                  => auth()->user()->id,
            'chalan_date'               => Carbon::createFromFormat('d-m-Y', $request->chalan_date)->format('Y-m-d'),            
            'purpose'                   => $request->purpose, 
            'remarks'                   => $request->remarks,
            'total_quantity'            => $request->total_quantity,
            'total_amount'              => $request->total_amount,
        ]);

        foreach($product_lists as $product){            
            //creating a new instance of RequisitionIngredientList table          
            $return_product = new ReturnIngredientList();
            $return_product->return_to_supplier_id  = $return_to_supplier->id;
            $return_product->supplier_stock_id = $product['supplier_stock_id'];
            $return_product->name = $product['name'];
            $return_product->unit = $product['unit'];
            $return_product->unit_cost = $product['unit_cost'];
            $return_product->quantity = $product['quantity'];
            $return_product->stock = $product['stock'];
            $return_product->total = $product['total'];
            $return_product->save(); 
            //updating the supplier product stock after returning the product to supplier.
            $supplier_stock = SupplierStock::find($product['supplier_stock_id']);
            $supplier_stock->total_qty -= $product['quantity'];
            $supplier_stock->total_cost -= $product['total'];             
            $supplier_stock->save();
            //updating the recipe stock, Recipe cost and Recipe ingredients unit price update
            $this->RecipeStockUpdate($supplier_stock->ingredient_id, strtolower($product['recipe_unit']), $product['recipe_stk_qty'], $product['total']);
        }

        if($product_lists && $return_to_supplier){
            // setting flash message using trait
            $this->setFlashMessage(' Return products to the supplier has done successfully', 'success');    
            $this->showFlashMessages(); 
            return redirect()->route('admin.supplier.return.index');
        }else{
            $this->setFlashMessage(' Error occurred while returning products to the supplier', 'error');    
            $this->showFlashMessages();
            return redirect()->back();
        }
    }

    public function RecipeStockUpdate($ingredient_id, $purchase_unit, $purchase_qty, $purchase_price){       
        // getting the ingredient details       
        $ingredient = Ingredient::find($ingredient_id);
        
        // Setting ingredient total quantity, total price, per unit cost price 
        $ingredient_total_quantity = $ingredient->total_quantity;
        $ingredient_total_price = $ingredient->total_price;
        $ingredient_smallest_unit_price = $ingredient->smallest_unit_price;

        $unit_conversion = 0;
        // now calculating stock ingredient quantity & unit conversion.
        if(strtolower($ingredient->measurement_unit) == $purchase_unit){
            $unit = Unit::where('measurement_unit', $purchase_unit)->first();            
            $unit_conversion = $unit->unit_conversion;
            $ingredient_total_quantity -= $purchase_qty; 
        }else{
            $unit = Unit::where('smallest_measurement_unit', $purchase_unit)->first();            
            $unit_conversion = $unit->unit_conversion; 
            $ingredient_total_quantity -= ($purchase_qty/$unit_conversion); 
        }
        
        //calculating total ingredient price 
        $ingredient_total_price -= $purchase_price;  
        //calculating ingredient unit price
        $ingredient_smallest_unit_price = $ingredient_total_price/($ingredient_total_quantity * $unit_conversion);      
        // updating the stock ingredient total quatity, total price and unit price
        $ingredient->total_quantity = $ingredient_total_quantity;        
        $ingredient->total_price = $ingredient_total_price;
        $ingredient->smallest_unit_price = $ingredient_smallest_unit_price;
        $ingredient->save();  
        
        // updating the ingredient unit price to recipe ingredients of the corresponding recipe.
        $recipeIngredients =  RecipeIngredient::where('ingredient_id', $ingredient_id)->get(); 
        foreach($recipeIngredients as $recipeIngredient){
            //updating the ingredient unit price and its total cost for the recipe.
            $recipeIngredient->ingredient_total_cost = $ingredient_smallest_unit_price * $recipeIngredient->quantity;
            $recipeIngredient->unit_price = $ingredient_smallest_unit_price;
            $recipeIngredient->save();
        } 
        
        // updating the food recipe cost.
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
    }

    /**
     *  Ajax Request to fetch products return to Supplier related data
     */

    public function getSupplierReturn(Request $request){

        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value 
            
        // Total records
        $totalRecords = ReturnToSupplier::select('count(*) as allcount')->count();
        $totalRecordswithFilter = ReturnToSupplier::select('count(*) as allcount')->where('supplier_id', 'like', '%' . $searchValue . '%')->count();
        // Fetch records
        $records = ReturnToSupplier::orderBy($columnName, $columnSortOrder)
            ->where('return_to_suppliers.chalan_date', 'like', '%' . $searchValue . '%')
            ->orWhere('return_to_suppliers.supplier_id', 'like', '%' . (Supplier::where('name', 'LIKE', '%' . $searchValue . '%')->first() ? Supplier::where('name', 'LIKE', '%' . $searchValue . '%')->first()->id : $searchValue) . '%')            
            ->orWhere('return_to_suppliers.total_quantity', 'like', '%' . $searchValue . '%')
            ->orWhere('return_to_suppliers.total_amount', 'like', '%' . $searchValue . '%')                     
            ->select('return_to_suppliers.*')
            ->skip($start) 
            ->take($rowperpage)
            ->get();

        $data_arr = array();
        //$sno = $start+1; 
        foreach($records as $record){             
            $data_arr[] = array(
                "id"                           => $record->id,
                "chalan_date"                  => explode(' ', $record->chalan_date)[0],
                "supplier_id"                  => $record->supplier->name,
                "total_quantity"               => round($record->total_quantity,2),
                "total_amount"                 => round($record->total_amount,2),                
                "action"                       => '<div class="btn-group" role="group" aria-label="Second group">                                                    
                                                    <a href="'. url("admin/supplier/return/pdf/{$record->id}"). '"
                                                    class="btn btn-sm btn-dark" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                                 </div>',

            ); 


        } 
        $response = array( 
            "draw" => intval($draw), 
            "iTotalRecords" => $totalRecords, 
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
        );

        echo json_encode($response); 
        exit;
    
    }

    public function generateReturnPdf($id){        
        $return_challan = ReturnToSupplier::find($id);
        $return_items = ReturnIngredientList::where('return_to_supplier_id', $id)->get(); 
        $pdf = PDF::loadView('admin.report.returnToSupplier.pdf_return', compact('return_challan','return_items'))->setPaper('a4', 'potrait');
        return $pdf->stream('pdf_supplier_return_items.pdf');       
    }

}
