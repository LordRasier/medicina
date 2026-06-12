<?php

use Illuminate\Support\Facades\Route;

Route::middleware('menu.access:/espacios/list')->group(function () {
    Route::get('/espacios/list', 'EspacioController@index');
    Route::get('/espacios/new', 'EspacioController@create');
    Route::put('/espacios/new/create', 'EspacioController@store');
    Route::get('/espacios/{id}/edit', 'EspacioController@edit');
    Route::put('/espacios/{id}/update', 'EspacioController@update');
    Route::delete('/espacios/{id}/remove', 'EspacioController@destroy');
});

Route::middleware('menu.access:/espacios/solicitudes/list')->group(function () {
    Route::get('/espacios/solicitudes/list', 'SolicitudEspacioController@index');
    Route::get('/espacios/solicitudes/new', 'SolicitudEspacioController@create');
    Route::put('/espacios/solicitudes/new/create', 'SolicitudEspacioController@store');
    Route::get('/espacios/solicitudes/{id}/show', 'SolicitudEspacioController@show');
    Route::delete('/espacios/solicitudes/{id}/remove', 'SolicitudEspacioController@destroy');
});

Route::middleware('menu.access:/espacios/solicitudes/autorizar')->group(function () {
    Route::get('/espacios/solicitudes/autorizar', 'SolicitudEspacioController@listado');
    Route::get('/espacios/solicitudes/{id}/edit', 'SolicitudEspacioController@edit');
    Route::put('/espacios/solicitudes/definir', 'SolicitudEspacioController@update');
});

Route::get('/espacios/solicitudes/historial', 'SolicitudEspacioController@historial')
    ->middleware('menu.access:/espacios/solicitudes/historial');
