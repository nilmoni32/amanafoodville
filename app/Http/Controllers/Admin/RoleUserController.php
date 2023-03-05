<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\Admin;
use App\Models\Userlog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController;
use App\Models\Ordersale;
use App\Models\Buffetorder;

class RoleUserController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        // Attaching pagetitle and subtitle to view.
        view()->share(['pageTitle' => 'Funville Users', 'subTitle' => 'List of Funville users and roles' ]);
        // getting all admin users
        $admins = Admin::all();
        return view('admin.user.index', compact('admins'));
    }    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {  
        // finding the current admin user.
         $admin = Admin::where('id', $id)->first();             
         // getting the logged user
         $logged_user = auth()->user();       
         //Checking logged admin user 
         if($logged_user <> $admin ){
        // Attaching pagetitle and subtitle to view.
	        view()->share(['pageTitle' => 'Funville Users', 'subTitle' => 'List of Funville users and roles' ]);        
        	$admin = Admin::where('id', $id)->first();
	        return view('admin.user.role', compact('admin'));
	  }
	  else{

            // setting flash message using trait
            $this->setFlashMessage(' Logged Admin User account can\'t be edited', 'info');    
            $this->showFlashMessages();
            return redirect()->route('admin.users.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {           
        // finding the admin user 
        $admin = Admin::where('id', $request->id)->first();
        // new roles are updated at pivot table using sync that accepts an array roles[]
        $admin->roles()->sync($request->roles);

        $admin->name = $request->name;
        $admin->email = $request->email;

        if($admin->save()){

            //saving log for the changing role of a user.            
            Userlog::user_role("Role Change","User account '". $request->name. "' new role '". 
            $admin->roles()->pluck('name')->first()."' is assigned by ".auth()->user()->name);

            // setting flash message using trait
            $this->setFlashMessage(' User account is updated successfully', 'success');    
            $this->showFlashMessages();
             // getting all admin users
             $admins = Admin::all();
            // Attaching pagetitle and subtitle to view.
            view()->share(['pageTitle' => 'Funville Users', 'subTitle' => 'List of Funville users and roles' ]); 
            return view('admin.user.index', compact('admins'));

        }else{
            return $this->responseRedirectBack(' There was an error while updating the user account' ,'error', false, false); 
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //finding the user
        $admin = Admin::where('id', $id)->first();

        $admin_user_have_kot_sales = Ordersale::where('admin_id', $admin->id)->first();
        $admin_user_have_buffet_sales = Buffetorder::where('admin_id', $admin->id)->first();
        if($admin_user_have_kot_sales || $admin_user_have_buffet_sales ){

            $this->setFlashMessage(' This Admin User account can\'t be deleted as user have sales record.', 'info');    
            $this->showFlashMessages();
            return redirect()->route('admin.users.index');
        }

        // getting the logged user
        $logged_user = auth()->user();       
        //Checking logged admin user or not
        if($logged_user <> $admin ){
        	
            //before deleting the user we will log deleted account details.
            //getting the role
            $del_role = $admin->roles()->pluck('name')->first();
            //Account name
            $del_name = $admin->name;

            // Detach all roles from the user...
        	$admin->roles()->detach();

        	//delete the user.       
        	if($admin->delete()){

                //saving log for deleting of a user account.            
                Userlog::user_role("User Delete","User account '". $del_name. "' with role '". 
                $del_role."' is deleted by ".auth()->user()->name);

             	// setting flash message using trait
             	$this->setFlashMessage(' User account is deleted successfully', 'success');    
             	$this->showFlashMessages();
		        return redirect()->route('admin.users.index');
              	// getting all admin users
              	//$admins = Admin::all();
             	// Attaching pagetitle and subtitle to view.
             	//view()->share(['pageTitle' => 'Funville Users', 'subTitle' => 'List of Funville users and roles' ]); 
             	//return view('admin.user.index', compact('admins'));

        	}else{
            	return $this->responseRedirectBack(' There was an error while deleting the user account' ,'error', false, false); 
        	}
	}else{

            // setting flash message using trait
            $this->setFlashMessage(' Logged Admin User account can\'t be deleted', 'info');    
            $this->showFlashMessages();
            return redirect()->route('admin.users.index');
        }

    }
}
