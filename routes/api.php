<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('login', 'AuthenticationController@login');
Route::post('dashboard', 'DashboardController@index');
Route::post('timetable/week', 'TimetableController@getWeek');
Route::post('coursework/year', 'CourseworkController@getYear');
Route::post('directory', 'OfficeHoursController@index');

Route::get('testnotify', 'DashboardController@notify');
Route::post('notify', 'NotificationController@notify');
