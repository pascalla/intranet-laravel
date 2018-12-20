<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

use XPathSelector\Selector;
use XPathSelector\Exception\NodeNotFoundException;

use App\User;

class AuthenticationController extends Controller
{
  /**
   * Login Request
   *
   * @return JSON
   */
    public function login(Request $request){
        // Lets do some validation!
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(["status" => "error", "message" => "Please fill in all fields", "errors" => $validator->errors()], 401);
        }

        // Some checker variables
        $loggedIn = false;
        $csrf = "";

        // Create new client
        $client = new \GuzzleHttp\Client();
        $jar = new \GuzzleHttp\Cookie\CookieJar;

        // Ping COSI for inital state
        $res = $client->request('GET', 'https://science.swansea.ac.uk/intranet/accounts/login/?next=/intranet/', [
          'cookies' => $jar
        ]);
        $string = $res->getBody();
        $xs = Selector::loadHTML($string);
        // Grab the CSRF Token
        $elements = $xs->find('//*[@id="login"]/input/@value');
        foreach($elements as $element){
          $csrf = $element->value;
        }

        // Make POST request using previus cookies
        $response = $client->request('POST', 'https://science.swansea.ac.uk/intranet/accounts/login/', [
            'form_params' => [
                'username' => $request->username,
                'password' => $request->password,
                'csrfmiddlewaretoken' => $csrf
            ],
            'cookies' => $jar
        ]);

        // Check if logged in
        $string = $response->getBody();
        $xs = Selector::loadHTML($string);
        try {
          // Check if logged in and return sessionid
          $elements = $xs->find('//*[@id="logout"]/a[2]');
          $user = new User;
          $user->name = $xs->find('//*[@id="logout"]/strong')->innerHtml();
          $user->token = substr($response->getHeader('set-cookie')[0], 10, 32);

          return response()->json(["ok" => true, "status" => 200, "statusText" => "Successfully Logged In", "user" => $user]);
        } catch(NodeNotFoundException $e){
          return response()->json(["ok" => false, "status" => 401, "statusText" => "Incorrect Login Details"], 401);
        }
    }
}
