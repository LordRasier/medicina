<?php

namespace App\Http\Controllers;

use App\event;
use App\inbox;
use App\mail;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class MailController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

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
        $data = Input::all();
        $user = Auth::user();

        foreach ($data["destiny"] as $destiny) {
            $mail = new mail();
            $mail->origin = $user->id;
            $mail->asunto = $data["asunto"];
            $mail->body = $data["body"];
            $mail->destiny = $destiny;

            $mail->save();
        }

        return redirect("/home");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\mail  $mail
     * @return \Illuminate\Http\Response
     */
    public function show(mail $mail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\mail  $mail
     * @return \Illuminate\Http\Response
     */
    public function edit(mail $mail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\mail  $mail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, mail $mail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\mail  $mail
     * @return \Illuminate\Http\Response
     */
    public function destroy(mail $mail)
    {
        //
    }

    public function inbox(){
        $user = Auth::user();

        $inbox = inbox::where([["did","=",$user->id],["oid","!=",$user->id]])->orderBy("id","desc")->get();

        return view('mail',[
            "mails" => $inbox,
            "route" => "Bandeja de entrada"
        ]);
    }

    public function outbox(){
        $user = Auth::user();

        $outbox = inbox::where([["did","!=",$user->id],["oid","=",$user->id]])->groupBy("asunto","oid","did","id")->orderBy("id","desc")->get();

        return view('mail',[
            "mails" => $outbox,
            "route" => "Mensajes Enviados"
        ]);
    }
}
