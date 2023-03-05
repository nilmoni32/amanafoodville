<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\District;

class DistrictController extends Controller
{
    
    public function index(){
        // Attaching pagetitle and subtitle to view.
        view()->share(['pageTitle' => 'Districts', 'subTitle' => 'List of all districts' ]);
        $districts = District::all();
        return view('admin.areas.district', compact('districts'));
    }

    public function districtUpdate(Request $request){
       $district = District::find($request->id);
       $district->status = $request->status;
       $district->save();
       return response()->json(['success' => 'Data is updated successfully']);  
        
    }    

}
