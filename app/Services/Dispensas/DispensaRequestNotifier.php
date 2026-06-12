<?php

namespace App\Services\Dispensas;

use App\Mail\dispensa_3;
use App\Mail\nueva_dispensa;
use App\request as DispensaRequest;
use App\User;
use Illuminate\Support\Facades\Mail;

class DispensaRequestNotifier
{
    public function notifySecondAuthorizers(): void
    {
        $this->notifyUsersWithAccess('/autorizaciones/2');
    }

    public function notifyThirdAuthorizers(): void
    {
        $this->notifyUsersWithAccess('/autorizaciones/3');
    }

    public function notifyFinalResolution(DispensaRequest $request, string $statusText): void
    {
        Mail::to($request->user->email)->send(new dispensa_3($statusText));
    }

    private function notifyUsersWithAccess(string $url): void
    {
        $users = User::whereHas('access', function ($query) use ($url) {
            $query->where('url', $url);
        })->get();

        foreach ($users as $user) {
            Mail::to($user->email)->send(new nueva_dispensa(''));
        }
    }
}
