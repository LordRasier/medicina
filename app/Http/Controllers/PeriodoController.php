<?php

namespace App\Http\Controllers;

use App\espacio;
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

class PeriodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::find(Auth::id());

        $center = Carbon::parse($user->ingreso);
        $actual = Carbon::create(Carbon::now()->year,$center->month,$center->day);

        $periodo = $user->periodos()->where("start","<=",$actual)->where("end",">=",$actual)->first();

        if($periodo == null){
            $periodo = new periodo();

            $periodo->user_id = $user->id;
            $periodo->start = $actual->toDateString();
            $periodo->end = $actual->addDays(365)->toDateString();

            $periodo->save();
        }

        $start    = (new DateTime($periodo->start))->modify('first day of this month');
        $end      = (new DateTime($periodo->end))->modify('last day of this month');
        $interval = DateInterval::createFromDateString('1 month');
        $period   = new DatePeriod($start, $interval, $end);
        foreach ($period as $dt) {
            $t[] = $dt->format("Y-m");
        }

        $sundays = array();
        while ($start <= $end) {
            if ($start->format('w') == 0 || $start->format("w") == 6) {
                $sundays[$start->format('Y-m-d')] = $start->format('Y-m-d');
            }

            $start->modify('+1 day');
        }
        //dd($sundays);
        $periodo->requests = $periodo->dispensas();

        return view("periodo.show",[
            "periodo" => $periodo,
            "almanaque" => $t,
            "domingos" => $sundays
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

        $center = Carbon::parse($user->ingreso);
        $actual = Carbon::create(Carbon::now()->year,$center->month,$center->day);

        $periodo = $user->periodos()->where("start","<=",$actual)->where("end",">=",$actual)->first();

        if($periodo == null){
            $periodo = new periodo();

            $periodo->user_id = $user->id;
            $periodo->start = $actual->toDateString();
            $periodo->end = $actual->addDays(365)->toDateString();

            $periodo->save();
        }

        $start    = (new DateTime($periodo->start))->modify('first day of this month');
        $end      = (new DateTime($periodo->end))->modify('last day of this month');
        $interval = DateInterval::createFromDateString('1 month');
        $period   = new DatePeriod($start, $interval, $end);
        foreach ($period as $dt) {
            $t[] = $dt->format("Y-m");
        }

        $sundays = array();
        while ($start <= $end) {
            if ($start->format('w') == 0 || $start->format("w") == 6) {
                $sundays[$start->format('Y-m-d')] = $start->format('Y-m-d');
            }

            $start->modify('+1 day');
        }
        //dd($sundays);
        $periodo->requests = $periodo->dispensas();

        return view("periodo.create",[
            "periodo" => $periodo,
            "almanaque" => $t,
            "domingos" => $sundays,
            "licencias" => licence::all()
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

}
