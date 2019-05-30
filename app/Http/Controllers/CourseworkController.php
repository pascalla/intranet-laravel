<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Coursework;

class CourseworkController extends Controller
{
    public function getYear(Request $request){
      $coursework = new Coursework;
      $coursework->api = $request->api;
      $coursework->lang = $request->lang;

      $courseworks = $coursework->getAll();
      if($courseworks == null){
        return response()->json(["ok" => false, "status" => 401, "statusText" => "Not Logged In"], 401);
      } else {
        return response()->json(["ok" => true, "status" => 200, "statusText" => "Successfully Grabbed Coursework", "time" => now()->timestamp, "curCoursework" => $courseworks->get(0), "oldCoursework" => $courseworks->get(1)], 200);
      }
    }
}
