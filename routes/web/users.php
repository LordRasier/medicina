<?php

use Illuminate\Support\Facades\Route;

Route::middleware('menu.access:/users')->group(function () {
    Route::get('/users', 'ProfileController@index');
    Route::get('/users/new', 'ProfileController@create');
    Route::put('/users/new/create', 'ProfileController@store');
    Route::get('/users/{id}', 'ProfileController@edit');
    Route::put('/users/{id}/update', 'ProfileController@update');
    Route::delete('/users/{id}/remove', 'ProfileController@destroy');
});
