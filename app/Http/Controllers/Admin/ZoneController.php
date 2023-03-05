<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\District;
use App\Models\Zone;

class ZoneController extends Controller
{
    public function index(){
        // Attaching pagetitle and subtitle to view.
        view()->share(['pageTitle' => 'Areas', 'subTitle' => 'List of Areas' ]);
        $districts = District::where('status', 1)->get();
        return view('admin.areas.zone', compact('districts'));
    }

    public function getZones($id){       
        // $zones = Zone::where('district_id', $id)->pluck("name","status");
        view()->share(['pageTitle' => 'Areas', 'subTitle' => 'List of Areas' ]);
        $districts = District::where('status', 1)->get();
        $zones = Zone::where('district_id', $id)->get();        
        return view('admin.areas.zoneupdate', compact('districts', 'zones'));        
    }

    public function zoneUpdate(Request $request){
        $zone = Zone::find($request->id);
        $zone->status = $request->status;
        $zone->save();
        return response()->json(['success' => 'Data is updated successfully']);           
     }
}
