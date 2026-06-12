<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index');

Route::put('profile/pic/update', 'ProfileController@pic');
Route::put('profile/newpass', 'ProfileController@password');

Route::put('/calendar/event/add', 'EventController@store');

Route::middleware('menu.access:/mail/inbox')->group(function () {
    Route::put('/home/mail/send', 'MailController@store');
    Route::get('/mail/inbox', 'MailController@inbox');
    Route::get('/mail/outbox', 'MailController@outbox');
});
