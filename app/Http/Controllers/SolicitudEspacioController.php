<?php

namespace App\Http\Controllers;

use App\espacio;
use App\event;
use App\turno;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SolicitudEspacioController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::find(Auth::id());
        $solicitudes = $user->solicitud_espacio()->get();
        $icons = [
          0 => "far fa-clock fa-2x",
          1 => "far fa-check fa-2x",
          2 => "far fa-times fa-2x"
        ];

        foreach ($solicitudes as $item){
            $item->espacio = espacio::find($item->espacio_id);
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
        $user = User::find(Auth::id());
        $solicitudes = $user->solicitud_espacio()->get();
        $icons = [
            0 => "far fa-clock fa-2x",
            1 => "far fa-check fa-2x",
            2 => "far fa-times fa-2x"
        ];

        foreach ($solicitudes as $item){
           $item->user = User::find($item->pivot["user_id"]);
        }

        return view("espacio.autorizar",[
            "solicitudes" => $solicitudes,
            "icon" => $icons,
        ]);
    }

    public function historial(){
        $user = User::find(Auth::id());
        $solicitudes = $user->solicitud_espacio()->wherePivot("autorizado","!=",0)->get();
        $icons = [
            0 => "far fa-clock fa-2x",
            1 => "far fa-check fa-2x",
            2 => "far fa-times fa-2x"
        ];

        foreach ($solicitudes as $item){
            $item->user = User::find($item->pivot["user_id"]);
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

        User::find(Auth::id())->solicitud_espacio()->attach($data["espacio"],["fecha" => $data["fecha"], "horario" => $data["horario"], "detalle" => $data["detalle"]]);

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
        $solicitud = DB::table("espacios_pivot")->where("id","=",$id)->get()[0];

        $solicitud->user = User::find($solicitud->user_id);
        $solicitud->espacio = espacio::find($solicitud->espacio_id);

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

        DB::table("espacios_pivot")->where("id","=",$data["id"])->update(["autorizado" => $data["estado"], "respuesta" => $data["respuesta"]]);
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
