<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Ingredient;
use App\Models\Supplier;
use App\Models\Unit; 
use App\Models\SupplierStock;
use App\Models\RecipeIngredient;
use App\Models\Recipe;
use App\Models\RequisitionToSupplier;
use App\Models\ReceiveFromSupplier;
use App\Models\ReceiveIngredientList;
use App\Models\RequisitionIngredientList;
use Illuminate\Http\Request;
use App\Traits\FlashMessages;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController;
use PDF;

class DeliveryChallanController extends Controller
{
    use FlashMessages;
    public function index(){
        // Attaching pagetitle and subtitle to view.
        view()->share(['pageTitle' => 'Supplier Delivery Challan', 'subTitle' => 'List of all received Supplier challans.' ]);        
        return view('admin.supplierChallan.index');
    }

    public function create(){
        // Attaching pagetitle and subtitle to view.
        view()->share(['pageTitle' => 'Create a Supplier Delivery Challan', 'subTitle' => '' ]);   
        $suppliers = Supplier::all();     
        return view('admin.supplierChallan.create',compact('suppliers'));
    }

    public function getRequisitions($id){
        $requisitions = RequisitionToSupplier::find($id);       
        return response()->json(['success'=>true, 'requisitions'=>$requisitions]); 
    }

    public function getRequisitionsFromDateWithSupplier($from_date, $to_date, $supplier_id){
        $requisitions = RequisitionToSupplier::where('supplier_id', $supplier_id)
                        ->whereDate('requisition_date', '>=', Carbon::createFromFormat('d-m-Y', $from_date)->format('Y-m-d'))                
                        ->whereDate('requisition_date', '<=', Carbon::createFromFormat('d-m-Y', $to_date)->format('Y-m-d'))
                        ->get();  
        return response()->json(['success'=>true, 'requisitions'=>$requisitions]); 
    }

    public function getOnlyRequisition($id){
        $supplier_requisition = RequisitionToSupplier::find($id);
        $supplier_name = Supplier::find($supplier_requisition->supplier_id)->name;         
        $stockItems = SupplierStock::where('supplier_id', $supplier_requisition->supplier_id)->get(); 
        //execluding certains columns (created_at, updated_at) while using eloquent       
        $items = RequisitionIngredientList::where('requisition_to_supplier_id', $id)->get();//->makeHidden(['created_at','updated_at'])->toArray();         
        $requisition_items = [];
        $i = 0;
        foreach($items as $item){
            $supplier_stock = SupplierStock::find($item->supplier_stock_id);
            $hasDifferProductUnit = $supplier_stock->has_differ_product_unit;
            $productUnit = $hasDifferProductUnit ? strtolower($supplier_stock->product_unit) : strtolower($supplier_stock->measurement_unit);
            $productQty = $supplier_stock->product_qty;
            //getting recipe stock unit & unit conversion to calculate recipe stock qty
            $recipe_stock_unit = strtolower($supplier_stock->ingredient->measurement_unit);
            $recipe_stock_small_unit = strtolower($supplier_stock->ingredient->smallest_unit);
            $recipe_stock_unit_conversion = Unit::where('smallest_measurement_unit', $recipe_stock_small_unit)->first()->unit_conversion;
            //getting supplier stock unit & unit conversion to calculate recipe stock qty
            $supplier_stock_unit = Unit::where('measurement_unit', $productUnit)->first();
            $small_measurement_unit = $supplier_stock_unit->smallest_measurement_unit;
            $conversion_unit = $supplier_stock_unit->unit_conversion;

            $requisition_items[$i]['id'] = $item->supplier_stock_id;            
            $requisition_items[$i]['ingredient_unit'] = $supplier_stock->ingredient->measurement_unit;
            $requisition_items[$i]['has_differ_unit'] = $hasDifferProductUnit;
            $requisition_items[$i]['product_actual_unit'] = $productUnit;
            $requisition_items[$i]['product_actual_qty'] = $productQty; 
            $requisition_items[$i]['name'] = $item->name;
            $requisition_items[$i]['unit'] = $item->unit;
            $requisition_items[$i]['unit_cost'] = $item->unit_cost;
            $requisition_items[$i]['quantity'] = $item->quantity;
            $requisition_items[$i]['stock'] = $item->stock;
            $requisition_items[$i]['total'] = $item->total;
            $requisition_items[$i]['recipe_stock_qty'] = $hasDifferProductUnit && $productUnit === $recipe_stock_unit  ? round(floatval($productQty * ($item->quantity)), 2) 
            : ($hasDifferProductUnit && $productUnit !== $recipe_stock_unit && $recipe_stock_unit == $small_measurement_unit ? round(floatval($productQty) * (floatval($item->quantity)* floatval($conversion_unit)), 2) 
            : ($hasDifferProductUnit && $productUnit !== $recipe_stock_unit && $recipe_stock_small_unit == $productUnit ?  round(floatval($productQty) * (floatval($item->quantity)/floatval($recipe_stock_unit_conversion)),2) 
            : (!$hasDifferProductUnit && $productUnit === $recipe_stock_unit ? round(floatval($item->quantity),2)
            : (!$hasDifferProductUnit && $productUnit !== $recipe_stock_unit && $recipe_stock_unit == $small_measurement_unit ? round(((floatval($item->quantity)) * floatval($conversion_unit)),2) 
            : (!$hasDifferProductUnit && $productUnit !== $recipe_stock_unit && $recipe_stock_small_unit == $productUnit ? round((floatval($item->quantity)) / floatval($recipe_stock_unit_conversion),2): ''))))); 

            $i++;
        }
        
        return response()->json(['success'=>true, 'supplier' => $supplier_name,'requisition'=>$supplier_requisition, 'stockItems' => $stockItems,  'requisitionItems' => $requisition_items ]); 
    }

    public function getProductDetail($id){
        $ss_item = SupplierStock::find($id);
        $unit = Unit::where('measurement_unit', $ss_item->has_differ_product_unit ? $ss_item->product_unit : $ss_item->measurement_unit)->first();
        $small_measurement_unit = $unit->smallest_measurement_unit;
        $conversion_unit = $unit->unit_conversion;
        return response()->json(['success'=>true, 'item'=>$ss_item, 'small_measurement_unit' => $small_measurement_unit, 'conversion_unit' => $conversion_unit]);
    }

    public function getRecipeIngredientUnits($id){
        $recipe_ingredientUnit = SupplierStock::find($id)->ingredient->measurement_unit;
        $recipe_ingredient_smallUnit = SupplierStock::find($id)->ingredient->smallest_unit;
        $unit_conversion_for_recipe_ingredient_smallUnit = Unit::where('smallest_measurement_unit', strtolower($recipe_ingredient_smallUnit))->first()->unit_conversion;
        return response()->json(['success'=>true, 'recipeIngredientUnit'=>$recipe_ingredientUnit, 'recipeIngredientSmallUnit' => $recipe_ingredient_smallUnit, 'uc_recipe_ingredient_smallUnit'=> $unit_conversion_for_recipe_ingredient_smallUnit]);
    }

    public function store(Request $request){
        //dd($request->all());

        $this->validate($request,[
            'chalan_no'            => 'required|unique:receive_from_suppliers,chalan_no|numeric|gt:0',
            'chalan_date'          => 'required|date_format:"d-m-Y"',             
            'payment_date'         => 'required|date_format:"d-m-Y"',
            'purpose'              => 'nullable|string|max:191',
            'total_quantity'       => 'required|numeric|gt:0',
            'total_amount'         => 'required|numeric|gt:0',
        ],
        [       
            'chalan_no.required' => 'Challan No is required',  
            'chalan_no.unique'   => 'This Challan no has already been received',         
        ]);

        /*
        * storing requisition all the product details to a supplier.
        */       

        $product_lists = '';
        //Convert JSON String to PHP Array 
        $product_lists = json_decode($request->product_lists, true);
        foreach($product_lists as $product){
            if($product['recipe_stk_qty'] == ""){
                $this->setFlashMessage($product['name'] .' must have recipe stock quantity', 'error');    
                $this->showFlashMessages();
                return redirect()->back();
            }
        }
        //dd($product_lists);
        $receive_to_supplier = ReceiveFromSupplier::create([
            'supplier_id'                  => $request->supplier_id,
            'admin_id'                     => auth()->user()->id,
            'requisition_to_supplier_id'   => $request->supplier_requisition_no,
            'chalan_no'                    => $request->chalan_no,
            'chalan_date'                  => Carbon::createFromFormat('d-m-Y', $request->chalan_date)->format('Y-m-d'),
            'payment_date'                 => Carbon::createFromFormat('d-m-Y', $request->payment_date)->format('Y-m-d'),
            'requisition_date'             => $request->supplier_requisition_dt ? explode(' ', $request->supplier_requisition_dt)[0] : null,
            'purpose'                      => $request->purpose,
            'total_quantity'               => $request->total_quantity,
            'total_amount'                 => $request->total_amount,
        ]);        

        foreach($product_lists as $product){
            $supplier_stock = SupplierStock::find($product['supplier_stock_id']);
            //creating a new instance of RequisitionIngredientList table
            $receive_product = new ReceiveIngredientList();
            $receive_product->receive_from_supplier_id = $receive_to_supplier->id;
            $receive_product->ingredient_id = $supplier_stock->ingredient_id; 
            $receive_product->supplier_stock_id = $product['supplier_stock_id'];
            $receive_product->name = $product['name'];
            $receive_product->unit = $product['unit'];
            $receive_product->unit_cost = $product['unit_cost'];
            $receive_product->quantity = $product['quantity'];
            $receive_product->stock = $product['stock'];
            $receive_product->total = $product['total'];
            $receive_product->recipe_ingredient_unit = strtolower($product['recipe_unit']);
            $receive_product->recipe_ingredient_quantity = $product['recipe_stk_qty'];
            $receive_product->save();

            //updating the supplier product stock after receiving the product
            $supplier_stock->total_qty += $product['quantity'];
            $supplier_stock->total_cost += $product['total']; 
            $supplier_stock->unit_cost = $product['unit_cost'];
            $supplier_stock->save();
            //updating the recipe stock, Recipe cost and Recipe ingredients unit price update
            $this->RecipeStockUpdate($supplier_stock->ingredient_id, strtolower($product['recipe_unit']), $product['recipe_stk_qty'], $product['total']);

        }

        //updating the requisition remarks status to received
        $requisition_to_supplier = RequisitionToSupplier::find($receive_to_supplier->requisition_to_supplier_id);        
        $requisition_to_supplier->remarks = 'received';
        $requisition_to_supplier->save();

        

        if($product_lists && $receive_to_supplier){
            // setting flash message using trait
            $this->setFlashMessage(' Supplier challan receiving is added successfully', 'success');    
            $this->showFlashMessages(); 
            return redirect()->route('admin.supplier.challan.index');
        }else{
            $this->setFlashMessage(' Error is occurred while supplier challan is receiving', 'error');    
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
            $ingredient_total_quantity += $purchase_qty; 
        }else{
            $unit = Unit::where('smallest_measurement_unit', $purchase_unit)->first();            
            $unit_conversion = $unit->unit_conversion; 
            $ingredient_total_quantity += ($purchase_qty/$unit_conversion); 
        } 
        //when ingredient total qty is negative i.e more sales results ingredient qty become negative in the stock.        
        if($ingredient->total_quantity < 0){             
            // calculating ingredient smallest unit price when $ingredient->total_price & $ingredient->total_quantity both are negative.
            if($ingredient->total_price < 0){
                if(strtolower($ingredient->measurement_unit) == $purchase_unit){ 
                    $negative_ingredient_total_quantity = $ingredient->total_quantity - $purchase_qty;
                }else{
                    $negative_ingredient_total_quantity = $ingredient->total_quantity - ($purchase_qty/$unit_conversion);
                }                                
                $negative_ingredient_total_price = $ingredient->total_price - $purchase_price;
                $ingredient_smallest_unit_price = abs($negative_ingredient_total_price/($negative_ingredient_total_quantity * $unit_conversion));
            }          
            
            // Now calculating stock ingredient total price. 
            $ingredient_total_price = ($ingredient_total_quantity * $unit_conversion)* $ingredient_smallest_unit_price;            
        }else{ // when $ingredient->total_price = 0 after sales, considering the old $ingredient_smallest_unit_price
            //calculating total ingredient price 
            $ingredient_total_price += $purchase_price;  
            //calculating ingredient unit price
            $ingredient_smallest_unit_price = $ingredient_total_price/($ingredient_total_quantity * $unit_conversion);
        }  
      
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
     *  Ajax Request to fetch Supplier requisition related data
     */

    public function getSupplierChallan(Request $request){

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
        $totalRecords = ReceiveFromSupplier::select('count(*) as allcount')->count();
        $totalRecordswithFilter = ReceiveFromSupplier::select('count(*) as allcount')->where('supplier_id', 'like', '%' . $searchValue . '%')->count();
        // Fetch records
        $records = ReceiveFromSupplier::orderBy($columnName, $columnSortOrder)
            ->where('receive_from_suppliers.chalan_date', 'like', '%' . $searchValue . '%') 
            ->orWhere('receive_from_suppliers.payment_date', 'like', '%' . $searchValue . '%')           
            ->orWhere('receive_from_suppliers.supplier_id', 'like', '%' . (Supplier::where('name', 'LIKE', '%' . $searchValue . '%')->first() ? Supplier::where('name', 'LIKE', '%' . $searchValue . '%')->first()->id : $searchValue) . '%')            
            ->orWhere('receive_from_suppliers.total_quantity', 'like', '%' . $searchValue . '%')
            ->orWhere('receive_from_suppliers.total_amount', 'like', '%' . $searchValue . '%')
            ->orWhere('receive_from_suppliers.chalan_no', 'like', '%' . $searchValue . '%')
            ->orWhere('receive_from_suppliers.requisition_to_supplier_id', 'like', '%' . $searchValue . '%')            
            ->select('receive_from_suppliers.*')
            ->skip($start) 
            ->take($rowperpage)
            ->get();

        $data_arr = array();
        //$sno = $start+1; 
        foreach($records as $record){             
            $data_arr[] = array( 
                "chalan_no"                    => $record->chalan_no,
                "requisition_to_supplier_id"   => $record->requisition_to_supplier_id,
                "chalan_date"                  => explode(' ', $record->chalan_date)[0],  
                "payment_date"                 => explode(' ', $record->payment_date)[0],// converting date string to array and get the date         
                "supplier_id"                  => $record->supplier->name,
                "total_quantity"               => $record->total_quantity,
                "total_amount"                 => round($record->total_amount,2),                
                "action"                       => '<div class="btn-group" role="group" aria-label="Second group">                                                    
                                                    <a href="'. url("admin/supplier/challan/pdf/{$record->id}"). '"
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


    public function generateChallanPdf($id){
        $receiving_challan = ReceiveFromSupplier::find($id);
        $receiving_items = ReceiveIngredientList::where('receive_from_supplier_id', $id)->get(); 
        $pdf = PDF::loadView('admin.report.challan.pdf_challan', compact('receiving_challan','receiving_items'))->setPaper('a4', 'potrait');
        return $pdf->stream('pdf_supplier_challan_items.pdf');
    }



    

    

}
