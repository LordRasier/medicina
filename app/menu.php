<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class menu extends Model
{
    public function sub_menu(){
        return $this->hasMany(sub_menu::class);
    }
}
