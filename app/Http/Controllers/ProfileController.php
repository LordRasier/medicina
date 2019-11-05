<?php

namespace App\Http\Controllers;

use App\dedication;
use App\event;
use App\menu;
use App\specialty;
use App\sub_menu;
use App\type;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;

class ProfileController extends Controller
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
        $user = Auth::user();
        return view("user.userList",[
            "user" =>Auth::user(),
            "users" => User::all(),
            "events" => event::all()->where("user",Auth::id()),
            "menus" => $user->access_list()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $menus = menu::all();
        foreach ($menus as $item){
            $item->hijos = $item->sub_menu()->get();
        }
        $user = Auth::user();
        return view("user.new",[
            "types" => type::all(),
            "specialtys" => specialty::all(),
            "dedications" => dedication::all(),
            "acceso" => $menus
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
            "name" => "required",
            "doc" => "required|min:8|unique:users,doc",
            "email" => "required|email|unique:users,email",
            "access" => "required",
            "horas" => "required",
            "dedication" => "required",
            "tipo" => "required",
            "especialidad" => "required",
            "codigo" => "required",
            "ingreso" => "required"
        ]);

        $new = new User();
        $new->name = $data["name"];
        $new->email = $data["email"];
        $new->doc = $data["doc"];
        $new->password = Hash::make($data["doc"]);
        $new->horas = $data["horas"];
        $new->dedication = $data["dedication"];
        $new->type = $data["tipo"];
        $new->specialty = $data["especialidad"];
        $new->code = $data["codigo"];
        $new->ingreso = Carbon::parse($data["ingreso"])->format("Y-m-d");
        $new->save();

        foreach ($data["access"] as $item){
            $new->access()->attach($item);
        }

        return redirect("/users");
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
        $edit = User::findOrFail($id);
        $edit->ingreso = Carbon::parse($edit->ingreso)->format("d/m/Y");

        $menus = menu::all();
        foreach ($menus as $item){
            $item->hijos = $item->sub_menu()->get();
        }

        return view("user.edit",[
            "edit" => $edit,
            "check" => $edit->access()->get(),
            "types" => type::all(),
            "especialidad" => specialty::all(),
            "dedications" => dedication::all(),
            "acceso" => $menus
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
        $data = $request->validate([
            "name" => "required",
            "doc" => "required|min:8",
            "email" => "required|email",
            "access" => "required",
            "horas" => "required",
            "dedication" => "required",
            "tipo" => "required",
            "especialidad" => "required",
            "codigo" => "required",
            "ingreso" => "required"
        ]);

        $user = User::findOrFail($id);

        $user->name = $data["name"];
        $user->doc = $data["doc"];
        $user->email = $data["email"];
        $user->horas = $data["horas"];
        $user->dedication = $data["dedication"];
        $user->type = $data["tipo"];
        $user->specialty = $data["especialidad"];
        $user->code = $data["codigo"];
        $temp = explode("/",$data["ingreso"]);
        $user->ingreso = $temp[2]."-".$temp[1]."-".$temp[0];

        $menus = sub_menu::all();
        foreach ($menus as $item){
            $user->access()->detach($item->id);
        }
        foreach($data["access"] as $item){
            $user->access()->attach($item);
        }
        $user->save();
        return redirect("/users");
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect("/users");
    }

    public function pic(Request $request){
        $user = user::findOrFail(Auth::user()->id);

        $user->profile = basename($request->file("pic")->store("/public/profile"));
        $user->save();

        return redirect("/home");

    }
}
