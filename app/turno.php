<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class turno extends Model
{
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function espacio(){
        return $this->belongsTo(espacio::class);
    }
}
