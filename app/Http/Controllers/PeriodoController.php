<?php

namespace App\Http\Controllers;

use App\alter;
use App\day;
use App\forbid;
use App\request as sol;
use App\event;
use App\licence;
use App\periodo;
use App\User;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PeriodoController extends Controller
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

    public function prev(){
        $user = User::find(Auth::id());
        $this->disponibles($user->id);
        $center = Carbon::parse($user->ingreso)->format("Y-m-d");
        $actual = Carbon::create(Carbon::now()->year,$center->month,$center->day);
        $actual = $actual->subYear();

        $periodo = $user->periodos()->where("start","<=",$actual)->where("end",">=",$actual)->first();

        if($periodo == null){
            $periodo = new periodo();

            $periodo->user_id = $user->id;
            $periodo->start = $actual->toDateString();
            $periodo->end = $actual->addDays(364)->toDateString();

            $periodo->save();
        }

        $start    = (new DateTime($periodo->start))->modify('first day of this month');
        $end      = (new DateTime($periodo->end))->modify('last day of this month');
        $interval = DateInterval::createFromDateString('1 month');
        $intevalday  = DateInterval::createFromDateString('1 day');
        //prohibite days
        $sundays = array();

        $before = new DatePeriod($start, $intevalday, new DateTime($periodo->start));
        foreach ($before as $item){
            $sundays[$item->format('Y-m-d')] = $item->format('Y-m-d');

            foreach (forbid::whereYear("day",$item->format("Y"))->get() as $item2){
                $sundays[$item2->day] = $item2->day;
            }
        }

        $after = new DatePeriod(new DateTime($periodo->end), $intevalday, $end);
        foreach ($after as $item){
            $sundays[$item->format('Y-m-d')] = $item->format('Y-m-d');

            foreach (forbid::whereYear("day",$item->format("Y"))->get() as $item2){
                $sundays[$item2->day] = $item2->day;
            }
        }


        $period   = new DatePeriod($start, $interval, $end);
        foreach ($period as $dt) {

            $mes = array(
                "01" => "Ene",
                "02" => "Feb",
                "03" => "Mar",
                "04" => "Abr",
                "05" => "May",
                "06" => "Jun",
                "07" => "Jul",
                "08" => "Ago",
                "09"=> "Sep",
                "10" => "Oct",
                "11" => "Nov",
                "12" => "Dic"
            );

            $t[] = [$dt->format('Y-m'),$dt->format('Y')."-".$mes[$dt->format('m')]];

            if(!checkdate($dt->format("m"),29,$dt->format("Y"))){
                $sundays[$dt->format("Y-m")."-29"] =  $dt->format("Y-m")."-29";
            }
            if(!checkdate($dt->format("m"),31,$dt->format("Y"))){
                $sundays[$dt->format("Y-m")."-31"] =  $dt->format("Y-m")."-31";
            }
            if(!checkdate($dt->format("m"),30,$dt->format("Y"))){
                $sundays[$dt->format("Y-m")."-31"] =  $dt->format("Y-m")."-31";
            }


        }


        while ($start <= $end) {
            if ($start->format('w') == 0 || $start->format("w") == 6) {
                $sundays[$start->format('Y-m-d')] = $start->format('Y-m-d');
            }

            $start->modify('+1 day');
        }
        //dd($sundays);
        $periodo->requests = $periodo->dispensas()->where("autorizacion2","=",2)->where("autorizacion3","=",2)->get();
        $usados = 0;
        $marcar = [];
        foreach($periodo->requests as $item) {
            $item->temporal = DB::table("licences")->where("id","=",$item->licence_id)->first();

            if($item->temporal->alter == 1){
                $usados = $usados + count(day::where("request_id","=",$item->id)->get());
            }
            $temp = day::where("request_id","=",$item->id)->get();

            foreach($temp as $item){
                $marcar[$item->date] = $item->date;
            }
        }


        $especiales = 0;
        $T = alter::where("periodo_id","=",$periodo->id)->get();

        foreach ($T as $item){
            $especiales = $especiales + $item->days;
        }

        return view("periodo.show",[
            "periodo" => $periodo,
            "almanaque" => $t,
            "domingos" => $sundays,
            "disponibles" => $this->disponibles($user->id),
            "usados" => $usados,
            "especiales" => $especiales,
            "marcar" => $marcar,
            "nextno" => true,
            "prevno" => false
        ]);
    }

    public function next(){
        $user = User::find(Auth::id());
        $this->disponibles($user->id);
        $center = Carbon::parse($user->ingreso)->format("Y-m-d");
        $actual = Carbon::create(Carbon::now()->year,$center->month,$center->day);
        $actual = $actual->addDays(365);

        $periodo = $user->periodos()->where("start","<=",$actual)->where("end",">=",$actual)->first();

        if($periodo == null){
            $periodo = new periodo();

            $periodo->user_id = $user->id;
            $periodo->start = $actual->toDateString();
            $periodo->end = $actual->addDays(364)->toDateString();

            $periodo->save();
        }

        $start    = (new DateTime($periodo->start))->modify('first day of this month');
        $end      = (new DateTime($periodo->end))->modify('last day of this month');
        $interval = DateInterval::createFromDateString('1 month');
        $intevalday  = DateInterval::createFromDateString('1 day');
        //prohibite days
        $sundays = array();

        $before = new DatePeriod($start, $intevalday, new DateTime($periodo->start));
        foreach ($before as $item){
            $sundays[$item->format('Y-m-d')] = $item->format('Y-m-d');

            foreach (forbid::whereYear("day",$item->format("Y"))->get() as $item2){
                $sundays[$item2->day] = $item2->day;
            }
        }



        $after = new DatePeriod(new DateTime($periodo->end), $intevalday, $end);
        foreach ($after as $item){
            $sundays[$item->format('Y-m-d')] = $item->format('Y-m-d');

            foreach (forbid::whereYear("day",$item->format("Y"))->get() as $item2){
                $sundays[$item2->day] = $item2->day;
            }
        }


        $period   = new DatePeriod($start, $interval, $end);
        foreach ($period as $dt) {
            $mes = array(
                "01" => "Ene",
                "02" => "Feb",
                "03" => "Mar",
                "04" => "Abr",
                "05" => "May",
                "06" => "Jun",
                "07" => "Jul",
                "08" => "Ago",
                "09"=> "Sep",
                "10" => "Oct",
                "11" => "Nov",
                "12" => "Dic"
            );

            $t[] = [$dt->format('Y-m'),$dt->format('Y')."-".$mes[$dt->format('m')]];

            if(!checkdate($dt->format("m"),29,$dt->format("Y"))){
                $sundays[$dt->format("Y-m")."-29"] =  $dt->format("Y-m")."-29";
            }
            if(!checkdate($dt->format("m"),31,$dt->format("Y"))){
                $sundays[$dt->format("Y-m")."-31"] =  $dt->format("Y-m")."-31";
            }
            if(!checkdate($dt->format("m"),30,$dt->format("Y"))){
                $sundays[$dt->format("Y-m")."-31"] =  $dt->format("Y-m")."-31";
            }
        }


        while ($start <= $end) {
            if ($start->format('w') == 0 || $start->format("w") == 6) {
                $sundays[$start->format('Y-m-d')] = $start->format('Y-m-d');
            }

            $start->modify('+1 day');
        }
        //dd($sundays);
        $periodo->requests = $periodo->dispensas()->where("autorizacion2","=",2)->where("autorizacion3","=",2)->get();
        $usados = 0;
        $marcar = [];
        foreach($periodo->requests as $item) {
            $item->temporal = DB::table("licences")->where("id","=",$item->licence_id)->first();

            if($item->temporal->alter == 1){
                $usados = $usados + count(day::where("request_id","=",$item->id)->get());
            }
            $temp = day::where("request_id","=",$item->id)->get();

            foreach($temp as $item){
                $marcar[$item->date] = $item->date;
            }
        }
        $especiales = 0;
        $T = alter::where("periodo_id","=",$periodo->id)->get();

        foreach ($T as $item){
            $especiales = $especiales + $item->days;
        }

        return view("periodo.show",[
            "periodo" => $periodo,
            "almanaque" => $t,
            "domingos" => $sundays,
            "disponibles" => $this->disponibles($user->id),
            "usados" => $usados,
            "especiales" => $especiales,
            "marcar" => $marcar,
            "nextno" => false,
            "prevno" => true
        ]);
    }




    public function index()
    {
        $user = User::find(Auth::id());
        $this->disponibles($user->id);
        $center = Carbon::parse($user->ingreso)->format("Y-m-d");
        $actual = Carbon::create(Carbon::now()->year,$center->month,$center->day);

        $periodo = $user->periodos()->where("start","<=",$actual)->where("end",">=",$actual)->first();

        if($periodo == null){
            $periodo = new periodo();

            $periodo->user_id = $user->id;
            $periodo->start = $actual->toDateString();
            $periodo->end = $actual->addDays(364)->toDateString();

            $periodo->save();
        }

        $start    = (new DateTime($periodo->start))->modify('first day of this month');
        $end      = (new DateTime($periodo->end))->modify('last day of this month');
        $interval = DateInterval::createFromDateString('1 month');
        $intevalday  = DateInterval::createFromDateString('1 day');
        //prohibite days
        $sundays = array();

        $before = new DatePeriod($start, $intevalday, new DateTime($periodo->start));
        foreach ($before as $item){
            $sundays[$item->format('Y-m-d')] = $item->format('Y-m-d');
            foreach (forbid::whereYear("day",$item->format("Y"))->get() as $item2){
                $sundays[$item2->day] = $item2->day;
            }
        }

        $after = new DatePeriod(new DateTime($periodo->end), $intevalday, $end);
        foreach ($after as $item){
            $sundays[$item->format('Y-m-d')] = $item->format('Y-m-d');
            foreach (forbid::whereYear("day",$item->format("Y"))->get() as $item2){
                $sundays[$item2->day] = $item2->day;
            }
        }


        $period   = new DatePeriod($start, $interval, $end);
        foreach ($period as $dt) {
            $mes = array(
                "01" => "Ene",
                "02" => "Feb",
                "03" => "Mar",
                "04" => "Abr",
                "05" => "May",
                "06" => "Jun",
                "07" => "Jul",
                "08" => "Ago",
                "09"=> "Sep",
                "10" => "Oct",
                "11" => "Nov",
                "12" => "Dic"
            );

            $t[] = [$dt->format('Y-m'),$dt->format('Y')."-".$mes[$dt->format('m')]];

            if(!checkdate($dt->format("m"),29,$dt->format("Y"))){
                $sundays[$dt->format("Y-m")."-29"] =  $dt->format("Y-m")."-29";
            }
            if(!checkdate($dt->format("m"),31,$dt->format("Y"))){
                $sundays[$dt->format("Y-m")."-31"] =  $dt->format("Y-m")."-31";
            }
            if(!checkdate($dt->format("m"),30,$dt->format("Y"))){
                $sundays[$dt->format("Y-m")."-31"] =  $dt->format("Y-m")."-31";
            }
        }


        while ($start <= $end) {
            if ($start->format('w') == 0 || $start->format("w") == 6) {
                $sundays[$start->format('Y-m-d')] = $start->format('Y-m-d');
            }

            $start->modify('+1 day');
        }
        //dd($sundays);
        $periodo->requests = $periodo->dispensas()->where("autorizacion2","=",2)->where("autorizacion3","=",2)->get();
        $usados = 0;
        $marcar = [];
        foreach($periodo->requests as $item) {
            $item->temporal = DB::table("licences")->where("id","=",$item->licence_id)->first();

            if($item->temporal->alter == 1){
                $usados = $usados + count(day::where("request_id","=",$item->id)->get());
            }
            $temp = day::where("request_id","=",$item->id)->get();

            foreach($temp as $item){
                $marcar[$item->date] = $item->date;
            }
        }
        $especiales = 0;
        $T = alter::where("periodo_id","=",$periodo->id)->get();

        foreach ($T as $item){
            $especiales = $especiales + $item->days;
        }

        return view("periodo.show",[
            "periodo" => $periodo,
            "almanaque" => $t,
            "domingos" => $sundays,
            "disponibles" => $this->disponibles($user->id),
            "usados" => $usados,
            "especiales" => $especiales,
            "marcar" => $marcar,
            "nextno" => true,
            "prevno" => true
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = User::find(Auth::id());

        $center = Carbon::parse($user->ingreso)->format("Y-m-d");
        $actual = Carbon::create(Carbon::now()->year,$center->month,$center->day);

        $periodo = $user->periodos()->where("start","<=",$actual)->where("end",">=",$actual)->first();

        if($periodo == null){
            $periodo = new periodo();

            $periodo->user_id = $user->id;
            $periodo->start = $actual->toDateString();
            $periodo->end = $actual->addDays(364)->toDateString();

            $periodo->save();
        }

        $start    = (new DateTime($periodo->start))->modify('first day of this month');
        $end      = (new DateTime($periodo->end))->modify('last day of this month');
        $interval = DateInterval::createFromDateString('1 month');
        $period   = new DatePeriod($start, $interval, $end);
        $intevalday  = DateInterval::createFromDateString('1 day');

        $sundays = array();

        $before = new DatePeriod($start, $intevalday, new DateTime($periodo->start));
        foreach ($before as $item){
            $sundays[$item->format('Y-m-d')] = $item->format('Y-m-d');
            foreach (forbid::whereYear("day",$item->format("Y"))->get() as $item2){
                $sundays[$item2->day] = $item2->day;
            }
        }

        $after = new DatePeriod(new DateTime($periodo->end), $intevalday, $end);
        foreach ($after as $item){
            $sundays[$item->format('Y-m-d')] = $item->format('Y-m-d');
            foreach (forbid::whereYear("day",$item->format("Y"))->get() as $item2){
                $sundays[$item2->day] = $item2->day;
            }
        }
        foreach ($period as $dt) {
            $mes = array(
                "01" => "Ene",
                "02" => "Feb",
                "03" => "Mar",
                "04" => "Abr",
                "05" => "May",
                "06" => "Jun",
                "07" => "Jul",
                "08" => "Ago",
                "09"=> "Sep",
                "10" => "Oct",
                "11" => "Nov",
                "12" => "Dic"
            );

            $t[] = [$dt->format('Y-m'),$dt->format('Y')."-".$mes[$dt->format('m')]];

            if(!checkdate($dt->format("m"),29,$dt->format("Y"))){
                $sundays[$dt->format("Y-m")."-29"] =  $dt->format("Y-m")."-29";
            }
            if(!checkdate($dt->format("m"),31,$dt->format("Y"))){
                $sundays[$dt->format("Y-m")."-31"] =  $dt->format("Y-m")."-31";
            }
            if(!checkdate($dt->format("m"),30,$dt->format("Y"))){
                $sundays[$dt->format("Y-m")."-31"] =  $dt->format("Y-m")."-31";
            }

        }

        while ($start <= $end) {
            if ($start->format('w') == 0 || $start->format("w") == 6) {
                $sundays[$start->format('Y-m-d')] = $start->format('Y-m-d');
            }

            $start->modify('+1 day');
        }
        //dd($sundays);
        $periodo->requests = $periodo->dispensas()->where("autorizacion2","=",2)->where("autorizacion3","=",2)->get();

        $usados = 0;
        $marcar = [];




        foreach($periodo->requests as $item) {
            $usados = $usados + count(day::where("request_id","=",$item->id)->get());
            $temp = day::where("request_id","=",$item->id)->get();

            foreach($temp as $item){
                $marcar[$item->date] = $item->date;
            }
        }
        $especiales = 0;
        $T = alter::where("periodo_id","=",$periodo->id)->get();

        foreach ($T as $item){
            $especiales = $especiales + $item->days;
        }

        return view("periodo.create",[
            "periodo" => $periodo,
            "almanaque" => $t,
            "domingos" => $sundays,
            "licencias" => licence::all()->where("habilitado","=",1),
            "disponibles" => $this->disponibles($user->id),
            "usados" => $usados,
            "especiales" => $especiales,
            "marcar" => $marcar
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
            "licencia" => "required",
            "dia" => "required",
            "description" => "required",
            "periodo" => "required"
        ]);

        $sol = new sol();

        $sol->user_id = Auth::id();
        $sol->licence_id = $data["licencia"];
        $sol->description = $data["description"];
        if($request->hasFile("file")){
            $sol->file = $request->file("file")->store("app/dispensas");
        }
        $sol->periodo_id = $request->periodo;

        $ids = DB::table("sub_menu_user")->distinct()->where("sub_menu_id", "=", 14)->get();

        foreach($ids as $item){
            $user = User::find($item->user_id);
            Mail::to($user->email)->send(new \App\Mail\nueva_dispensa(""));
        }

        $sol->save();

        foreach($data["dia"] as $item){
            $dia = new day();
            $dia->request_id = $sol->id;
            $dia->date = $item;
            $dia->save();
        }

        return redirect("/misDispensas/solicitudes");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    public function misSolicitudes(){

        $icono = [
          0 => "far fa-times fa-2x",
          1 => "far fa-clock",
          2 => "far fa-check fa-2x"
        ];


        return view("periodo.misSolicitudes",[
            "solicitudes" => sol::all()->where("user_id","=",Auth::id()),
            "icono" => $icono
        ]);
    }

    public function detail($id){
        $sol = sol::findOrFail($id);
        $sol->days = $sol->days()->get();
        $sol->licence = DB::table("licences")->select("description")->where("id","=",$sol->licence_id)->first();
        return view("periodo.detail",[
            "periodo" => $sol
        ]);
    }

    public function auto2(){
        $icono = [
            0 => "far fa-times fa-2x",
            1 => "far fa-clock",
            2 => "far fa-check fa-2x"
        ];

        return view("periodo.lista2",[
            "solicitudes" => sol::where("autorizacion2","=",1)->orWhere("autorizacion3","=",1)->get(),
            "icono" => $icono
        ]);
    }

    public function auto3(){
        $icono = [
            0 => "far fa-times fa-2x",
            1 => "far fa-clock",
            2 => "far fa-check fa-2x"
        ];

        return view("periodo.lista3",[
            "solicitudes" => sol::where("autorizacion2","=",2)->Where("autorizacion3","=",1)->get(),
            "icono" => $icono
        ]);
    }

    public function detail2($id){
        $sol = sol::findOrFail($id);
        $sol->user = $sol->user()->first();
        $sol->dias = $sol->days()->get();
        $sol->licence = DB::table("licences")->select("description")->where("id","=",$sol->licence_id)->first();

        return view("periodo.definir2",[
            "solicitud" => $sol
        ]);
    }

    public function detail3($id){
        $sol = sol::findOrFail($id);
        $sol->user = $sol->user()->first();
        $sol->dias = $sol->days()->get();
        $sol->licence = DB::table("licences")->select("description")->where("id","=",$sol->licence_id)->first();

        return view("periodo.definir3",[
            "solicitud" => $sol
        ]);
    }

    public function checkfile($id){
        $sol = sol::findOrFail($id);

        return response()->file(storage_path("app/".$sol->file));
    }

    public function definir2(Request $request){
        $data = $request->validate([
            "id" => "required",
            "respuesta" => "required",
            "estado" => "required"
        ]);

        $sol = sol::findOrFail($data["id"]);

        $sol->observacion2 = $data["respuesta"];
        $sol->autorizacion2 = $data["estado"];

        $user = User::find($sol->user_id);
        $R = [
            0 => "Rechazada",
            2 => "Aprobada"
        ];

        //Mail::to($user->email)->send(new \App\Mail\dispensa_2($R[$data["estado"]]));

        if($data["estado"] == 2){
            $ids = DB::table("sub_menu_user")->distinct()->where("sub_menu_id", "=", 15)->get();

            foreach($ids as $item){
                $user = User::find($item->user_id);
                Mail::to($user->email)->send(new \App\Mail\nueva_dispensa(""));
            }
        }


        $sol->save();
        $resp = ($data["estado"] == 2)? true : false;
        $icono = [
            0 => "far fa-times fa-2x",
            1 => "far fa-clock",
            2 => "far fa-check fa-2x"
        ];

        return view("periodo.lista2",[
            "solicitudes" => sol::where("autorizacion2","=",1)->orWhere("autorizacion3","=",1)->get(),
            "icono" => $icono,
            "resp" => $resp
        ]);

    }

    public function definir3(Request $request){
        $data = $request->validate([
            "id" => "required",
            "respuesta" => "required",
            "estado" => "required"
        ]);

        $sol = sol::findOrFail($data["id"]);

        $sol->observacion3 = $data["respuesta"];
        $sol->autorizacion3 = $data["estado"];

        $user = User::find($sol->user_id);
        $R = [
            0 => "Rechazada",
            2 => "Aprobada"
        ];

        Mail::to($user->email)->send(new \App\Mail\dispensa_3($R[$data["estado"]]));
        $sol->save();

        $resp = ($data["estado"] == 2)? true : false;
        $icono = [
            0 => "far fa-times fa-2x",
            1 => "far fa-clock",
            2 => "far fa-check fa-2x"
        ];

        return view("periodo.lista3",[
            "solicitudes" => sol::where("autorizacion2","=",1)->orWhere("autorizacion3","=",1)->get(),
            "icono" => $icono,
            "resp" => $resp
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

    public function disponibles($id){
        $user = User::findOrFail($id);
        $horas = $user->horas;
        $dedicacion = $user->dedication;
        $antiguedad = date_diff(new DateTime($user->ingreso),new DateTime(now()));
        $antiguedad = ($antiguedad->y > 15)?15:$antiguedad->y;

        $total = 0;
        switch($dedicacion){
            //exclusiva
            case 1:
                if($horas < 20){
                    $total = $total + 18;
                }else if($horas >= 20 && $horas < 30){
                    $total = $total + 23;
                }else{
                    $total = $total + 27;
                }
            break;
            //no exclusiva
            case 2:
                if($horas < 20){
                    $total = $total + 14;
                }else if($horas >= 20 && $horas < 30){
                    $total = $total + 19;
                }else{
                    $total = $total + 23;
                }
            break;
        }
        return $total + $antiguedad;
    }

    public function toeditperiod(){
        return view("periodo.extrauser",[
            "users" => User::all()
        ]);
    }

    public function editperiod($id){
        $user = User::find($id);
        $this->disponibles($user->id);
        $center = Carbon::parse($user->ingreso)->format("Y-m-d");
        $actual = Carbon::create(Carbon::now()->year,$center->month,$center->day);

        $periodo = $user->periodos()->where("start","<=",$actual)->where("end",">=",$actual)->first();

        if($periodo == null){
            $periodo = new periodo();

            $periodo->user_id = $user->id;
            $periodo->start = $actual->toDateString();
            $periodo->end = $actual->addDays(364)->toDateString();

            $periodo->save();
        }

        $user->periodos = $user->periodos()->where("start","<=",$actual)->where("end",">=",$actual)->first();

        return view("periodo.editperiod",[
            "user" => $user
        ]);
    }

    public function updateperiod(Request $request){
        $data = $request->validate([
            "id" => "required",
            "observacion" => "required",
            "cantidad" => "required"
        ]);
        $alter = new alter();

        $alter->periodo_id = $data["id"];
        $alter->description = $data["observacion"];
        $alter->days = $data["cantidad"];

        $temp = DB::table("periodos")->select("user_id")->where("id","=",$data["id"])->first();

        $user = User::findOrFail($temp->user_id);
        Mail::to($user->email)->send(new \App\Mail\extras(""));
        $alter->save();

        $extras = alter::all();
        foreach($extras as $item){
            $temp = $item->periodo()->first();
            $item->user = $temp->user()->first();
            $item->periodo = $item->periodo()->first();
        }
        return view("periodo.listedit",[
            "extras" => $extras,
            "resp" => true
        ]);
    }

    public function extras(){
        $extras = alter::all();
        foreach($extras as $item){
            $temp = $item->periodo()->first();
            $item->user = $temp->user()->first();
            $item->periodo = $item->periodo()->first();
        }
        return view("periodo.listedit",[
            "extras" => $extras
        ]);
    }

    public function checklist(){
        $users = User::all();

        return view("periodo.checklist",[
            "users" => $users
        ]);
    }

    public function checkuser($id){
        $user = User::findOrFail($id);
        $this->disponibles($user->id);
        $center = Carbon::parse($user->ingreso)->format("Y-m-d");
        $center = explode("-",$center);
        $actual = Carbon::create(Carbon::now()->year,$center[1],$center[2]);

        $periodo = $user->periodos()->where("start","<=",$actual)->where("end",">=",$actual)->first();

        if($periodo == null){
            $periodo = new periodo();

            $periodo->user_id = $user->id;
            $periodo->start = $actual->toDateString();
            $periodo->end = $actual->addDays(364)->toDateString();

            $periodo->save();
        }

        $start    = (new DateTime($periodo->start))->modify('first day of this month');
        $end      = (new DateTime($periodo->end))->modify('last day of this month');
        $interval = DateInterval::createFromDateString('1 month');
        $intevalday  = DateInterval::createFromDateString('1 day');
        //prohibite days
        $sundays = array();

        $before = new DatePeriod($start, $intevalday, new DateTime($periodo->start));
        foreach ($before as $item){
            $sundays[$item->format('Y-m-d')] = $item->format('Y-m-d');
            foreach (forbid::whereYear("day",$item->format("Y"))->get() as $item2){
                $sundays[$item2->day] = $item2->day;
            }
        }

        $after = new DatePeriod(new DateTime($periodo->end), $intevalday, $end);
        foreach ($after as $item){
            $sundays[$item->format('Y-m-d')] = $item->format('Y-m-d');
            foreach (forbid::whereYear("day",$item->format("Y"))->get() as $item2){
                $sundays[$item2->day] = $item2->day;
            }
        }


        $period   = new DatePeriod($start, $interval, $end);
        foreach ($period as $dt) {
            $mes = array(
                "01" => "Ene",
                "02" => "Feb",
                "03" => "Mar",
                "04" => "Abr",
                "05" => "May",
                "06" => "Jun",
                "07" => "Jul",
                "08" => "Ago",
                "09"=> "Sep",
                "10" => "Oct",
                "11" => "Nov",
                "12" => "Dic"
            );

            $t[] = [$dt->format('Y-m'),$dt->format('Y')."-".$mes[$dt->format('m')]];

            if(!checkdate($dt->format("m"),29,$dt->format("Y"))){
                $sundays[$dt->format("Y-m")."-29"] =  $dt->format("Y-m")."-29";
            }
            if(!checkdate($dt->format("m"),31,$dt->format("Y"))){
                $sundays[$dt->format("Y-m")."-31"] =  $dt->format("Y-m")."-31";
            }
            if(!checkdate($dt->format("m"),30,$dt->format("Y"))){
                $sundays[$dt->format("Y-m")."-31"] =  $dt->format("Y-m")."-31";
            }
        }


        while ($start <= $end) {
            if ($start->format('w') == 0 || $start->format("w") == 6) {
                $sundays[$start->format('Y-m-d')] = $start->format('Y-m-d');
            }

            $start->modify('+1 day');
        }
        //dd($sundays);
        $periodo->requests = $periodo->dispensas()->where("autorizacion2","=",2)->where("autorizacion3","=",2)->get();
        $usados = 0;
        $marcar = [];
        foreach($periodo->requests as $item) {
            $item->temporal = DB::table("licences")->where("id","=",$item->licence_id)->first();

            if($item->temporal->alter == 1){
                $usados = $usados + count(day::where("request_id","=",$item->id)->get());
            }

            $temp = day::where("request_id","=",$item->id)->get();

            foreach($temp as $item){
                $marcar[$item->date] = $item->date;
            }
        }
        $especiales = 0;
        $T = alter::where("periodo_id","=",$periodo->id)->get();

        foreach ($T as $item){
            $especiales = $especiales + $item->days;
        }

        return view("periodo.showuser",[
            "periodo" => $periodo,
            "almanaque" => $t,
            "domingos" => $sundays,
            "disponibles" => $this->disponibles($user->id),
            "usados" => $usados,
            "especiales" => $especiales,
            "marcar" => $marcar,
            "nextno" => true,
            "prevno" => true,
            "use" => $user
        ]);
    }

    public function prevd($id){
        $user = User::findOrFail($id);
        $this->disponibles($user->id);
        $center = Carbon::parse($user->ingreso)->format("Y-m-d");
        $actual = Carbon::create(Carbon::now()->year,$center->month,$center->day);
        $actual = $actual->subYear();

        $periodo = $user->periodos()->where("start","<=",$actual)->where("end",">=",$actual)->first();

        if($periodo == null){
            $periodo = new periodo();

            $periodo->user_id = $user->id;
            $periodo->start = $actual->toDateString();
            $periodo->end = $actual->addDays(364)->toDateString();

            $periodo->save();
        }

        $start    = (new DateTime($periodo->start))->modify('first day of this month');
        $end      = (new DateTime($periodo->end))->modify('last day of this month');
        $interval = DateInterval::createFromDateString('1 month');
        $intevalday  = DateInterval::createFromDateString('1 day');
        //prohibite days
        $sundays = array();

        $before = new DatePeriod($start, $intevalday, new DateTime($periodo->start));
        foreach ($before as $item){
            $sundays[$item->format('Y-m-d')] = $item->format('Y-m-d');
            foreach (forbid::whereYear("day",$item->format("Y"))->get() as $item2){
                $sundays[$item2->day] = $item2->day;
            }
        }

        $after = new DatePeriod(new DateTime($periodo->end), $intevalday, $end);
        foreach ($after as $item){
            $sundays[$item->format('Y-m-d')] = $item->format('Y-m-d');
            foreach (forbid::whereYear("day",$item->format("Y"))->get() as $item2){
                $sundays[$item2->day] = $item2->day;
            }
        }


        $period   = new DatePeriod($start, $interval, $end);
        foreach ($period as $dt) {
            $mes = array(
                "01" => "Ene",
                "02" => "Feb",
                "03" => "Mar",
                "04" => "Abr",
                "05" => "May",
                "06" => "Jun",
                "07" => "Jul",
                "08" => "Ago",
                "09"=> "Sep",
                "10" => "Oct",
                "11" => "Nov",
                "12" => "Dic"
            );

            $t[] = [$dt->format('Y-m'),$dt->format('Y')."-".$mes[$dt->format('m')]];

            if(!checkdate($dt->format("m"),29,$dt->format("Y"))){
                $sundays[$dt->format("Y-m")."-29"] =  $dt->format("Y-m")."-29";
            }
            if(!checkdate($dt->format("m"),31,$dt->format("Y"))){
                $sundays[$dt->format("Y-m")."-31"] =  $dt->format("Y-m")."-31";
            }
            if(!checkdate($dt->format("m"),30,$dt->format("Y"))){
                $sundays[$dt->format("Y-m")."-31"] =  $dt->format("Y-m")."-31";
            }
        }


        while ($start <= $end) {
            if ($start->format('w') == 0 || $start->format("w") == 6) {
                $sundays[$start->format('Y-m-d')] = $start->format('Y-m-d');
            }

            $start->modify('+1 day');
        }
        //dd($sundays);
        $periodo->requests = $periodo->dispensas()->where("autorizacion2","=",2)->where("autorizacion3","=",2)->get();
        $usados = 0;
        $marcar = [];
        foreach($periodo->requests as $item) {
            $item->temporal = DB::table("licences")->where("id","=",$item->licence_id)->first();

            if($item->temporal->alter == 1){
                $usados = $usados + count(day::where("request_id","=",$item->id)->get());
            }
            $temp = day::where("request_id","=",$item->id)->get();

            foreach($temp as $item){
                $marcar[$item->date] = $item->date;
            }
        }
        $especiales = 0;
        $T = alter::where("periodo_id","=",$periodo->id)->get();

        foreach ($T as $item){
            $especiales = $especiales + $item->days;
        }

        return view("periodo.showuser",[
            "periodo" => $periodo,
            "almanaque" => $t,
            "domingos" => $sundays,
            "disponibles" => $this->disponibles($user->id),
            "usados" => $usados,
            "especiales" => $especiales,
            "marcar" => $marcar,
            "nextno" => true,
            "prevno" => false,
            "use" => $user
        ]);
    }

    public function nextd($id){
        $user = User::findOrFail($id);
        $this->disponibles($user->id);
        $center = Carbon::parse($user->ingreso)->format("Y-m-d");
        $actual = Carbon::create(Carbon::now()->year,$center->month,$center->day);
        $actual = $actual->addDays(365);

        $periodo = $user->periodos()->where("start","<=",$actual)->where("end",">=",$actual)->first();

        if($periodo == null){
            $periodo = new periodo();

            $periodo->user_id = $user->id;
            $periodo->start = $actual->toDateString();
            $periodo->end = $actual->addDays(364)->toDateString();

            $periodo->save();
        }

        $start    = (new DateTime($periodo->start))->modify('first day of this month');
        $end      = (new DateTime($periodo->end))->modify('last day of this month');
        $interval = DateInterval::createFromDateString('1 month');
        $intevalday  = DateInterval::createFromDateString('1 day');
        //prohibite days
        $sundays = array();

        $before = new DatePeriod($start, $intevalday, new DateTime($periodo->start));
        foreach ($before as $item){
            $sundays[$item->format('Y-m-d')] = $item->format('Y-m-d');
            foreach (forbid::whereYear("day",$item->format("Y"))->get() as $item2){
                $sundays[$item2->day] = $item2->day;
            }
        }

        $after = new DatePeriod(new DateTime($periodo->end), $intevalday, $end);
        foreach ($after as $item){
            $sundays[$item->format('Y-m-d')] = $item->format('Y-m-d');
            foreach (forbid::whereYear("day",$item->format("Y"))->get() as $item2){
                $sundays[$item2->day] = $item2->day;
            }
        }


        $period   = new DatePeriod($start, $interval, $end);
        foreach ($period as $dt) {
            $mes = array(
                "01" => "Ene",
                "02" => "Feb",
                "03" => "Mar",
                "04" => "Abr",
                "05" => "May",
                "06" => "Jun",
                "07" => "Jul",
                "08" => "Ago",
                "09"=> "Sep",
                "10" => "Oct",
                "11" => "Nov",
                "12" => "Dic"
            );

            $t[] = [$dt->format('Y-m'),$dt->format('Y')."-".$mes[$dt->format('m')]];

            if(!checkdate($dt->format("m"),29,$dt->format("Y"))){
                $sundays[$dt->format("Y-m")."-29"] =  $dt->format("Y-m")."-29";
            }
            if(!checkdate($dt->format("m"),31,$dt->format("Y"))){
                $sundays[$dt->format("Y-m")."-31"] =  $dt->format("Y-m")."-31";
            }
            if(!checkdate($dt->format("m"),30,$dt->format("Y"))){
                $sundays[$dt->format("Y-m")."-31"] =  $dt->format("Y-m")."-31";
            }
        }


        while ($start <= $end) {
            if ($start->format('w') == 0 || $start->format("w") == 6) {
                $sundays[$start->format('Y-m-d')] = $start->format('Y-m-d');
            }

            $start->modify('+1 day');
        }
        //dd($sundays);
        $periodo->requests = $periodo->dispensas()->where("autorizacion2","=",2)->where("autorizacion3","=",2)->get();
        $usados = 0;
        $marcar = [];
        foreach($periodo->requests as $item) {
            $item->temporal = DB::table("licences")->where("id","=",$item->licence_id)->first();

            if($item->temporal->alter == 1){
                $usados = $usados + count(day::where("request_id","=",$item->id)->get());
            }
            $temp = day::where("request_id","=",$item->id)->get();

            foreach($temp as $item){
                $marcar[$item->date] = $item->date;
            }
        }
        $especiales = 0;
        $T = alter::where("periodo_id","=",$periodo->id)->get();

        foreach ($T as $item){
            $especiales = $especiales + $item->days;
        }

        return view("periodo.showuser",[
            "periodo" => $periodo,
            "almanaque" => $t,
            "domingos" => $sundays,
            "disponibles" => $this->disponibles($user->id),
            "usados" => $usados,
            "especiales" => $especiales,
            "marcar" => $marcar,
            "nextno" => false,
            "prevno" => true,
            "use" => $user
        ]);
    }

    public function anio(){
        $user = User::findOrFail(Auth::id());
        $actual = Carbon::create(Carbon::now()->year,"01","01");


        $start    = (new DateTime(Carbon::now()->year."-01-01"));
        $end      = (new DateTime(Carbon::now()->year."-12-31"));
        $interval = DateInterval::createFromDateString('1 month');
        $intevalday  = DateInterval::createFromDateString('1 day');
        //prohibite days
        $sundays = array();

        $before = new DatePeriod($start, $intevalday, new DateTime(Carbon::now()->year."-01-01"));
        foreach ($before as $item){
            $sundays[$item->format('Y-m-d')] = $item->format('Y-m-d');
            foreach (forbid::whereYear("day",$item->format("Y"))->get() as $item2){
                $sundays[$item2->day] = $item2->day;
            }
        }

        $after = new DatePeriod(new DateTime(Carbon::now()->year."-12-31"), $intevalday, $end);
        foreach ($after as $item){
            $sundays[$item->format('Y-m-d')] = $item->format('Y-m-d');
            foreach (forbid::whereYear("day",$item->format("Y"))->get() as $item2){
                $sundays[$item2->day] = $item2->day;
            }
        }


        $period   = new DatePeriod($start, $interval, $end);
        foreach ($period as $dt) {
            $mes = array(
                "01" => "Ene",
                "02" => "Feb",
                "03" => "Mar",
                "04" => "Abr",
                "05" => "May",
                "06" => "Jun",
                "07" => "Jul",
                "08" => "Ago",
                "09"=> "Sep",
                "10" => "Oct",
                "11" => "Nov",
                "12" => "Dic"
            );

            $t[] = [$dt->format('Y-m'),$dt->format('Y')."-".$mes[$dt->format('m')]];

            if(!checkdate($dt->format("m"),29,$dt->format("Y"))){
                $sundays[$dt->format("Y-m")."-29"] =  $dt->format("Y-m")."-29";
            }
            if(!checkdate($dt->format("m"),31,$dt->format("Y"))){
                $sundays[$dt->format("Y-m")."-31"] =  $dt->format("Y-m")."-31";
            }
            if(!checkdate($dt->format("m"),30,$dt->format("Y"))){
                $sundays[$dt->format("Y-m")."-31"] =  $dt->format("Y-m")."-31";
            }
        }


        while ($start <= $end) {
            if ($start->format('w') == 0 || $start->format("w") == 6) {
                $sundays[$start->format('Y-m-d')] = $start->format('Y-m-d');
            }

            $start->modify('+1 day');
        }
        //dd($sundays);
        $bucket = User::all()->where("specialty", "=", $user->specialty);
        $marcar = [];

        foreach($bucket as $item){

            $periodo = $item->periodos()->whereYear("start",Carbon::now()->year)->orwhereYear("end",Carbon::now()->year)->get();

            if($periodo == null){
                $periodo = new periodo();

                $cen = Carbon::parse($item->ingreso)->format("Y-m-d");
                $act = Carbon::create(Carbon::now()->year,$cen->month,$cen->day);

                $periodo->user_id = $item->id;
                $periodo->start = $act->toDateString();
                $periodo->end = $act->addDays(364)->toDateString();

                $periodo->save();

                $periodo = $item->periodos()->where("start","<=",Carbon::now()->year."-01-01")->where("end",">=",Carbon::now()->year."-12-31")->get();
            }

            foreach ($periodo as $per){
                $req = $per->dispensas()->where("autorizacion2","=",2)->where("autorizacion3","=",2)->get();

                foreach($req as $IT) {

                    $temp = day::where("request_id","=",$IT->id)->get();

                    foreach($temp as $item){
                        $marcar[$item->date] = $item->date;
                    }
                }
            }

        }

        return view("periodo.estadoanual",[
            "periodo" => $periodo,
            "almanaque" => $t,
            "domingos" => $sundays,
            "disponibles" => $this->disponibles($user->id),
            "marcar" => $marcar,
            "nextno" => true,
            "prevno" => true,
            "use" => $user
        ]);

    }

}
