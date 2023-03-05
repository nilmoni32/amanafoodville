<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\ReservationMail;
use App\Mail\ContactMail;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
      public function mailReservation(Request $request){
          
        // $this->validate($request,[  
        //     'fname' => 'required|string|max:40',
        //     'lname' => 'required|string|max:40',
        //     'email' => 'required|string|email|max:100,', 
        //     'mobile' =>  'required|regex:/(01)[3-9]{1}(\d){8}/|max:13',       
        //     'appointment_dt' => 'required | date_format:"d-m-Y H:i a"', 
        //     'persons' => 'required|digits_between:0,9',
        // ]);

        //$user = $request->except('_token'); // all user data except token.        
        Mail::to(config('settings.default_email_address'))->send(new ReservationMail($request->except('_token')));
        session()->flash('success', 'Your reservation request has been sent successfully.');

        return redirect()->back();

    }


    public function contact(Request $request){

        Mail::to(config('settings.default_email_address'))->send(new ContactMail($request->except('_token')));
        session()->flash('success', 'Thank you for your enquiry about our Restaurant & Party Center.');
        return redirect()->back();
    }
}
