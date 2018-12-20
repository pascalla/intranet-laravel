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
      $res = $client->request('GET', 'https://science.swansea.ac.uk/intranet/attendance/timetable/2018/12/03?', [
        'cookies' => $jar
      ]);

      //get response
      $body = $res->getBody();
      $xs = Selector::loadHTML($body);

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

      return $timetable->sortBy('hour')->sortBy('day');
    }
}
