<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use XPathSelector\Selector;
use XPathSelector\Exception\NodeNotFoundException;

class Coursework extends Model
{
    public function getAll(){
      $client = new \GuzzleHttp\Client();
      $jar = new \GuzzleHttp\Cookie\CookieJar;

      // Setup collection
      $courseworks = new Coursework;
      $courseworks->current = collect();
      $courseworks->past = collect();

      // Setup cookie jar
      $jar->setCookie(new \GuzzleHttp\Cookie\SetCookie([
        'Domain' => 'science.swansea.ac.uk',
        'Name' => 'sessionid',
        'Value' => $this->api,
        'Path' => '/intranet/',
        'Max-Age' => "172800"
      ]));

      // make request with cookies
      $res = $client->request('GET', 'https://science.swansea.ac.uk/intranet/submission/coursework?session=1819', [
        'cookies' => $jar
      ]);

      //get response
      $body = $res->getBody();
      $xs = Selector::loadHTML($body);

      try {
        $elements = $xs->find('//*[@id="logout"]/a[2]');
      } catch(NodeNotFoundException $ex){
        return null;
      }

        $pastcws = $xs->find('//table[@class="fullwidth courseworks past"]');
        $pastcws = $pastcws->findAll('//tr');
        foreach ($pastcws as $cw) {
          // skip if header
          $xs = Selector::loadHTML($cw->innerHtml());
          $cwExist = $xs->findOneOrNull('//td/span') !== null;
          if(!$cwExist){
            continue;
          }

          // else lets get that data
          $module = $xs->find('//td[1]/span')->innerHtml();
          $lecturer = $xs->find('//td[2]')->innerHtml();
          $title = $xs->find('//td[3]')->innerHtml();
          $deadline = $xs->find('//td[4]')->innerHtml();
          $feedback = $xs->find('//td[5]')->innerHtml();

          // lets create object
          $coursework = new Coursework;
          $coursework->module = $module;
          $coursework->lecturer = $lecturer;
          $coursework->title = $title;
          $coursework->deadline = $deadline;
          $coursework->feedback = $feedback;

          // push it to colletion
          $courseworks->past->push($coursework);
        }
        return $courseworks;
    }
}
