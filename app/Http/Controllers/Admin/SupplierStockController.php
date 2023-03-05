<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Ingredient;
use App\Models\Supplier;
use App\Models\Unit; 
use App\Models\Typeingredient;
use App\Models\SupplierStock;
use Illuminate\Http\Request;
use App\Traits\FlashMessages;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController;
use Illuminate\Validation\Rule;



class SupplierStockController extends Controller
{
    use FlashMessages;
    public function index(){
        // Attaching pagetitle and subtitle to view.
        view()->share(['pageTitle' => 'Supplier Stock Products', 'subTitle' => 'List of all Supplier Products' ]);
        //$supplier_products = SupplierStock::orderBy('created_at', 'desc')->get();
        return view('admin.supplierStock.index');
    }

    /**
     *  Ajax Request to fetch Supplier stock data
     */
    public function getSupplierProducts(Request $request){

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
        $totalRecords = SupplierStock::select('count(*) as allcount')->count();
        $totalRecordswithFilter = SupplierStock::select('count(*) as allcount')->where('supplier_product_name', 'like', '%' . $searchValue . '%')->count();

        // Fetch records
        $records = SupplierStock::orderBy($columnName, $columnSortOrder)
            ->where('supplier_stocks.supplier_product_name', 'like', '%' . $searchValue  . '%')            
            ->orWhere('supplier_stocks.supplier_id', 'like', '%' . (Supplier::where('name', 'LIKE', '%' . $searchValue . '%')->first() ? Supplier::where('name', 'LIKE', '%' . $searchValue . '%')->first()->id : $searchValue) . '%')
            ->orWhere('supplier_stocks.ingredient_id', 'like', '%' . (Ingredient::where('name', 'LIKE', '%' . $searchValue . '%')->first() ? Ingredient::where('name', 'LIKE', '%' . $searchValue . '%')->first()->id : $searchValue) . '%')           
            ->orWhere('supplier_stocks.measurement_unit', 'like', '%' . $searchValue . '%')
            ->orWhere('supplier_stocks.total_qty', 'like', '%' . $searchValue . '%')
            ->orWhere('supplier_stocks.total_cost', 'like', '%' . $searchValue . '%')
            ->orWhere('supplier_stocks.unit_cost', 'like', '%' . $searchValue . '%')
            ->select('supplier_stocks.*')
            ->skip($start) 
            ->take($rowperpage)
            ->get();

        $data_arr = array();
        //$sno = $start+1; 
        foreach($records as $record){ 

            $data_arr[] = array( 
                "id"                    => $record->id,
                "supplier_product_name" => $record->supplier_product_name,                
                "supplier_id"           => $record->supplier->name, 
                "ingredient_id"         => $record->ingredient->name, 
                "measurement_unit"      => $record->measurement_unit,
                "total_qty"             => $record->total_qty,
                "total_cost"            => $record->total_cost,
                "unit_cost"             => $record->unit_cost,
                "action"                => '<div class="btn-group" role="group" aria-label="Second group">
                                                <a href="'. url("admin/supplier/stock/edit/{$record->id}"). '"
                                                class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                            </div>',
            ); 

            //echo route('admin.supplier.stock.edit', {$post->id});
            // <a href="{{ route("admin.supplier.stock.delete",'. $record->id .') }}"
            //         class="btn btn-sm btn-danger delete-confirm"><i class="fa fa-trash"></i></a>


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

    public function create(){
        // Attaching pagetitle and subtitle to view.
        view()->share(['pageTitle' => 'Ingredients', 'subTitle' => 'Add ingredient' ]);
        $suppliers = Supplier::all();   
        //$ingredients = Ingredient::all(); //except the root category.
        $typeingredients = Typeingredient::where('id', '<>', 1)->get(); 
        return view('admin.supplierStock.create', compact('suppliers', 'typeingredients'));
    }

    public function getUnitfromIngredientId($id){
        $measurement_unit = Ingredient::find($id)->measurement_unit;       
        return response()->json(['success'=>true, 'unit'=>$measurement_unit]);
    }

    public function getIngredientsByType($id){
        $ingredients = Ingredient::where('typeingredient_id', $id)->get();
        return response()->json(['success'=>true, 'unit'=>$ingredients]);
    }

     /**
     * Save the ingredient
     * @param Request $request  
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request){        
        $validated = $request->validate([                        
            'ingredient_id'         => 'required|numeric',
            'typeingredient_id'     => 'required|numeric',
            'supplier_product_name' => 'required|string|max:191',
            'measurement_unit'      => 'required|string', 
            'unit_cost'             => 'required|numeric',
            'supplier_id'           => 'required|numeric',
            'product_unit'          => 'nullable|regex:/^[a-zA-Z]+$/u|max:191',
            'product_qty'           => 'nullable|numeric',
            // 'supplier_id'           => ['required', Rule::unique('supplier_stocks')->where(function ($query) use($request) {
            //                                     return $query->where('supplier_id', $request->supplier_id)
            //                                                  ->where('ingredient_id', $request->ingredient_id);
            //                             }),],
                                       
                                    ],
                                    [ 
            'unit_cost.required'   => 'The Product Cost Price should be set', 
            'supplier_id.required' => 'Supplier is required',  
            // 'supplier_id.unique'   => 'This Supplier has already chosen for this ingredient',         
                                    ]);

        $unique_stock_product = SupplierStock::where('supplier_product_name', 'LIKE', '%'. $request->supplier_product_name .'%')->first();
        //dd($unique_stock_product);

        if($unique_stock_product){
            $this->setFlashMessage(' Error the same supplier product is already added', 'error');    
            $this->showFlashMessages();
            return redirect()->back();
        }
                
        $supplier_stock = SupplierStock::create([
            'supplier_id'               => $request->supplier_id,
            'typeingredient_id'         => $request->typeingredient_id,
            'ingredient_id'             => $request->ingredient_id,
            'supplier_product_name'     => $request->supplier_product_name,
            'measurement_unit'          => $request->measurement_unit, 
            'unit_cost'                 => $request->unit_cost,
            'has_differ_product_unit'   => $request->product_unit ? 1 : 0,
            'product_unit'              => $request->product_unit,
            'product_qty'               => $request->product_qty,
        ]);
        
        
        if($supplier_stock){
            // setting flash message using trait
            $this->setFlashMessage(' Supplier stock product is added successfully', 'success');    
            $this->showFlashMessages(); 
            return redirect()->route('admin.supplier.stock.index');
        }else{
            $this->setFlashMessage(' Error occurred while adding a stock product', 'error');    
            $this->showFlashMessages();
            return redirect()->back();
        }
    }

    public function edit($id){    
        $supplier_stock = SupplierStock::find($id);  
        $suppliers = Supplier::all();   
        $ingredients = Ingredient::where('typeingredient_id', $supplier_stock->typeingredient_id)->get();
        $typeingredients = Typeingredient::where('id', '<>', 1)->get();

        view()->share(['pageTitle' => 'Supplier Stock Product', 'subTitle' => 'Editing the Stock Product '.$supplier_stock->supplier_product_name]);        
        return view('admin.supplierStock.edit', compact('suppliers','supplier_stock','typeingredients', 'ingredients' ));
    }


    /**
     * Update the ingredient
     * @param Request $request  
     * @throws \Illuminate\Validation\ValidationException
     */

    public function update(Request $request){

        $validated = $request->validate([
            'ingredient_id'         => 'required|numeric',
            'typeingredient_id'     => 'required|numeric',
            'supplier_product_name' => 'required|string|max:191',
            'measurement_unit'      => 'required|string', 
            'unit_cost'             => 'required|numeric', 
            'supplier_id'           => 'required|numeric',
            'product_unit'          => 'nullable|string',
            'product_qty'           => 'nullable|numeric',
            // 'supplier_id'           => ['required', Rule::unique('supplier_stocks')->where(function ($query) use($request) {
            //                             return $query->where('supplier_id', $request->supplier_id)
            //                             ->where('ingredient_id', $request->ingredient_id);
            //                             })->ignore($request->id),],
       
                                        ],
                                        [ 
            'unit_cost.required'   => 'The Product Cost Price should be set', 
            'supplier_id.required' => 'Supplier is required',
            // 'supplier_id.unique'   => 'This Supplier has already chosen for this ingredient',
        ]); 
        
        
        $supplierStock= SupplierStock::find($request->id);
        // updating the supplier stock data.
        $supplierStock->supplier_id = $request->supplier_id;
        $supplierStock->ingredient_id = $request->ingredient_id;
        $supplierStock->typeingredient_id = $request->typeingredient_id;
        $supplierStock->supplier_product_name =  $request->supplier_product_name; 
        $supplierStock->measurement_unit =  $request->measurement_unit;
        $supplierStock->unit_cost = $request->unit_cost;
        $supplierStock->has_differ_product_unit = $request->product_unit ? 1 : 0;
        $supplierStock->product_unit = $request->product_unit;
        $supplierStock->product_qty = $request->product_qty;

        // if(Ingredient::where('id', $request->ingredient_id)->first()->measurement_unit == $request->measurement_unit ||
        //     Ingredient::where('id', $request->ingredient_id)->first()->smallest_unit == $request->measurement_unit){
        //         $supplierStock->measurement_unit =  $request->measurement_unit;
        // }else{
        //     $this->setFlashMessage(' Supplier stock measurement unit is mismatched with recipe measurement unit', 'error');    
        //     $this->showFlashMessages(); 
        //     return redirect()->back();
        // }

        $supplierStock->save();

        if($supplierStock){ 
            // setting flash message using trait
            $this->setFlashMessage(' Supplier Stock is updated successfully', 'success');    
            $this->showFlashMessages();
            return redirect()->route('admin.supplier.stock.index');
        }else{
            $this->setFlashMessage(' Error occurred while updating the Stock Product', 'error');    
            $this->showFlashMessages(); 
            return redirect()->back();
        }

    }



}
