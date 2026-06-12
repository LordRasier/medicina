<?php

namespace App\Services\Periodos;

use App\Mail\extras;
use App\User;
use Illuminate\Support\Facades\Mail;

class PeriodExtraDaysNotifier
{
    public function notifyAssigned(User $user): void
    {
        Mail::to($user->email)->send(new extras(''));
    }
}
