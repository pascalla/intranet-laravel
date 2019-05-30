<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Office;

class OfficeHoursController extends Controller
{
    public function index(Request $request){
        $office = new Office;
        $office->api = $request->api;
        $office->lang = $request->lang;


        $officeHours = $office->getAll();
        if($officeHours == null){
          return response()->json(["ok" => false, "status" => 401, "statusText" => "Not Logged In"], 401);
        } else {
          return response()->json(["ok" => true, "status" => 200, "statusText" => "Successfully Grabbed Office Hours", "time" => now()->timestamp, "hours" => $officeHours], 200);
        }
    }
}
