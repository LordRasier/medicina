<?php

use Illuminate\Support\Facades\Route;

Route::middleware('menu.access:/dispensas')->group(function () {
    Route::get('/dispensas', 'DispensaController@index');
    Route::get('/dispensas/create', 'DispensaController@create');
    Route::put('/dispensas/create/store', 'DispensaController@store');
    Route::get('/dispensas/show/{id}', 'DispensaController@edit');
    Route::put('/dispensas/show/{id}/update', 'DispensaController@update');
});

Route::middleware('menu.access:/misDispensas')->group(function () {
    Route::get('/misDispensas', 'PeriodoController@index');
    Route::get('/misDispensas/Prev', 'PeriodoController@Prev');
    Route::get('/misDispensas/Next', 'PeriodoController@Next');
    Route::get('/misDispensas/solicitud', 'PeriodoController@create');
    Route::get('/misDispensas/solicitudes', 'PeriodoController@misSolicitudes');
    Route::put('/misDispensas/create/store', 'PeriodoController@store');
    Route::get('/request/detalle/{id}', 'PeriodoController@detail');
});

Route::middleware('menu.access:/autorizaciones/2')->group(function () {
    Route::get('/autorizaciones/2', 'PeriodoController@auto2');
    Route::get('/autorizaciones/2/definir/{id}', 'PeriodoController@detail2');
    Route::put('/autorizaciones/2/definir/{id}/update', 'PeriodoController@definir2');
});

Route::middleware('menu.access:/autorizaciones/3')->group(function () {
    Route::get('/autorizaciones/3', 'PeriodoController@auto3');
    Route::get('/autorizaciones/3/definir/{id}', 'PeriodoController@detail3');
    Route::put('/autorizaciones/3/definir/{id}/update', 'PeriodoController@definir3');
});

Route::get('/checkfile/{id}', 'PeriodoController@checkfile');

Route::middleware('menu.access:/editperiod')->group(function () {
    Route::get('/editperiod', 'PeriodoController@toeditperiod');
    Route::get('/editperiod/{id}', 'PeriodoController@editperiod');
    Route::put('/editperiod/{id}/update', 'PeriodoController@updateperiod');
    Route::get('/extralist', 'PeriodoController@extras');
});

Route::middleware('menu.access:/checklist')->group(function () {
    Route::get('/checklist', 'PeriodoController@checklist');
    Route::get('/dispensas/check/{id}', 'PeriodoController@checkuser');
    Route::get('/dispensas/check/{id}/prev', 'PeriodoController@Prevd');
    Route::get('/dispensas/check/{id}/next', 'PeriodoController@Nextd');
});

Route::get('/dispensas/historial', 'DispensaController@historial')
    ->middleware('menu.access:/dispensas/historial');

Route::get('/dispensas/anual', 'PeriodoController@anio')
    ->middleware('menu.access:/dispensas/anual');

Route::middleware('menu.access:/configuracion/forbid')->group(function () {
    Route::get('/configuracion/forbid', 'ForbidController@index');
    Route::get('/configuracion/forbid/{anio}', 'ForbidController@change');
    Route::put('/configuracion/forbid/store', 'ForbidController@store');
});

Route::get('/historial/detail/{id}', 'DispensaController@HistorialDetail')
    ->middleware('menu.access:/dispensas/historial');
