<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Coursework;
use App\Timetable;
use Carbon\Carbon;
use App\Notification;
use Storage;

class DashboardController extends Controller
{
    public function index(Request $request){
      $coursework = new Coursework;
      $coursework->api = $request->api;
      $coursework->lang = $request->lang;
      $courseworks = $coursework->getAll();

      $timetable = new Timetable;
      $timetable->api = $request->api;
      $date = Carbon::now()->startOfWeek();

      $timetableWeek = $timetable->getWeek($date);

      return response()->json(["status" => "success", "timetable" => $timetableWeek, "coursework" => $courseworks[0], "updated" => Carbon::now()->timestamp]);
    }

    public function notify(){
      $notifications = collect(json_decode(Storage::get('notifications.json')));
      foreach($notifications as $key => &$notification){
        if($notification->timestamp < time()){
          $recipients = array($notification->recipient);
          fcm()
            ->to($recipients) // $recipients must an array
            ->data([
                'title' => $notification->title,
                'body' => $notification->body
            ])
            ->send();

            $notifications->forget($key);
        }
      }

      Storage::put('notifications.json', json_encode($notifications));


    }
}
