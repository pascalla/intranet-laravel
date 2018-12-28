<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Coursework;

class CourseworkController extends Controller
{
    public function getYear(Request $request){
      $coursework = new Coursework;
      $coursework->api = $request->api;

      $courseworks = $coursework->getAll();
      if($courseworks == null){
        return response()->json(["ok" => false, "status" => 401, "statusText" => "Not Logged In"], 401);
      } else {
        return response()->json(["ok" => true, "status" => 200, "statusText" => "Successfully Grabbed Coursework", "time" => now()->timestamp, "coursework" => $courseworks], 200);
      }
    }
}
