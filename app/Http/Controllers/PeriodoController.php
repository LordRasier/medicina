<?php

namespace App\Http\Controllers;

use App\espacio;
use App\event;
use App\periodo;
use App\User;
use Carbon\Carbon;
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

        $periodo->requests = $periodo->dispensas();

        return view("periodo.show",[
            "periodo" => $periodo
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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
