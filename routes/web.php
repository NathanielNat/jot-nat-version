<?php

use Illuminate\Support\Facades\Route;



Auth::routes();

Route::get('/logout-manual',function(){
    request()->session()->invalidate();
});


// gets any route and sends to the app controller 
Route::get('/{any}', 'AppController@index')->where('any','.*');
