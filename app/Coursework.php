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
      $courseworks = collect([]);
      $oldCoursework = collect([]);
      $curCoursework = collect([]);

      // Setup cookie jar
      $jar->setCookie(new \GuzzleHttp\Cookie\SetCookie([
        'Domain' => 'science.swansea.ac.uk',
        'Name' => 'sessionid',
        'Value' => $this->api,
        'Path' => '/intranet/',
        'Max-Age' => "172800"
      ]));

      // make request with cookies
      $res = $client->request('GET', 'https://science.swansea.ac.uk/intranet/submission/coursework?language=' . $this->lang, [
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

      $cws = $xs->findAll('//*[@id="pagecontent"]/table[1]//tr');
      foreach ($cws as $cw) {
        // skip if header
        $xz = Selector::loadHTML($cw->innerHtml());
        $cwExist = $xz->findOneOrNull('//td/span') !== null;
        if(!$cwExist){
          continue;
        }

        // else lets get that data
        $module = $xz->find('//td[1]/span')->innerHtml();
        $lecturer = $xz->find('//td[2]')->innerHtml();
        $title = $xz->find('//td[3]')->innerHtml();
        $deadline = $xz->find('//td[4]')->innerHtml();
        $feedback = $xz->find('//td[5]')->innerHtml();

        // lets create object
        $coursework = new Coursework;
        $coursework->module = $module;
        $coursework->lecturer = $lecturer;
        $coursework->title = $title;
        $coursework->deadline = $deadline;
        $coursework->feedback = $feedback;
        $coursework->id = rand(100,100000);

        // push it to colletion
        $curCoursework->push($coursework);
      }

      $cws = $xs->findAll('//*[@id="pagecontent"]/table[2]//tr');
      foreach ($cws as $cw) {
        // skip if header
        $xy = Selector::loadHTML($cw->innerHtml());
        $cwExist = $xy->findOneOrNull('//td/span') !== null;
        if(!$cwExist){
          continue;
        }

        // else lets get that data
        $module = $xy->find('//td[1]/span')->innerHtml();
        $lecturer = $xy->find('//td[2]')->innerHtml();
        $title = $xy->find('//td[3]')->innerHtml();
        $deadline = $xy->find('//td[4]')->innerHtml();
        $feedback = $xy->find('//td[5]')->innerHtml();

        // lets create object
        $coursework = new Coursework;
        $coursework->module = $module;
        $coursework->lecturer = $lecturer;
        $coursework->title = $title;
        $coursework->deadline = $deadline;
        $coursework->feedback = $feedback;
        $coursework->id = rand(100,100000);

        // push it to collection
        $oldCoursework->push($coursework);
      }

      $coursework = new Coursework;
      $coursework->module = "CS101";
      $coursework->lecturer = "Joshua Blackman";
      $coursework->title = "Software Testing";
      $coursework->deadline = "17th of May";
      $coursework->feedback = "25th of May";
      $coursework->id = rand(100,100000);

      $curCoursework->push($coursework);

      $courseworks->push($curCoursework);
      $courseworks->push($oldCoursework);

      return $courseworks;
    }
}
