<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use Storage;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
      $schedule->call(function () {
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
      })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
