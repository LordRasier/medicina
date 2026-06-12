<?php

use Illuminate\Support\Facades\Route;

Route::middleware('menu.access:/misHonorarios')->group(function () {
    Route::get('/misFacturas', 'FacturaController@MisFacturas');
    Route::get('/misFacturas/create/{id}', 'FacturaController@create');
    Route::put('/misFacturas/create/store', 'FacturaController@store');
});

Route::middleware('menu.access:/honorarios')->group(function () {
    Route::get('/facturas/show', 'FacturaController@index');

    Route::get('/honorarios', 'HonorarioController@index');
    Route::delete('/honorarios/delete/{id}', 'HonorarioController@destroy');
});

Route::delete('/facturas/delete/{id}', 'FacturaController@destroy')
    ->middleware('menu.access:/honorarios,/misHonorarios');

Route::get('/facturas/show/{id}', 'FacturaController@show')
    ->middleware('menu.access:/honorarios,/misHonorarios');

Route::middleware('menu.access:/misHonorarios')->group(function () {
    Route::get('/misHonorarios', 'HonorarioController@MisHonorarios');
    Route::get('/honorarios/show/{id}', 'HonorarioController@show');
});

Route::middleware('menu.access:/honorarios/create')->group(function () {
    Route::get('/honorarios/create', 'HonorarioController@create');
    Route::put('/honorarios/create/store', 'HonorarioController@store');
});
