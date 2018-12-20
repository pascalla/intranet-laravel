<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Timetable;


class TimetableController extends Controller
{
    // Get timetable by week
    //
    // return Timetable Object
    public function getWeek(Request $request){
      $timetable = new Timetable;
      $timetable->api = $request->api;

      $timetableWeek = $timetable->getWeek();

      return response()->json(["status" => "success", "timetable" => $timetableWeek]);
    }
}
