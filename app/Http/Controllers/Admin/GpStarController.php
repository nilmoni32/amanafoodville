<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gpstardiscount; 
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Validator;
use App\Traits\FlashMessages; 

class GpStarController extends Controller
{
    use FlashMessages;

    public function index(){       
        // Attaching pagetitle and subtitle to view.
        view()->share(['pageTitle' => 'Mobile Operator Stars Discount', 'subTitle' => 'List of MO Stars']);
        $gpstars = Gpstardiscount::orderBy('created_at', 'asc')->get();        
        return view('admin.gpstardiscount.index', compact('gpstars')); 
    }

    public function create(){  
        // Attaching pagetitle and subtitle to view.
        view()->share(['pageTitle' => 'Mobile Operator Stars Discount', 'subTitle' => 'Add MO Star Info' ]);       
        return view('admin.gpstardiscount.create');  
    }

    public function store(Request $request){
        
        $this->validate($request,[
            'gp_star_name'         => 'required|string|max:255', 
            'discount_percent'     => 'required|digits_between:1,2', //if user input is a digit between 0 to 99
            'discount_upper_limit' => 'nullable|regex:/^\d+(\.\d{1,2})?$/',
            'discount_lower_limit' => 'nullable|regex:/^\d+(\.\d{1,2})?$/',
        ]);

        $gpstar = Gpstardiscount::create([
            'gp_star_name' =>   $request->gp_star_name,            
            'discount_percent' =>  $request->discount_percent, 
            'discount_upper_limit' =>  $request->discount_upper_limit, 
            'discount_lower_limit' =>  $request->discount_lower_limit, 
        ]);
        
        if($gpstar){         

            // setting flash message using trait
            $this->setFlashMessage('Mobile Star discount details is added successfully', 'success');    
            $this->showFlashMessages();
            return redirect()->route('admin.gpstar.index');
        }else{
            return $this->responseRedirectBack(' Error occurred while adding MO star discount details.' ,'error', false, false);    
        }
    }


    public function edit($id){
        $gpstar = Gpstardiscount::find($id);
        view()->share(['pageTitle' => 'Mobile Operator Stars Discount', 'subTitle' => 'Edit the MO Star Info' ]);
        return view('admin.gpstardiscount.edit', compact('gpstar'));   
    }

    public function update(Request $request){

        $this->validate($request,[
            'gp_star_name'         => 'required|string|max:255', 
            'discount_percent'     => 'required|digits_between:1,2', //if user input is a digit between 0 to 99
            'discount_upper_limit' => 'nullable|regex:/^\d+(\.\d{1,2})?$/',
            'discount_lower_limit' => 'nullable|regex:/^\d+(\.\d{1,2})?$/',
        ]);
        
        $gpstar = Gpstardiscount::find($request->id);            
        $gpstar->gp_star_name = $request->gp_star_name;
        $gpstar->status = $request->status;
        $gpstar->discount_percent = $request->discount_percent;
        $gpstar->discount_lower_limit = $request->discount_lower_limit;
        $gpstar->discount_upper_limit = $request->discount_upper_limit;
        $gpstar->save();

        // setting flash message using trait
        $this->setFlashMessage(' Mobile Star discount details is updated successfully', 'success');    
        $this->showFlashMessages();
        return redirect()->route('admin.gpstar.index');
    }

    public function delete($id){        
        $gpstar = Gpstardiscount::find($id); 
        $gpstar->delete();
        if(!$gpstar){
            return  $this->responseRedirectBack(' Error occurred while deleting the Mobile Star discount.', 'error', true, true);
         }
        $this->setFlashMessage(' The Mobile Star discount is deleted successfully', 'success');    
        $this->showFlashMessages();
        return redirect()->route('admin.gpstar.index');
    }


}
