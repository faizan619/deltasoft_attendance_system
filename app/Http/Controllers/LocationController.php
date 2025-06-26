<?php

namespace App\Http\Controllers;

use App\Models\Locations;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function viewPresentLocations(){
        $locs = Locations::get();
        return view('admin.addLocations',compact('locs'));
    }

    public function savePresentLocations(Request $request){

        $request->validate([
            'name' => 'required',
            'latitude' => 'required',
            'longitude' => 'required'
        ]); 
        // return $request;
        $saveLoc = new Locations();
        $saveLoc->name = $request->name;
        $saveLoc->latitude = $request->latitude;
        $saveLoc->longitude = $request->longitude;
        $saveLoc->save();
        return redirect()->route('viewPresentLocations')->with('success','Added '.$request->name.' Office');
    }
}
