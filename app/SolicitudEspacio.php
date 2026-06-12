<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SolicitudEspacio extends Model
{
    public const PENDING = 0;
    public const APPROVED = 1;
    public const REJECTED = 2;

    protected $table = 'espacios_pivot';

    protected $fillable = [
        'user_id',
        'espacio_id',
        'horario',
        'detalle',
        'respuesta',
        'fecha',
        'autorizado',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function espacio()
    {
        return $this->belongsTo(espacio::class);
    }

    public function scopePending($query)
    {
        return $query->where('autorizado', self::PENDING);
    }

    public function scopeResolved($query)
    {
        return $query->where('autorizado', '!=', self::PENDING);
    }

    public function scopeApprovedSlot($query, int $espacioId, string $fecha, string $horario)
    {
        return $query
            ->where('espacio_id', $espacioId)
            ->where('fecha', $fecha)
            ->where('horario', $horario)
            ->where('autorizado', self::APPROVED);
    }
}
