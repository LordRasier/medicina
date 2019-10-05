<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class espacio extends Model
{
    public function solicitudes(){
        return $this->belongsToMany(espacio::class,"espacios_pivot","espacio_id","user_id");
    }
}
