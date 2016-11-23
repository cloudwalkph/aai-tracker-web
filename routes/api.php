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

Route::group(['prefix' => 'v1'], function() {
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:api');


    Route::group(['prefix' => 'events'], function() {
        Route::get('/', 'API\EventsController@getEvents');
        Route::post('/{eventId}/event-locations/{locationId}/answer', 'API\EventAnswersController@saveAnswer');
    });
});

