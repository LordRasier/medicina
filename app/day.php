<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class day extends Model
{
    public function request(){
        return $this->belongsTo(request::class);
    }
}
