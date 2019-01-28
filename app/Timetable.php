<?php

namespace App;

use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Database\Eloquent\Model;

use XPathSelector\Selector;
use XPathSelector\Exception\NodeNotFoundException;

class Timetable extends Model
{
    protected $casts = ['api' => 'string'];

    // Gets week of Timetable
    //
    // Returns collection of slots
    public function getWeek()
    {
      // Create new client
      $client = new \GuzzleHttp\Client();
      $jar = new \GuzzleHttp\Cookie\CookieJar;

      // Setup collection
      $timetable = collect([]);

      // Setup cookie jar
      $jar->setCookie(new \GuzzleHttp\Cookie\SetCookie([
        'Domain' => 'science.swansea.ac.uk',
        'Name' => 'sessionid',
        'Value' => $this->api,
        'Path' => '/intranet/',
        'Max-Age' => "172800"
      ]));

      // make request with cookies
      $res = $client->request('GET', 'https://science.swansea.ac.uk/intranet/attendance/timetable/2019/02/04?', [
        'cookies' => $jar
      ]);

      //get response
      $body = $res->getBody();
      $xs = Selector::loadHTML($body);

      $weekStarting = $xs->find('//*[@id="currentweekspan"]')->innerHtml();
      $weekMonthYear =  explode(' ', $weekStarting);

      $weekNames = array();
      $weeks = $xs->findAll('//*[@id="timetable"]/thead/tr/th');
      foreach($weeks as $week){
        if($week->innerHtml() == ""){
          continue;
        }
        $weekNames[] = $week->innerHtml() . " " . $weekMonthYear[1];
      }


      // Parse the week into slots into an array
      $slots = $xs->findAll('//td');
      foreach ($slots as $slot) {
        // check if the slot actually has content
        $xs = Selector::loadHTML($slot->innerHtml());
        $slotExist = $xs->findOneOrNull('//div[@rel="tipsy"]') !== null;
        if($slotExist) {
          // get data attributes
          $hour = $xs->find('//@data-hour')->innerHtml();
          $day = $xs->find('//@data-day')->innerHtml();
          $location = $xs->find('//div[@class="lectureinfo room"]')->innerHtml();
          $module = $xs->find('//div/strong')->innerHtml();
          //span

          //create slot with data
          $timetableSlot = new Slot;
          $timetableSlot->day = $day;
          $timetableSlot->hour = $hour;
          $timetableSlot->location = $location;
          $timetableSlot->module = $module;

          // push it to collection
          $timetable->push($timetableSlot);
        }
      }

      $timetable->sortBy('hour')->sortBy('day');

      for($i = 0;$i <= 4;$i++){
        for($k = 9; $k <= 17;$k++){
          $weekTimetable[$i][$k] = array();
        }
      }

      $teachingWeek = array();

      for($i = 0;$i < 5;$i++){
        $time = strtotime($weekStarting . " +" . $i . " day");
        $date = date("D d M", $time);

        $teachingWeek[] = $date;
      }

      foreach($timetable as $slot){
        $weekTimetable[$slot->day][$slot->hour][] = $slot;
      }

      $data = array('week' => $teachingWeek, 'timetable' => $weekTimetable);

      return $data;
    }
}
