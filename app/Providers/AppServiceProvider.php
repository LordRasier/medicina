<?php

namespace App\Providers;

use App\event;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {


        View::composer("layouts.app",function ($view){
            $user = User::find(Auth::id());
            $view->with([
                "user" => Auth::user(),
                "users" => User::all(),
                "events" => event::all()->where("user",Auth::id()),
                "menus" => $user->access_list()
            ]);
        });
    }
}
