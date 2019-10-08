<?php

namespace App\Http\Controllers;

use App\factura;
use App\honorario;
use Carbon\Carbon;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HonorarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("honorarios.index",["honorarios" => honorario::all()]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function MisHonorarios(){
        $user = User::findOrFail(Auth::id());

        return view("honorarios.listado",["honorarios" => $user->honorarios()->get()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("honorarios.create");
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
        if($request->hasFile("file")){
            foreach($files as $file){
                $temp = explode($file->name);
                $user = User::where("code",$temp)->get();

                $honorario = honorario::firstOrNew(["user_id" => $user->id],["date" => $temp]);
                $honorario->user_id = $user->id;
                $honorario->date = Carbon::parse($temp)->toDateString();
                $honorario->file = $file->store("app/honorarios");

                $honorario->save();
            }
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
