<?php

use Illuminate\Support\Facades\Route;



Auth::routes();


// gets any route and sends to the app controller 
Route::get('/{any}', 'AppController@index')->where('any','.*');
