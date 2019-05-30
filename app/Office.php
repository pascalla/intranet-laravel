<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use GuzzleHttp\Cookie\CookieJar;
use Carbon\Carbon;
use XPathSelector\Selector;
use XPathSelector\Exception\NodeNotFoundException;

class Office extends Model
{
  public function getAll(){
    $client = new \GuzzleHttp\Client();
    $jar = new \GuzzleHttp\Cookie\CookieJar;

    // Setup collection
    $officeHours  = collect([]);

    // Setup cookie jar
    $jar->setCookie(new \GuzzleHttp\Cookie\SetCookie([
      'Domain' => 'science.swansea.ac.uk',
      'Name' => 'sessionid',
      'Value' => $this->api,
      'Path' => '/intranet/',
      'Max-Age' => "172800"
    ]));

    // make request with cookies
    $res = $client->request('GET', 'https://science.swansea.ac.uk/intranet/staff_officehours/list?language=' . $this->lang, [
      'cookies' => $jar
    ]);

    if($this->lang == "en"){
      $dayText = "DAY:";
      $timeText = "TIME:";
      $notFound = "Currently does not have any Office Hours.";
    } else if($this->lang == "cy") {
      $dayText = "DYDD: Dydd";
      $timeText = "AMSER:";
      $notFound = "Nid oes unrhyw Oriau Swyddfa ar hyn o bryd.";
    } else {
      $dayText = "DAY:";
      $timeText = "TIME:";
      $notFound = "Currently does not have any Office Hours.";
    }

    //get response
    $body = $res->getBody();
    $xs = Selector::loadHTML($body);

    try {
      $elements = $xs->find('//*[@id="logout"]/a[2]');
    } catch(NodeNotFoundException $ex){
      return null;
    }

    $hours = $xs->findAll('//*[@id="pagecontent"]/table[1]//tr');
    foreach ($hours as $hour) {
      // skip if header
      $xz = Selector::loadHTML($hour->innerHtml());
      $titleExist = $xz->findOneOrNull('//th');
      if($titleExist != null){
        continue;
      }

      //dd($xz);
      // else lets get that data
      $name = $xz->find('//td[1]/a')->innerHtml();
      $times =  explode("<br>", preg_replace("/[^a-zA-Z0-9_.-\s -]/", "", $xz->find('//td[2]')->innerHtml()));
      $room = $xz->find('//td[3]')->innerHtml();
      $building = $xz->find('//td[4]')->innerHtml();
      $lastUpdated =  preg_replace("/[^a-zA-Z0-9_.-\s ]/", "", $xz->find('//td[5]')->innerHtml());

      foreach($times as &$time){
        $time = trim($time);
        if($time != "") {
          preg_match("/\b" . $dayText . "\s+\K\S+\b/", $time, $day);
          preg_match("/\b" . $timeText ."\s+(.*)$/", $time, $hours);
          $time = new Hours;
          if($this->lang == "cy"){
            $time->day = "Dydd " . $day[0];
          } else {
            $time->day = $day[0];
          }
          $time->hours = $hours[1];
        } else {
          $time = new Hours;
          $time->day = $notFound;
          $time->hours = "";
        }

      }

      $office = new Office;
      $office->lecturer = $name;
      $office->times = $times;
      $office->room = $room;
      $office->building = $building;
      $office->lastUpdate = trim($lastUpdated);

      $officeHours->push($office);

    }

    return $officeHours;
  }
}
