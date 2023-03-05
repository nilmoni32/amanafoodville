<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use App\Models\User;
use App\Mail\OtpMailable;
use App\Sms\SendCode;


class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function postforgot(Request $request){

        $phoneOrEmail=$request->email_or_phone;        

        if(is_numeric($phoneOrEmail)){
            $validated = request()->validate([
                // 'email' => 'required|email|exists:users', 
                'email_or_phone' =>  'required|regex:/(01)[3-9]{1}(\d){8}/|max:11|exists:users,phone_number',  
                ]);
        }else{            
            $validated = request()->validate([
                 'email_or_phone' => 'required|email|exists:users,email', 
               // 'phone_number' =>  'required|regex:/(01)[3-9]{1}(\d){8}/|max:11',  
                ]);
        } 
       
        if(is_numeric($phoneOrEmail)){
             //finding the user with phone_number verification code.
            $user = User::where('phone_number', $validated['email_or_phone'])->first();
        }else{
             //finding the user with email verification code.
            $user = User::where('email', $validated['email_or_phone'])->first();
        }       


        if(!$user->is_token_verified){

            if(session()->has('error') && session()->get('error') !== ''){
                session()->flash('error', '');
            }
            return redirect()->back()->with('error', 'User account has not been activated. Please activate first.');
        }
        else{
            //setting the eamil token.
            $user->update([ 
                 'verify_token' => mt_rand(10000,99999),                  
                ]);
            if(session()->has('success') && session()->get('success') !== ''){
                session()->flash('success', '');
            }

            if(is_numeric($phoneOrEmail)){
                // sending token to phone            
                $response = SendCode::sendCode($user->phone_number, $user->verify_token);            
                return redirect('verifytoken')->with('success', 'Please check your Phone to get the OTP to reset your password');
            }else{
                //sending mail to mailable class OtpMailable for the user with it's email id
                \Mail::to($user->email)->send(new OtpMailable($user)); 
                return redirect('verifytoken')->with('success', 'Please check your email to get the OTP to reset your password');
            }

            
        }
        
    }
}
