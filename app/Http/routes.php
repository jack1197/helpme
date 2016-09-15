<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'RootController@index');
Route::get('/help', 'RootController@redirect_index');
Route::get('/about', 'RootController@redirect_index');

Route::auth();

Route::get('/home', 'HomeController@index');
Route::get('/student', 'StudentController@index');
Route::get('/tutor', 'TutorController@index');
//unimplemented
//Route::get('/help', 'HelpController@index');
//Route::get('/about', 'AboutController@index');

Route::post('/account', 'AccountController@update');
Route::get('/account', 'AccountController@index');


Route::post('/class/new', 'ClassSessionController@newclass');

Route::get('/class/tutor', 'ClassSessionController@tutorjoinclass');
Route::put('/class/tutor', 'ClassSessionController@tutorupdateclass');
Route::delete('/class/tutor', 'ClassSessionController@tutordeleteclass');
Route::get('/class/tutor/refresh', 'ClassSessionController@tutorrefreshclass');

Route::get('/class/student', 'ClassSessionController@studentjoinclass');
Route::get('/class/student/refresh', 'ClassSessionController@studentrefreshclass');
Route::put('/class/student', 'ClassSessionController@studentupdateclass');
Route::delete('/class/student', 'ClassSessionController@studentleaveclass');