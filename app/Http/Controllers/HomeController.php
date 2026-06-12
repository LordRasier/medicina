<?php

namespace App\Http\Controllers;

use App\Services\Dashboard\DashboardService;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    private DashboardService $dashboard;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(DashboardService $dashboard)
    {
        $this->middleware('auth');
        $this->dashboard = $dashboard;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();

        return view('home', [
            'dashboard' => $this->dashboard->forUser($user),
        ]);
    }
}
