<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class licence extends Model
{
    public function solicitudes()
    {
        return $this->hasMany(request::class);
    }
}
