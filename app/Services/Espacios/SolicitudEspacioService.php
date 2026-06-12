<?php

namespace App\Services\Espacios;

use App\SolicitudEspacio;
use App\User;

class SolicitudEspacioService
{
    private SolicitudEspacioNotifier $notifier;

    public function __construct(SolicitudEspacioNotifier $notifier)
    {
        $this->notifier = $notifier;
    }

    /**
     * Creates a space request only when the requested slot is still available.
     */
    public function create(User $requester, array $data): ?SolicitudEspacio
    {
        if (SolicitudEspacio::approvedSlot((int) $data['espacio'], $data['fecha'], $data['horario'])->exists()) {
            return null;
        }

        $solicitud = SolicitudEspacio::create([
            'user_id' => $requester->id,
            'espacio_id' => $data['espacio'],
            'fecha' => $data['fecha'],
            'horario' => $data['horario'],
            'detalle' => $data['detalle'],
        ]);

        $this->notifier->notifyAuthorizers($requester);

        return $solicitud;
    }

    /**
     * Resolves a request and closes competing pending requests when approved.
     */
    public function resolve(SolicitudEspacio $solicitud, int $status, string $response): SolicitudEspacio
    {
        $solicitud->autorizado = $status;
        $solicitud->respuesta = $response;
        $solicitud->save();

        if ($status === SolicitudEspacio::APPROVED) {
            $this->notifier->notifyApproved($solicitud);
            $this->rejectCompetingPendingRequests($solicitud);

            return $solicitud;
        }

        $this->notifier->notifyRejected($solicitud);

        return $solicitud;
    }

    private function rejectCompetingPendingRequests(SolicitudEspacio $solicitud): void
    {
        SolicitudEspacio::pending()
            ->where('id', '!=', $solicitud->id)
            ->where('espacio_id', $solicitud->espacio_id)
            ->where('fecha', $solicitud->fecha)
            ->where('horario', $solicitud->horario)
            ->update([
                'autorizado' => SolicitudEspacio::REJECTED,
                'respuesta' => 'Otro usuario fue autorizado',
            ]);
    }
}
