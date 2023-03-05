<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Http\Controllers\BaseController;
use App\Traits\FlashMessages; 

class ServiceController extends Controller
{
    use FlashMessages;

    public function index(){
        // Attaching pagetitle and subtitle to view.
        view()->share(['pageTitle' => 'Funville Services', 'subTitle' => 'List of Funville Services' ]);
        // getting all services
        $services = Service::all();
        return view('admin.services.index', compact('services'));
    }

    public function create(){
        // Attaching pagetitle and subtitle to view.
        view()->share(['pageTitle' => 'Funville Services', 'subTitle' => 'Add Funville Services' ]);       
        return view('admin.services.create');
    }

    public function store(Request $request){
        $this->validate($request,[
            'title' => 'required|string|max:40',  
            'description' =>  'required|string|max:191',         
            'icon'=> 'required',         
        ]);   

        $service = Service::create([
            'title' => $request->title,
            'icon' => $request->icon,
            'description' => $request->description,                           
        ]);

        if($service){
            // setting flash message using trait
            $this->setFlashMessage(' Service is created successfully', 'success');    
            $this->showFlashMessages(); 
            // Attaching pagetitle and subtitle to view.
            view()->share(['pageTitle' => 'Funville Services', 'subTitle' => 'List of Funville Services' ]);
            // getting all services
            $services = Service::all();
            return view('admin.services.index', compact('services'));
        }else{
            return $this->responseRedirectBack('There was an error while creating the services' ,'error', false, false); 
        }
  
    }

    public function edit($id){
         // Attaching pagetitle and subtitle to view.
         view()->share(['pageTitle' => 'Funville Services', 'subTitle' => 'List of Funville Services' ]);        
         $service = Service::where('id', $id)->first();
         return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request){
         // finding the admin user 
         $service = Service::where('id', $request->id)->first();
         $service->title = $request->title;
         $service->icon = $request->icon;
         $service->description = $request->description;
 
         if($service->save()){
             // setting flash message using trait
             $this->setFlashMessage(' Service is updated successfully', 'success');    
             $this->showFlashMessages();
              // getting all admin users
              $services = Service::all();
             // Attaching pagetitle and subtitle to view.
             view()->share(['pageTitle' => 'Funville Services', 'subTitle' => 'List of Funville Services' ]); 
             return view('admin.services.index', compact('services'));
 
         }else{
             return $this->responseRedirectBack(' There was an error while updating the services' ,'error', false, false); 
         }
    }

    public function delete($id){
        //finding the user
        $service = Service::where('id', $id)->first(); 
        //delete the user.       
        if($service->delete()){
             // setting flash message using trait
             $this->setFlashMessage(' Service is deleted successfully', 'success');    
             $this->showFlashMessages();
              // getting all admin users
              $services = Service::all();
             // Attaching pagetitle and subtitle to view.
             view()->share(['pageTitle' => 'Funville Services', 'subTitle' => 'List of Funville Services' ]);
             return view('admin.services.index', compact('services'));

        }else{
            return $this->responseRedirectBack(' There was an error while deleting the user account' ,'error', false, false); 
        }
    }
}
