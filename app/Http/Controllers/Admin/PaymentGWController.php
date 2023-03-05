<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Paymentgw;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Validator;
use App\Traits\FlashMessages; 

class PaymentGWController extends Controller
{
    use FlashMessages;

    public function index(){
        // Attaching pagetitle and subtitle to view.
        view()->share(['pageTitle' => 'Payment Gateways', 'subTitle' => 'List of all Payment GWs']);
        $paymentgws = Paymentgw::orderBy('created_at', 'asc')->get();        
        return view('admin.paymentgw.index', compact('paymentgws')); 
    }

    public function create(){  
        // Attaching pagetitle and subtitle to view.
        view()->share(['pageTitle' => 'Payment Gateways', 'subTitle' => 'Add a Payment Gateway' ]);       
        return view('admin.paymentgw.create');  
    }

    public function store(Request $request){
        
        $this->validate($request,[
            'bank_name'            => 'required|string|max:255', 
            'discount_percent'     => 'required|digits_between:1,2', //if user input is a digit between 0 to 99
            'discount_upper_limit' => 'nullable|regex:/^\d+(\.\d{1,2})?$/',
            'discount_lower_limit' => 'nullable|regex:/^\d+(\.\d{1,2})?$/',
        ]);

        $paymentgw = Paymentgw::create([
            'bank_name' =>   $request->bank_name,
            'bank_type' =>   $request->bank_type,
            'discount_percent' =>  $request->discount_percent, 
            'discount_upper_limit' =>  $request->discount_upper_limit, 
            'discount_lower_limit' =>  $request->discount_lower_limit, 
        ]);
        
        if($paymentgw){         

            // setting flash message using trait
            $this->setFlashMessage(' New Payment GateWay details is added successfully', 'success');    
            $this->showFlashMessages();
            return redirect()->route('admin.payment.gw.index');
        }else{
            return $this->responseRedirectBack(' Error occurred while adding payment gateway details.' ,'error', false, false);    
        }
    }

    public function edit($id){
        $paymentgw = Paymentgw::find($id);
        view()->share(['pageTitle' => 'Payment Gateways', 'subTitle' => 'Edit the Payment GW']);
        return view('admin.paymentgw.edit', compact('paymentgw'));   
    }

    public function update(Request $request){

        $this->validate($request,[
            'bank_name'            => 'required|string|max:255', 
            'discount_percent'     => 'required|digits_between:1,2', //if user input is a digit between 0 to 99
            'discount_upper_limit' => 'nullable|regex:/^\d+(\.\d{1,2})?$/',
            'discount_lower_limit' => 'nullable|regex:/^\d+(\.\d{1,2})?$/',
        ]);
        
        $paymentgw = Paymentgw::find($request->id);            
        $paymentgw->bank_name = $request->bank_name;
        $paymentgw->discount_percent = $request->discount_percent;
        $paymentgw->discount_lower_limit = $request->discount_lower_limit;
        $paymentgw->discount_upper_limit = $request->discount_upper_limit;
        $paymentgw->save();

        // setting flash message using trait
        $this->setFlashMessage(' Payment Gateway details is updated successfully', 'success');    
        $this->showFlashMessages();
        return redirect()->route('admin.payment.gw.index');
    }

    public function delete($id){        
        $paymentgw = Paymentgw::find($id); 
        $paymentgw->delete();
        if(!$paymentgw){
            return  $this->responseRedirectBack(' Error occurred while deleting the Payment GW.', 'error', true, true);
         }
        $this->setFlashMessage(' The Payment GW is deleted successfully', 'success');    
        $this->showFlashMessages();
        return redirect()->route('admin.payment.gw.index');
    }

}
