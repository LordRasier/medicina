<?php

namespace App\Http\Controllers;

use App\espacio;
use App\event;
use App\turno;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SolicitudEspacioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $solicitudes = $user->solicitud_espacio()->get();
        $icons = [
          0 => "far fa-clock fa-2x",
          1 => "far fa-check fa-2x",
          2 => "far fa-times fa-2x"
        ];

        foreach ($solicitudes as $item){
            $item->espacio = $item->espacio()->get();
        }

        return view("espacio.listado",[
            "solicitudes" => $solicitudes,
            "icon" => $icons,
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function listado(){
        $user = Auth::user();
        $solicitudes = turno::all()->where("autorizado","=","0");
        $icons = [
            0 => "far fa-clock fa-2x",
            1 => "far fa-check fa-2x",
            2 => "far fa-times fa-2x"
        ];

        foreach ($solicitudes as $item){
            $item->espacio = $item->espacio()->get();
            $item->user = $item->user()->get();
        }

        return view("espacio.autorizar",[
            "solicitudes" => $solicitudes,
            "icon" => $icons,
        ]);
    }

    public function historial(){
        $user = Auth::user();
        $solicitudes = turno::all()->where("autorizado","!=","0");
        $icons = [
            0 => "far fa-clock fa-2x",
            1 => "far fa-check fa-2x",
            2 => "far fa-times fa-2x"
        ];

        foreach ($solicitudes as $item){
            $item->espacio = $item->espacio()->get();
            $item->user = $item->user()->get();
        }

        return view("espacio.historial",[
            "solicitudes" => $solicitudes,
            "icon" => $icons,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();


        return view("espacio.nuevaSolicitud",[
            "espacios" => espacio::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            "fecha"=>"required",
            "horario"=>"required",
            "espacio"=>"required",
            "detalle" => "required"
        ]);

        $new = new turno();
        $new->user_id = Auth::id();
        $new->espacio_id = $data["espacio"];
        $new->fecha = $data["fecha"];
        $new->horario = $data["horario"];
        $new->detalle = $data["detalle"];

        $new->save();

        return redirect("/espacios/solicitudes/list");

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $turno = User::find($id);
        $estado = [
          1 => "Autorizado",
          2 => "Rechazado"
        ];

        $turno->espacio = $turno->espacio()->get();
        $turno->user = $turno->user()->get();
        return view("espacio.show",[
            "item" => $turno,
            "estado" => $estado
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       $solicitud = turno::findOrFail($id);
       $solicitud->espacio = $solicitud->espacio()->get();
       $solicitud->user = $solicitud->user()->get();

        $user = Auth::user();

        return view("espacio.definir",[
            "item" => $solicitud
        ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = $request->validate([
            "estado" => "required",
            "respuesta" => "required",
            "id" => "required"
        ]);

        $turno = turno::findOrFail($data["id"]);
        $turno->autorizado = $data["estado"];
        $turno->respuesta = $data["respuesta"];

        $turno->save();

        return redirect("/espacios/solicitudes/autorizar");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
