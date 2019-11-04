<?php

namespace App\Http\Controllers;

use App\day;
use App\forbid;
use App\periodo;
use App\User;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Http\Request;

class ForbidController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $start    = (new DateTime(Carbon::now()->year."-01-01"));
        $end      = (new DateTime(Carbon::now()->year."-12-31"));
        $interval = DateInterval::createFromDateString('1 month');
        $intevalday  = DateInterval::createFromDateString('1 day');
        //prohibite days
        $sundays = array();

        $before = new DatePeriod($start, $intevalday, new DateTime(Carbon::now()->year."-01-01"));
        foreach ($before as $item){
            $sundays[$item->format('Y-m-d')] = $item->format('Y-m-d');
        }

        $after = new DatePeriod(new DateTime(Carbon::now()->year."-12-31"), $intevalday, $end);
        foreach ($after as $item){
            $sundays[$item->format('Y-m-d')] = $item->format('Y-m-d');
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

        $marcar = [];

        foreach (forbid::whereYear("day",Carbon::now()->year)->get() as $item){
            $marcar[$item->day] = $item->day;
        }

        return view("forbid.calendar",
        [
            "actual" => Carbon::now()->year,
            "domingos" => $sundays,
            "almanaque" => $t,
            "marcar" => $marcar
        ]
        );
    }

    public function change($anio){
        $start    = (new DateTime($anio."-01-01"));
        $end      = (new DateTime($anio."-12-31"));
        $interval = DateInterval::createFromDateString('1 month');
        $intevalday  = DateInterval::createFromDateString('1 day');
        //prohibite days
        $sundays = array();

        $before = new DatePeriod($start, $intevalday, new DateTime($anio."-01-01"));
        foreach ($before as $item){
            $sundays[$item->format('Y-m-d')] = $item->format('Y-m-d');
        }

        $after = new DatePeriod(new DateTime($anio."-12-31"), $intevalday, $end);
        foreach ($after as $item){
            $sundays[$item->format('Y-m-d')] = $item->format('Y-m-d');
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

        $marcar = [];

        foreach (forbid::whereYear("day",$anio)->get() as $item){
            $marcar[$item->day] = $item->day;
        }


        return view("forbid.calendar",
            [
                "actual" => $anio,
                "domingos" => $sundays,
                "almanaque" => $t,
                "marcar" => $marcar
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            "dia" => "required",
            "anio" => "required"
        ]);

        $all = forbid::whereYear("day",$data["anio"]);

        foreach($all as $item){
            $item->delete();
        }

        foreach ($data["dia"] as $item){
            $forbid = new forbid();
            $forbid->day = $item;
            $forbid->save();
        }

        return redirect("/configuracion/forbid");

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
        //
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
        //
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
