<?php

namespace App\Http\Controllers;

use App\dispensa;
use App\espacio;
use App\event;
use App\licence;
use App\request as sol;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DispensaController extends Controller
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
    public function index(){
        $dispensas = licence::all();

        $icon = [
            0 => "far fa-times fa-2x",
            1 => "far fa-check fa-2x"
        ];

        return view("licenses.index",[
            "dispensas" => $dispensas,
            "icon" => $icon
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("licenses.create");
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
            "dispensa" => "required"
        ]);

        $licence = new licence();

        $licence->description = $data["dispensa"];
        $licence->alter = ($request->has("alter"))? 1 : 0;
        $licence->habilitado = ($request->has("habilitado"))? 1 : 0;

        $licence->save();

        return redirect("/dispensas");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $dispensa = licence::findOrFail($id);
        return view("licenses.show",[
            "dispensa" => $dispensa
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $licence = licence::findOrFail($id);
        $data = $request->validate([
            "dispensa" => "required"
        ]);

        $licence->description = $data["dispensa"];
        $licence->alter = ($request->has("alter"))? 1 : 0;
        $licence->habilitado = ($request->has("habilitado"))? 1 : 0;

        $licence->save();

        return redirect("/dispensas");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }

    public function historial(){
        $icono = [
            0 => "far fa-times fa-2x",
            1 => "far fa-clock",
            2 => "far fa-check fa-2x"
        ];
        $solicitudes = sol::where("autorizacion2","!=",1)->Where("autorizacion3","!=",1)->get();
        foreach ($solicitudes as $item){
            $item->user = User::find($item->user_id);
        }


        return view("periodo.historial",[
            "solicitudes" => $solicitudes,
            "icono" => $icono
        ]);
    }

    public function HistorialDetail(Request $request, $id){
        $sol = sol::findOrFail($id);
        $sol->user = $sol->user()->first();
        $sol->dias = $sol->days()->get();
        $sol->licence = DB::table("licences")->select("description")->where("id","=",$sol->licence_id)->first();

        return view("licenses.historial",[
            "solicitud" => $sol
        ]);
    }

}
