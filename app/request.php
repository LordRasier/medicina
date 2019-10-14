<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class request extends Model
{
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function days(){
        return $this->hasMany(day::class);
    }
}
