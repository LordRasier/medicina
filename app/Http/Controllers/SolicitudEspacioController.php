<?php

namespace App\Http\Controllers;

use App\espacio;
use App\event;
use App\turno;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

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
        $solicitudes = DB::table("espacios_pivot")->where("autorizado","=",0)->orderBy("id","desc")->get();
        $icons = [
            0 => "far fa-clock fa-2x",
            1 => "far fa-check fa-2x",
            2 => "far fa-times fa-2x"
        ];

        foreach ($solicitudes as $item){
           $item->user = User::find($item->user_id);
           $item->espacio = espacio::find($item->espacio_id);
        }

        return view("espacio.autorizar",[
            "solicitudes" => $solicitudes,
            "icon" => $icons,
        ]);
    }

    public function historial(){
        $user = User::find(Auth::id());
        $solicitudes = $user->solicitud_espacio()->wherePivot("autorizado","!=",0)->orderBy("id","desc")->get();
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
        $result = false;
        //dd(DB::table("espacios_pivot")->where(["espacio_id" => $data["espacio"],"fecha" => $data["fecha"], "horario" => $data["horario"], "autorizado" => 1])->count());
        if(!DB::table("espacios_pivot")->where(["espacio_id" => $data["espacio"],"fecha" => $data["fecha"], "horario" => $data["horario"], "autorizado" => 1])->count()){
            User::find(Auth::id())->solicitud_espacio()->attach($data["espacio"],["fecha" => $data["fecha"], "horario" => $data["horario"], "detalle" => $data["detalle"]]);
            $result = true;

            $ids = DB::table("sub_menu_user")->where("sub_menu_id", "=", 6)->get();

            foreach($ids as $item){
                $user = User::find($item->user_id);
                Mail::to($user->email)->send(new \App\Mail\solicitud_espacio('It works!'));
            }
        }

        return view("espacio.nuevaSolicitud",[
            "espacios" => espacio::all(),
            "result" => $result
        ]);

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
        $solicitud = DB::table("espacios_pivot")->where("id","=",$id)->first();

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

        $R = [
            1 => "Aprobada",
            2 => "Rechazada"
        ];

        DB::table("espacios_pivot")->where("id","=",$data["id"])->update(["autorizado" => $data["estado"], "respuesta" => $data["respuesta"]]);


        $temp = DB::table("espacios_pivot")->select(["user_id","espacio_id","horario"])->where("id","=",$data["id"])->first();
        $user = User::find($temp->user_id);
        if($data["estado"] == 1){
            Mail::to($user->email)->send(new \App\Mail\resolucion_espacio(""));
            DB::table("espacios_pivot")->where("id","!=",$data["id"])->where("espacio_id","=",$temp->espacio_id)->where("horario","=",$temp->horario)->update(["autorizado" => 2, "respuesta" => "Otro usuario fue autorizado"]);
        }else{
            Mail::to($user->email)->send(new \App\Mail\resolucion_negativa(""));
        }


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
        DB::table("espacios_pivot")->delete($id);

        return redirect("/espacios/solicitudes/list");
    }
}
