<?php

namespace App\Services\Dispensas;

use App\request as DispensaRequest;

class DispensaAuthorizationService
{
    private DispensaRequestNotifier $notifier;

    public function __construct(DispensaRequestNotifier $notifier)
    {
        $this->notifier = $notifier;
    }

    public function resolveSecondLevel(DispensaRequest $request, int $status, string $response): DispensaRequest
    {
        $request->observacion2 = $response;
        $request->autorizacion2 = $status;
        $request->save();

        if ($status === DispensaRequest::APPROVED) {
            $this->notifier->notifyThirdAuthorizers();
        }

        return $request;
    }

    public function resolveThirdLevel(DispensaRequest $request, int $status, string $response): DispensaRequest
    {
        $request->loadMissing('user');
        $request->observacion3 = $response;
        $request->autorizacion3 = $status;
        $request->save();

        $this->notifier->notifyFinalResolution($request, $this->statusText($status));

        return $request;
    }

    private function statusText(int $status): string
    {
        return ($status === DispensaRequest::APPROVED) ? 'Aprobada' : 'Rechazada';
    }
}
