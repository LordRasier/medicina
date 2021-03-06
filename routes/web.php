<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
});

Auth::routes();
//Rutas Base
Route::get('/', 'HomeController@index')->name('home');

Route::get('/home', 'HomeController@index')->name('home');

Route::get("/logout", "SesionController@logout");
//End Rutas Base

//Rutas Perfil
Route::put("profile/pic/update","ProfileController@pic");

Route::put("profile/newpass","ProfileController@password");
//End Rutas Perfil

//Rutas Calendar
Route::put("/calendar/event/add","EventController@store");
//End Rutas calendar

//Rutas Mailing
Route::put("/home/mail/send","MailController@store");

Route::get("/mail/inbox","MailController@inbox");

Route::get("/mail/outbox","MailController@outbox");
//End Rutas Mailing

//Rutas Users
Route::get("/users","ProfileController@index");

Route::get("/users/new","ProfileController@create");

Route::put("/users/new/create","ProfileController@store");

Route::get("/users/{id}","ProfileController@edit");

Route::put("/users/{id}/update","ProfileController@update");

Route::get("/users/{id}/remove","ProfileController@destroy");
//End Rutas User

//Rutas espacios
Route::get("/espacios/list","EspacioController@index");

Route::get("/espacios/new","EspacioController@create");

Route::put("/espacios/new/create","EspacioController@store");

Route::get("/espacios/{id}/edit","EspacioController@edit");

Route::put("/espacios/{id}/update","EspacioController@update");

Route::get("/espacios/{id}/remove","EspacioController@destroy");
//End Rutas espacios

//Rutas solicitud de espacios
Route::get("/espacios/solicitudes/list","SolicitudEspacioController@index");

Route::get("/espacios/solicitudes/new","SolicitudEspacioController@create");

Route::put("/espacios/solicitudes/new/create","SolicitudEspacioController@store");

Route::get("/espacios/solicitudes/autorizar","SolicitudEspacioController@listado");

Route::get("/espacios/solicitudes/{id}/edit","SolicitudEspacioController@edit");

Route::put("/espacios/solicitudes/definir","SolicitudEspacioController@update");

Route::get("/espacios/solicitudes/historial","SolicitudEspacioController@historial");

Route::get("/espacios/solicitudes/{id}/show","SolicitudEspacioController@show");

Route::get("/espacios/solicitudes/{id}/remove","SolicitudEspacioController@destroy");
//End Rutas solicitud

// Rutas Facturas
Route::get("/misFacturas","FacturaController@MisFacturas");

Route::get("/misFacturas/create/{id}","FacturaController@create");

Route::put("/misFacturas/create/store","FacturaController@store");

Route::get("/facturas/show","FacturaController@index");

Route::get("/facturas/show/{id}","FacturaController@show");

Route::get("/facturas/delete/{id}","FacturaController@destroy");
//End Rutas facturas

//Rutas Honorarios
Route::get("/misHonorarios","HonorarioController@MisHonorarios");

Route::get("/honorarios","HonorarioController@index");

Route::get("/honorarios/create", "HonorarioController@create");

Route::put("/honorarios/create/store", "HonorarioController@store");

Route::get("/honorarios/delete/{id}", "HonorarioController@destroy");

Route::get("/honorarios/show/{id}", "HonorarioController@show");
//End rutas honorarios

//Rutas Dispensas //

Route::get("/dispensas","DispensaController@index");

Route::get("/dispensas/create","DispensaController@create");

Route::put("/dispensas/create/store","DispensaController@store");

Route::get("/dispensas/show/{id}","DispensaController@edit");

Route::put("/dispensas/show/{id}/update","DispensaController@update");

Route::get("/misDispensas", "PeriodoController@index");

Route::get("/misDispensas/Prev", "PeriodoController@Prev");

Route::get("/misDispensas/Next", "PeriodoController@Next");

Route::get("/misDispensas/solicitud", "PeriodoController@create");

Route::get("/misDispensas/solicitudes", "PeriodoController@misSolicitudes");

Route::put("/misDispensas/create/store", "PeriodoController@store");

Route::get("/request/detalle/{id}", "PeriodoController@detail");

Route::get("/autorizaciones/2", "PeriodoController@auto2");

Route::get("/autorizaciones/3", "PeriodoController@auto3");

Route::get("/autorizaciones/2/definir/{id}","PeriodoController@detail2");

Route::put("/autorizaciones/2/definir/{id}/update","PeriodoController@definir2");

Route::get("/autorizaciones/3/definir/{id}","PeriodoController@detail3");

Route::put("/autorizaciones/3/definir/{id}/update","PeriodoController@definir3");

Route::get("/checkfile/{id}","PeriodoController@checkfile");

Route::get("/editperiod","PeriodoController@toeditperiod");

Route::get("/editperiod/{id}","PeriodoController@editperiod");

Route::put("/editperiod/{id}/update","PeriodoController@updateperiod");

Route::get("/extralist","PeriodoController@extras");

Route::get("/checklist","PeriodoController@checklist");

Route::get("/dispensas/check/{id}","PeriodoController@checkuser");

Route::get("/dispensas/check/{id}/prev", "PeriodoController@Prevd");

Route::get("/dispensas/check/{id}/next", "PeriodoController@Nextd");

Route::get("/dispensas/historial", "DispensaController@historial");

Route::get("/dispensas/anual", "PeriodoController@anio");

Route::get("/configuracion/forbid", "ForbidController@index");

Route::get("/configuracion/forbid/{anio}", "ForbidController@change");

Route::put("/configuracion/forbid/store", "ForbidController@store");

Route::get("/historial/detail/{id}","DispensaController@HistorialDetail");

//End Rutas Dispensas //
