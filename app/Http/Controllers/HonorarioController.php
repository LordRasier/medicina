<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\factura;
use App\honorario;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HonorarioController extends Controller
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
        return view("honorario.index",["honorarios" => honorario::all()]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function MisHonorarios(){
        $user = User::findOrFail(Auth::id());

        return view("honorario.listado",["honorarios" => $user->honorarios()->get()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("honorario.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $files = $request->file("file");


            foreach($files as $file){
                $temp = explode(" - ",$file->getClientOriginalName());
                $date =  Carbon::parse($temp[1])->toDateString();
                $user = User::where("code","=",explode("-",$temp[2])[0])->first();

                $honorario = honorario::firstOrNew(["user_id" => $user->id,"fecha" => $date]);
                $honorario->file = $file->store("app/honorarios");

                $honorario->save();

            }

        return redirect("/honorarios");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $honorario = honorario::findOrFail($id);

        return response()->file(storage_path("app/".$honorario->file));
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
