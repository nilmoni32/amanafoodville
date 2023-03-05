<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    //Updating the user password.
    public function update(Request $request, $token){

        $validated = request()->validate([
            'password' => 'required|min:8|confirmed',          
         ]);        
         // token is decrypted.
         $token = Crypt::decryptString($token);

        //finding the user with email verification code.
        $user = User::where('verify_token', $token)->first();      
        //updating user password.
        $user->update([
            'password' => Hash::make($request->password),
            'verify_token' => "",
        ]);
        
        return redirect('login')->with('success', 'The password is updated successfully.');
    }


}
