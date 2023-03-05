<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Ingredient;
use App\Models\Supplier;
use App\Models\ReceiveFromSupplier;
use App\Models\Unit; 
use App\Models\SupplierStock;
use App\Models\RequisitionToSupplier;
use App\Models\RequisitionIngredientList;
use Illuminate\Http\Request;
use App\Traits\FlashMessages;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController;
use PDF;


class SupplierRequisitionController extends Controller
{
    //
    use FlashMessages;
    public function index(){
        // Attaching pagetitle and subtitle to view.
        view()->share(['pageTitle' => 'Requisition of Products to Supplier', 'subTitle' => 'List of all Supplier Requisitions' ]);        
        return view('admin.supplierRequisition.index');
    }

    /**
     *  Ajax Request to fetch Supplier requisition related data
     */
    public function getSupplierRequisition(Request $request){

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
        $totalRecords = RequisitionToSupplier::select('count(*) as allcount')->count();
        $totalRecordswithFilter = RequisitionToSupplier::select('count(*) as allcount')->where('supplier_id', 'like', '%' . $searchValue . '%')->count();
        // Fetch records
        $records = RequisitionToSupplier::orderBy($columnName, $columnSortOrder)
            ->where('requisition_to_suppliers.requisition_date', 'like', '%' . $searchValue . '%')            
            ->orWhere('requisition_to_suppliers.supplier_id', 'like', '%' . (Supplier::where('name', 'LIKE', '%' . $searchValue . '%')->first() ? Supplier::where('name', 'LIKE', '%' . $searchValue . '%')->first()->id : $searchValue) . '%')            
            ->orWhere('requisition_to_suppliers.total_quantity', 'like', '%' . $searchValue . '%')
            ->orWhere('requisition_to_suppliers.total_amount', 'like', '%' . $searchValue . '%')
            ->orWhere('requisition_to_suppliers.remarks', 'like', '%' . $searchValue . '%')            
            ->select('requisition_to_suppliers.*')
            ->skip($start) 
            ->take($rowperpage)
            ->get();

        $data_arr = array();
        //$sno = $start+1; 
        foreach($records as $record){ 
            $disabled = RequisitionToSupplier::find($record->id)->remarks !== NULL ? 'disabled' :'';
            $data_arr[] = array( 
                "id"                    => $record->id,
                "requisition_date"      => explode(' ', $record->requisition_date)[0],       // converting date string to array and get the date         
                "supplier_id"           => $record->supplier->name,
                "total_quantity"        => $record->total_quantity,
                "total_amount"          => round($record->total_amount,2),
                "remarks"               => $record->remarks,
                "action"                => '<div class="btn-group" role="group" aria-label="Second group">
                                                <a href="'. url("admin/supplier/requisition/edit/{$record->id}"). '"
                                                class="btn btn-sm btn-primary '. $disabled .'">
                                                <i class="fa fa-edit"></i></a>
                                                <a href="'. url("admin/supplier/requisition/pdf/{$record->id}"). '"
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
  

    public function create(){
        // Attaching pagetitle and subtitle to view.
        view()->share(['pageTitle' => 'Create Requisition', 'subTitle' => 'Requisition to a Supplier' ]);
        $suppliers = Supplier::all();
        return view('admin.supplierRequisition.create', compact('suppliers'));
    }

    public function getAllSupplierProducts($id){
        $items = SupplierStock::where('supplier_id', $id)->get();       
        return response()->json(['success'=>true, 'items'=>$items]);        
    }

    public function getProductUnit($id){
        $item = SupplierStock::find($id);      
        return response()->json(['success'=>true, 'item'=>$item]);     
    }

    public function getRecipeUnit($id){
        $recipeIngredientUnit = SupplierStock::find($id)->ingredient->measurement_unit;
        return response()->json(['success'=>true, 'recipeIngredientUnit'=>$recipeIngredientUnit]);
    }

    public function store(Request $request){
        
        $this->validate($request,[
            'requisition_date'     => 'required|date_format:"d-m-Y"',             
            'expected_delivery'    => 'required|date_format:"d-m-Y"',
            'purpose'              => 'nullable|string|max:191',       
            'customer_notes'       => 'nullable|string|max:191',    
            'total_quantity'       => 'required|numeric|gt:0',
            'total_amount'         => 'required|numeric|gt:0',
        ]);
		if($request->requisition_date > $request->expected_delivery){
            $this->setFlashMessage(' Requisition date can\'t be greater than expected delivery date', 'error');    
            $this->showFlashMessages();
            return redirect()->back();
        }
        //dd($request->all());
        /*
        * storing requisition all the product details to a supplier.
        */
        $product_lists = '';
        //Convert JSON String to PHP Array 
        $product_lists = json_decode($request->product_lists, true);

        //dd($product_lists);
        $requisition_to_supplier = RequisitionToSupplier::create([
            'supplier_id'           => $request->supplier_id,
            'admin_id'              => auth()->user()->id,
            'requisition_date'      => Carbon::createFromFormat('d-m-Y', $request->requisition_date)->format('Y-m-d'),
            'expected_delivery'     => Carbon::createFromFormat('d-m-Y', $request->expected_delivery)->format('Y-m-d'),
            'purpose'               => $request->purpose, 
            'remarks'               => $request->remarks,
            'total_quantity'        => $request->total_quantity,
            'total_amount'          => $request->total_amount,
        ]);

        foreach($product_lists as $product){
            //creating a new instance of RequisitionIngredientList table          
            $requisition_product = new RequisitionIngredientList();
            $requisition_product->requisition_to_supplier_id = $requisition_to_supplier->id;
            $requisition_product->supplier_stock_id = $product['supplier_stock_id'];
            $requisition_product->name = $product['name'];
            $requisition_product->unit = $product['unit'];
            $requisition_product->unit_cost = $product['unit_cost'];
            $requisition_product->quantity = $product['quantity'];
            $requisition_product->stock = $product['stock'];
            $requisition_product->total = $product['total'];
            $requisition_product->save();
        }

        if($product_lists && $requisition_to_supplier){
            // setting flash message using trait
            $this->setFlashMessage(' Supplier requisition is added successfully', 'success');    
            $this->showFlashMessages(); 
            return redirect()->route('admin.supplier.requisition.index');
        }else{
            $this->setFlashMessage(' Error occurred while creating a requisition to a supplier', 'error');    
            $this->showFlashMessages();
            return redirect()->back();
        }
    }

    public function edit($id){
        $supplier_requisition = RequisitionToSupplier::find($id);         
        $items = SupplierStock::where('supplier_id', $supplier_requisition->supplier_id)->get(); 
        //excluding certains columns (created_at, updated_at) while using eloquent       
        $requisition_items = RequisitionIngredientList::where('requisition_to_supplier_id', $id)->get()->makeHidden(['created_at','updated_at'])->toArray();  
        view()->share(['pageTitle' => 'Edit', 'subTitle' => 'Requisition to Supplier']);        
        return view('admin.supplierRequisition.edit', compact('supplier_requisition','requisition_items','items'));
    }

    public function generateRequisitionPdf($id){        
        $requisition = RequisitionToSupplier::find($id);
        $requisition_items = RequisitionIngredientList::where('requisition_to_supplier_id', $id)->get(); 
        $pdf = PDF::loadView('admin.report.requisition.pdf_requisition', compact('requisition','requisition_items'))
        ->setPaper('a4', 'potrait');
        return $pdf->stream('pdf_requisition_to_supplier.pdf');
    }

    public function update(Request $request){
        
        $this->validate($request,[
            'requisition_date'     => 'required|date_format:"d-m-Y"',             
            'expected_delivery'    => 'required|date_format:"d-m-Y"',
            'purpose'              => 'nullable|string|max:191',       
            'customer_notes'       => 'nullable|string|max:191',    
            'total_quantity'       => 'required|numeric|gt:0',
            'total_amount'         => 'required|numeric|gt:0',
        ]);

        $product_lists = '';
        $front_requisition_product_ids = []; 
        $back_requisition_products_ids = []; 
        // Convert JSON String to PHP Array 
        $product_lists = json_decode($request->product_lists, true); 

        if(!$product_lists){
            $this->setFlashMessage(' No Change has been made for this requisition', 'info');    
            $this->showFlashMessages(); 
            return redirect()->route('admin.supplier.requisition.index');
        }

        //updating the Requisition To Supplier
        $requisition_to_supplier = RequisitionToSupplier::find($request->supplier_requisition_id);

        if($requisition_to_supplier){
            $requisition_to_supplier->supplier_id = $request->supplier_id;
            $requisition_to_supplier->admin_id = auth()->user()->id;
            $requisition_to_supplier->requisition_date = Carbon::createFromFormat('d-m-Y', $request->requisition_date)->format('Y-m-d');
            $requisition_to_supplier->expected_delivery = Carbon::createFromFormat('d-m-Y', $request->expected_delivery)->format('Y-m-d');
            $requisition_to_supplier->purpose = $request->purpose;
            $requisition_to_supplier->remarks = $request->remarks;
            $requisition_to_supplier->total_quantity = $request->total_quantity;
            $requisition_to_supplier->total_amount = $request->total_amount;
            $requisition_to_supplier->save();
        }
        /*
        * updating requisition all the products to RequisitionIngredientList.
        */        

        foreach($product_lists as $product){
            // storing requisition all products Ids from the user.
            $front_requisition_product_ids[] = $product['id'];            
            // Get the stored all requisition product list from the database
            $requisition_stored_product_list = RequisitionIngredientList::where('requisition_to_supplier_id', $product['requisition_to_supplier_id'])->get();
        
            foreach($requisition_stored_product_list as $requisition_stored_product){                
                //if $product['id'] exists and it matched with requisition_stored_product_list id, then update the record
                if($product['id'] && $requisition_stored_product->id == $product['id']){                    
                    $requisition_stored_product->supplier_stock_id = $product['supplier_stock_id'];
                    $requisition_stored_product->name = $product['name'];
                    $requisition_stored_product->unit = $product['unit'];
                    $requisition_stored_product->unit_cost = $product['unit_cost'];
                    $requisition_stored_product->quantity = $product['quantity'];
                    $requisition_stored_product->stock = $product['stock'];
                    $requisition_stored_product->total = $product['total'];
                    $requisition_stored_product->save();
                }               
                // storing backend requisition all products ids.
                $back_requisition_products_ids[] = $requisition_stored_product->id;
            } 
             //creating a new instance of RequisitionIngredientList table 
            if(!$product['id']){                        
                $requisition_new_product = new RequisitionIngredientList();
                $requisition_new_product->requisition_to_supplier_id = $request->supplier_requisition_id;
                $requisition_new_product->supplier_stock_id = $product['supplier_stock_id'];
                $requisition_new_product->name = $product['name'];
                $requisition_new_product->unit = $product['unit'];
                $requisition_new_product->unit_cost = $product['unit_cost'];
                $requisition_new_product->quantity = $product['quantity'];
                $requisition_new_product->stock = $product['stock'];
                $requisition_new_product->total = $product['total'];
                $requisition_new_product->save();
            }
        }
        
        //deleting the requisition product record from the database which are not exists as user preference.
        // compare back_requisition_products with front_requisition_product and get the difference
        $del_product_ids = array_diff($back_requisition_products_ids, $front_requisition_product_ids);

        foreach($del_product_ids as $del_product_id){
            $del_product = RequisitionIngredientList::find($del_product_id);
            if($del_product){
                $del_product->delete();
            }
        }                      

        if($product_lists && $requisition_to_supplier){
            // setting flash message using trait
            $this->setFlashMessage(' Supplier requisition is updated successfully', 'success');    
            $this->showFlashMessages(); 
            return redirect()->route('admin.supplier.requisition.index');
        }else{
            $this->setFlashMessage(' Error occurred while updating the requisition to a supplier', 'error');    
            $this->showFlashMessages();
            return redirect()->back();
        }
    }

    

}
