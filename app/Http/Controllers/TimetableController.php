<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Timetable;
use Carbon\Carbon;


class TimetableController extends Controller
{
    // Get timetable by week
    //
    // return Timetable Object
    public function getWeek(Request $request){

$timetable = new Timetable;
$timetable->api = $request->api;
$date = $request->date;

if($date != null) {
  $date = Carbon::createFromTimestamp($date)->startOfWeek();
} else {
  $date = Carbon::now()->startOfWeek();
}

      $timetableWeek = $timetable->getWeek($date);

      return response()->json(["status" => "success", "timetable" => $timetableWeek, "starting" => $date->timestamp, "updated" => Carbon::now()->timestamp]);
    }
}
