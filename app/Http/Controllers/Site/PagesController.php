<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use GuzzleHttp\Client;

class PagesController extends Controller
{
    /**
     * viewing the homepage of funville
     */
    public function index(){
        
        return view('site.pages.homepage');
    }

    public function about(){
  
        return view('site.pages.about');
        
    }

    public function reservation(){

        return view('site.pages.reservation');
    }

    public function contact(){

        return view('site.pages.contact');
    }
    
    public function pagenotfound(){
      return view('errors.404');        
    }


}
