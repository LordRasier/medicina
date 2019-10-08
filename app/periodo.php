<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class periodo extends Model
{
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function dispensas(){
        return $this->hasMany(request::class);
    }
}
