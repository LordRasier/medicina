<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SesionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function logout(){
        Auth::logout();

        return redirect("/login");
    }
}
