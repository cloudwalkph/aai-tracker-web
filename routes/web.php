<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', 'HomeController@index');

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::group(['prefix' => 'insite'], function() {
    Route::get('login', 'Insite\LoginController@index');

    Route::group(['middleware' => 'auth'], function() {
        Route::get('/', 'Insite\HomeController@index');

        Route::group(['prefix' => 'events'], function() {
            Route::get('{eventId}', 'Insite\EventsController@show');
            Route::get('{eventId}/locations/{locationId}', 'Insite\EventsController@showByLocation');
        });
    });
});


Route::group(['prefix' => 'management'], function() {
    Route::get('login', 'Management\LoginController@index');
    Route::get('/', 'Management\DashboardController@index');

    Route::get('events', 'Management\EventsController@index');
    Route::get('events/{eventId}/locations', 'Management\EventsController@showLocations');
    Route::post('events/{eventId}/locations/{locationId}', 'Management\EventsController@uploadPlaybackFootage');
});