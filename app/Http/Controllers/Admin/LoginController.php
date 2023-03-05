<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth; // we need to add this trait for authentication


class LoginController extends Controller
{
     /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

     /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::ADMIN;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Admin guest [the people who are not logged in as admin, will be redirecting to admin login.          
        $this->middleware('guest:admin')->except('logout');
    }

    public function showLoginForm(){        
        return view('admin.auth.login');
    }

    public function login(Request $request){
        // validate the form data.
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
        // attempt to log the user in dashboard.
        // we are using admin guard to filter admin users defined in config/auth.php
        // if the admin credentials(admin.auth.login) is matched with Admin model then it is remembered the truthy value
        if(Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password ], $request->get('remember'))){
            // if successful, then redirect to their intended location.
            // here intended method is used to redirect the page is requested by admin user after successful login. 
            return redirect()->intended(route('admin.dashboard'));
        } 
        // if unsuccessful, then redirect back to the login with the form data.
        else {            
            return redirect()->back()->withInput($request->only('email','remmember'));
        }
    }

     /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        return redirect()->route('admin.login');
    }
    
}
