<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class alter extends Model
{

    public function periodo(){
        return $this->belongsTo(periodo::class);
    }
}
