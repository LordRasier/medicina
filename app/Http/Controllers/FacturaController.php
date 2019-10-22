<?php

namespace App\Http\Controllers;

use App\factura;
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
    public function create()
    {
        return view("factura.create");
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
            "fecha" => "required",
            "numero" => "required | unique:facturas,numero",
            "observacion" => "required",
            "file" => "file"
        ]);

        $factura = new factura();

        $factura->user_id = Auth::id();
        $factura->fecha = Carbon::parse($data["fecha"])->toDateString();
        $factura->numero = $data["numero"];
        $factura->description = $data["observacion"];
        $factura->file = $request->file("file")->store("app/facturas");

        $ids = DB::table("sub_menu_user")->distinct()->where("sub_menu_id", "=", 9)->get();

        foreach($ids as $item){
            $user = User::find($item->user_id);
            Mail::to($user->email)->send(new \App\Mail\factura(""));
        }
        $factura->save();

        return view("factura.misFacturas",[
            "facturas" => factura::all()->where("user_id","=",Auth::id()),
            "carga" => true
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
