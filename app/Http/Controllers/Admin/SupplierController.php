<?php

namespace App\Http\Controllers\Admin;

use DateTime;
use Carbon\Carbon;
use App\Models\Ingredient;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Traits\FlashMessages;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController;

class SupplierController extends Controller
{
    use FlashMessages;
    public function index(){
        // Attaching pagetitle and subtitle to view.
        view()->share(['pageTitle' => 'Suppliers', 'subTitle' => 'List of all Suppliers' ]);
        $suppliers = Supplier::orderBy('created_at', 'desc')->get();
        return view('admin.supplier.index', compact('suppliers'));
    }

    public function create(){        
        // Attaching pagetitle and subtitle to view.
        view()->share(['pageTitle' => 'Suppliers', 'subTitle' => 'Add Supplier' ]);        
        return view('admin.supplier.create'); 
    }

    public function store(Request $request){

        $validated = $request->validate([
            'name'      => 'required|max:191',  
            'phone'     => 'required|regex:/(01)[3-9]{1}(\d){8}/|max:11|unique:suppliers,phone', 
            'address'   => 'required|max:191',
        ]);

        $supplier = Supplier::create([
            'name' =>   $request->name,
            'phone' => $request->phone,
            'address' =>  $request->address, 
            'instantPayment' =>  $request->instantPayment == 'yes' ? TRUE : FALSE, 
            'activeSupplier' =>  $request->activeSupplier == 'yes' ? TRUE : FALSE, 
        ]);
        
        if($supplier){  
            // setting flash message using trait
            $this->setFlashMessage(' New Supplier Account is created successfully', 'success');    
            $this->showFlashMessages();
            return redirect()->route('admin.supplier.index');
        }else{
            $this->setFlashMessage(' Error occurred while adding Supplier new account.', 'error');    // setting flash message using trait
            $this->showFlashMessages();                 // displaying flash messages using trait            
            return redirect()->back();             
        }
    }

    public function edit($id){
        $supplier = Supplier::find($id);
        view()->share(['pageTitle' => 'Suppliers', 'subTitle' => 'Edit the Supplier Account' ]);
        return view('admin.supplier.edit', compact('supplier'));   
    }


    public function update(Request $request){
        $validated = $request->validate([
            'name'      => 'required|max:191',  
            'phone'     => 'required|regex:/(01)[3-9]{1}(\d){8}/|max:11|unique:suppliers,phone,'.$request->id, //Validation unique on update 
            'address'   => 'required|max:191',
        ]);
        
        $supplier = Supplier::find($request->id);            
        $supplier->name = $request->name;
        $supplier->phone = $request->phone;
        $supplier->address = $request->address;
        $supplier->instantPayment = $request->instantPayment == 'yes' ? TRUE : FALSE;
        $supplier->activeSupplier = $request->activeSupplier == 'yes' ? TRUE : FALSE;        
        $supplier->save();

        // setting flash message using trait
        $this->setFlashMessage('Supplier details is updated successfully', 'success');    
        $this->showFlashMessages();
        return redirect()->route('admin.supplier.index');

    }

    public function delete($id){        
        $supplier = Supplier::find($id); 
        $supplier->delete();
        if(!$supplier){
            $this->setFlashMessage(' Error occurred while deleting the Supplier details.', 'error');    // setting flash message using trait
            $this->showFlashMessages();                 // displaying flash messages using trait            
            return redirect()->back();            
         }
        $this->setFlashMessage(' The Supplier Details is deleted successfully', 'success');    
        $this->showFlashMessages();
        return redirect()->route('admin.supplier.index');
    }
}
