<?php

namespace App\Http\Controllers;

use App\espacio;
use App\event;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EspacioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("espacio.list",[
            "espacios" => espacio::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("espacio.new");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate(
            ["description" => "required|min: 1|unique:espacios,description"]
        );

        $espacio = new espacio();
        $espacio->description = $data["description"];
        $espacio->save();

        return redirect("/espacios/list");
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
        $edit = espacio::findOrFail($id);
        return view("espacio.edit",[
            "edit" => $edit
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
        $edit = espacio::findOrFail($id);
        $data = $request->validate(
            ["description" => "required|min: 1|unique:espacios,description"]
        );

        $edit->description = $data["description"];
        $edit->save();

        return redirect("/espacios/list");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $edit = espacio::findOrFail($id);
        $edit->delete();

        return redirect("/espacios/list");
    }

}
