<?php

namespace App\Http\Controllers;

use App\factura;
use App\honorario;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class FacturaController extends Controller
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
        return view("factura.index",[
            "facturas" => factura::all()
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function MisFacturas(){
        return view("factura.misFacturas",[
            "facturas" => factura::all()->where("user_id","=",Auth::id())
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        return view("factura.create",["honorario" => $id]);
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
            "file" => "file",
            "honorario" => "required"
        ]);

        $honorario = honorario::fndOrFail($data["honorario"]);
        $honorario->factura = $request->file("file")->store("app/facturas");

        $honorario->save();

        return redirect("/misHonorarios");

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $factura = factura::findOrFail($id);

        return response()->file(storage_path("app/".$factura->file));
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
        $factura = factura::findOrFail($id);
        $factura->delete();

        return redirect("/misFacturas");
    }
}
