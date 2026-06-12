<?php

use Illuminate\Support\Facades\Route;

// Authentication routes previously registered through Auth::routes().
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
