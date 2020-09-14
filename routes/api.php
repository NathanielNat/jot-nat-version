<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::middleware('auth:api')->group(function () {
    Route::post('/contacts','ContactController@store');
    Route::get('/contacts/{contact}','ContactController@show');
    Route::patch('/contacts/{contact}','ContactController@update');
    Route::delete('/contacts/{contact}','ContactController@destroy');
});

