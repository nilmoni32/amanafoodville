<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Ingredient;
use App\Models\Unit; 
use App\Models\SupplierStock;
use App\Models\RecipeIngredient;
use App\Models\Recipe;
use App\Models\IngredientDisposal;
use App\Models\DisposalIngredientList;
use Illuminate\Http\Request;
use App\Traits\FlashMessages;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Collection;
use App\Models\Typeingredient;
use PDF;

class ProductDisposalController extends Controller
{
    //
    use FlashMessages;

    public function index(){
       // Attaching pagetitle and subtitle to view.
       view()->share(['pageTitle' => 'Transaction', 'subTitle' => 'Supplier Product Disposal List' ]);        
       return view('admin.productDisposal.index');
    }

    public function create(){
        // Attaching pagetitle and subtitle to view.
        view()->share(['pageTitle' => 'Transaction', 'subTitle' => 'Create Product Disposal' ]); 
        $typeingredients = Typeingredient::where('id', '<>', 1)->get();  //except the recipe ingredient root category.     
        return view('admin.productDisposal.create',  compact('typeingredients'));
    }
    
    public function getRecipeIngredientSupplierProducts($id){
        $items =SupplierStock::where('ingredient_id', $id)->get();
        //define a collection.
        $stock_items = collect(); 
        foreach($items as $supplier_stock){            
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
                'product_qty'               => $supplier_stock->product_qty, //supplier stock product actual quantity.
                'unit_cost'                 => $supplier_stock->unit_cost,
                'total_qty'                 => $supplier_stock->total_qty,
                'total_cost'                => $supplier_stock->total_cost,
                'ingredient_unit'           => $supplier_stock->ingredient->measurement_unit,
                'recipe_stock_qty'          => '',                
                ]);                 
        }
        return response()->json(['success' => true, 'products' => $stock_items]);
    }

    public function getRecipeIngredientQty($product_id, $disposal_qty){

            $supplier_stock = SupplierStock::find($product_id);
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

            $recipe_stock_qty = $hasDifferProductUnit && $productUnit === $recipe_stock_unit  ? round(floatval($productQty * ($disposal_qty)), 2) 
            : ($hasDifferProductUnit && $productUnit !== $recipe_stock_unit && $recipe_stock_unit == $small_measurement_unit ? round(floatval($productQty) * (floatval($disposal_qty)* floatval($conversion_unit)), 2) 
            : ($hasDifferProductUnit && $productUnit !== $recipe_stock_unit && $recipe_stock_small_unit == $productUnit ?  round(floatval($productQty) * (floatval($disposal_qty)/floatval($recipe_stock_unit_conversion)),2) 
            : (!$hasDifferProductUnit && $productUnit === $recipe_stock_unit ? round(floatval($disposal_qty),2)
            : (!$hasDifferProductUnit && $productUnit !== $recipe_stock_unit && $recipe_stock_unit == $small_measurement_unit ? round(((floatval($disposal_qty)) * floatval($conversion_unit)),2) 
            : (!$hasDifferProductUnit && $productUnit !== $recipe_stock_unit && $recipe_stock_small_unit == $productUnit ? round((floatval($disposal_qty)) / floatval($recipe_stock_unit_conversion),2): ''))))); 

            return response()->json(['success' => true, 'recipe_stock_qty' => $recipe_stock_qty]);

    }

    public function store(Request $request){
        /*
        * storing disposal products.
        */ 
        $product_lists = '';
        //Convert JSON String to PHP Array 
        $product_lists = json_decode($request->product_lists, true);
       
        //Before adding the product to disposal checking recipe stock product quantity is empty or not 
        foreach($product_lists as $product){
            $recipe_product_stock = Ingredient::find(SupplierStock::find($product['supplier_stock_id'])->ingredient_id)->total_quantity;
            if($recipe_product_stock < 0 || $recipe_product_stock < $product['recipe_stk_qty']){
                $this->setFlashMessage($product['name'] .' have corresponding recipe stock quantity is become less or empty', 'info');    
                $this->showFlashMessages();
                return redirect()->back();
            }
        }
        
        $ingredient_disposal = IngredientDisposal::create([            
            'admin_id'       => auth()->user()->id,
            'reason'         => $request->reason,
            'remarks'        => $request->remarks,
        ]);        

        foreach($product_lists as $product){
            $supplier_stock = SupplierStock::find($product['supplier_stock_id']);
            //creating a new instance of RequisitionIngredientList table
            $disposal_product = new DisposalIngredientList();
            $disposal_product->ingredient_disposal_id = $ingredient_disposal->id;
            $disposal_product->ingredient_id = $product['ingredient_id']; 
            $disposal_product->supplier_stock_id = $product['supplier_stock_id'];
            $disposal_product->name = $product['name'];
            $disposal_product->unit = $product['unit'];
            $disposal_product->unit_cost = $product['unit_cost'];
            $disposal_product->quantity = $product['quantity'];
            $disposal_product->stock = $product['stock'];
            $disposal_product->total = $product['total'];            
            $disposal_product->save();

            //updating the supplier product stock after deducting the product amount.
            $supplier_stock->total_qty -= $product['quantity'];
            $supplier_stock->total_cost -= $product['total'];
            $supplier_stock->save();
            //updating the recipe stock, Recipe cost and Recipe ingredients unit price update
            $this->RecipeStockUpdate($supplier_stock->ingredient_id, strtolower($product['recipe_unit']), $product['recipe_stk_qty'], $product['total']);
        }

        if($product_lists && $ingredient_disposal){
            // setting flash message using trait
            $this->setFlashMessage(' Supplier product is added to disposal list successfully', 'success');    
            $this->showFlashMessages(); 
            return redirect()->route('admin.product.disposal.index');
        }else{
            $this->setFlashMessage(' Error is occurred while adding product to disposal', 'error');    
            $this->showFlashMessages();
            return redirect()->back();
        }

    }


    public function RecipeStockUpdate($ingredient_id, $disposal_unit, $disposal_qty, $diposal_price){       
        // getting the ingredient details       
        $ingredient = Ingredient::find($ingredient_id);
        
        // Setting ingredient total quantity, total price, per unit cost price 
        $ingredient_total_quantity = $ingredient->total_quantity;
        $ingredient_total_price = $ingredient->total_price;
        $ingredient_smallest_unit_price = $ingredient->smallest_unit_price;

        $unit_conversion = 0;
        // now calculating stock ingredient quantity & unit conversion.
        if(strtolower($ingredient->measurement_unit) == $disposal_unit){
            $unit = Unit::where('measurement_unit', $disposal_unit)->first();            
            $unit_conversion = $unit->unit_conversion;
            $ingredient_total_quantity -= $disposal_qty; 
        }else{
            $unit = Unit::where('smallest_measurement_unit', $disposal_unit)->first();            
            $unit_conversion = $unit->unit_conversion; 
            $ingredient_total_quantity -= ($disposal_qty/$unit_conversion); 
        }
        
        //calculating total ingredient price 
        $ingredient_total_price -= $diposal_price;  
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
     *  Ajax Request
     */

    public function getProductDisposal(Request $request){

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
        $totalRecords = IngredientDisposal::select('count(*) as allcount')->count();
        $totalRecordswithFilter = IngredientDisposal::select('count(*) as allcount')->count();
        // Fetch records
        $records = IngredientDisposal::orderBy($columnName, $columnSortOrder)
            ->where('ingredient_disposals.created_at', 'like', '%' . explode(' ', $searchValue)[0] . '%')            
            ->orWhere('ingredient_disposals.reason', 'like', '%' . $searchValue . '%')
            ->select('ingredient_disposals.*')
            ->skip($start) 
            ->take($rowperpage)
            ->get();

        $data_arr = array();        
        foreach($records as $record){             
            $data_arr[] = array(
                "id"                           => $record->id,
                "created_at"                   => explode(' ', $record->created_at)[0],                
                "reason"                       => $record->reason,                       
                "action"                       => '<div class="btn-group" role="group" aria-label="Second group">                                                    
                                                    <a href="'. url("admin/product/disposal/pdf/{$record->id}"). '"
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

    public function generateDisposalPdf($id){
        $product_disposal = IngredientDisposal::find($id);
        $disposal_items = DisposalIngredientList::where('ingredient_disposal_id', $id)->get(); 
        $pdf = PDF::loadView('admin.report.productDisposal.pdf_disposal', compact('product_disposal','disposal_items'))->setPaper('a4', 'potrait');
        return $pdf->stream('pdf_supplier_product_disposal.pdf');       
    }

    
}
