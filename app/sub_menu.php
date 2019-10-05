<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class sub_menu extends Model
{
    public function users(){
        return $this->belongsToMany(User::class,"sub_menu_user","sub_menu_id","user_id");
    }
}
