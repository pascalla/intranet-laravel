<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use App\Notification;

class NotificationController extends Controller
{
    public function notify(Request $request){
      $notifications = collect(json_decode(Storage::get('notifications.json')));
      $notification = new Notification;
      $notification->recipient = $request->recipient;
      $notification->title = $request->title;
      $notification->body = $request->body;
      $notification->timestamp = $request->timestamp;
      $notifications->push($notification);
      Storage::put('notifications.json', json_encode($notifications));
    }
}
