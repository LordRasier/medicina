<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class request extends Model
{
    public const REJECTED = 0;
    public const PENDING = 1;
    public const APPROVED = 2;

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function days(){
        return $this->hasMany(day::class);
    }

    public function licence()
    {
        return $this->belongsTo(licence::class);
    }

    public function periodo()
    {
        return $this->belongsTo(periodo::class);
    }

    public function scopeWaitingSecondAuthorization($query)
    {
        return $query->where(function ($query) {
            $query->where('autorizacion2', self::PENDING)
                ->orWhere('autorizacion3', self::PENDING);
        });
    }

    public function scopeWaitingThirdAuthorization($query)
    {
        return $query
            ->where('autorizacion2', self::APPROVED)
            ->where('autorizacion3', self::PENDING);
    }

    public function scopeResolved($query)
    {
        return $query
            ->where('autorizacion2', '!=', self::PENDING)
            ->where('autorizacion3', '!=', self::PENDING);
    }

}
