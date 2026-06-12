<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class espacio extends Model
{
    public function solicitudes(){
        return $this->hasMany(SolicitudEspacio::class);
    }
}
