<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function solicitud_espacio(){
        return $this->belongsToMany(espacio::class,"espacios_pivot","user_id","espacio_id");
    }

    public function events(){
        return $this->hasMany(event::class);
    }

    public function access(){
        return $this->belongsToMany(sub_menu::class,"sub_menu_user","user_id","sub_menu_id");
    }

    public function dedication(){
        return $this->belongsTo(dedication::class);
    }

    public function specialty(){
        return $this->belongsTo(specialty::class);
    }

    public function type(){
        return $this->belongsTo(type::class);
    }

    public function access_list(){
        $user = User::find(Auth::id());
        $sub_menu = $user->access()->get();

        $menus = array();

        foreach ($sub_menu as $item) {
            if(!isset($menus[$item->menu_id])){
                $padre = menu::find($item->menu_id);
                $menus[$padre->id] = array(
                    "name" => $padre->name,
                    "icon" => $padre->icon,
                    "hijos" => array()
                );
            }
            $menus[$item->menu_id]["hijos"][] = array(
                "name" => $item->name,
                "url" =>$item->url
            );
        }

        return $menus;
    }
}
