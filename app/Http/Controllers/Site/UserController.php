<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Rules\Lowercase;
use Auth;
use App\Models\Order;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
   
   public function dashboard(){
    $user = Auth::user();   
    return view('site.pages.user.dashboard', compact('user'));
   }

   public function updateProfile(Request $request){

    $user = Auth::user();

    $this->validate($request,[  
        'name' => 'required|string|max:40',
        'address' =>  'required|string|max:191',                      
    ]);

    if($user->is_token_verified){      
        $user->name = $request->name;
        $user->address = $request->address;
        $user->save();
        session()->flash('success_msg', 'Your profile information has been updated');
        return redirect()->back();       
    }

   }

   public function changePassword(Request $request){

    $user = Auth::user();
    $validated = request()->validate([
        'password' => 'required|min:8|confirmed',  
        'old_password' => 'required|string|min:8',        
     ]);   
 
     if(Hash::check($request->old_password, $user->password )){
        //updating user password.
        $user->update([
            'password' => Hash::make($request->password),            
        ]);
       
        session()->flash('success_msg', 'Your password is modified.');
        return redirect()->back();

     }else{ 
        session()->flash('err_msg', 'Your old password is incorrect.');
        return redirect()->back();
     }
    
   }

   public function paymenyHistory($year){    
    //find the order by date of the respective user.    
    $order = Order::where('user_id', auth()->user()->id)->where('order_date', 'like', $year.'%')->orderBy('created_at', 'desc')->get();   
    return json_encode($order);
   }

}
