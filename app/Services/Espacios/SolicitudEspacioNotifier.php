<?php

namespace App\Services\Espacios;

use App\Mail\resolucion_espacio;
use App\Mail\resolucion_negativa;
use App\Mail\solicitud_espacio;
use App\SolicitudEspacio;
use App\User;
use Illuminate\Support\Facades\Mail;

class SolicitudEspacioNotifier
{
    public function notifyAuthorizers(User $requester): void
    {
        $authorizers = User::where('specialty', $requester->specialty)
            ->whereHas('access', function ($query) {
                $query->where('url', '/espacios/solicitudes/autorizar');
            })
            ->get();

        foreach ($authorizers as $user) {
            Mail::to($user->email)->send(new solicitud_espacio('It works!'));
        }
    }

    public function notifyApproved(SolicitudEspacio $solicitud): void
    {
        Mail::to($solicitud->user->email)->send(new resolucion_espacio(''));
    }

    public function notifyRejected(SolicitudEspacio $solicitud): void
    {
        Mail::to($solicitud->user->email)->send(new resolucion_negativa(''));
    }
}
