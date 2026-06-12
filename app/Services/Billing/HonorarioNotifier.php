<?php

namespace App\Services\Billing;

use App\Mail\honorario as HonorarioMail;
use App\User;
use Illuminate\Support\Facades\Mail;

class HonorarioNotifier
{
    public function notifyUploaded(User $user): void
    {
        Mail::to($user->email)->send(new HonorarioMail('It works!'));
    }
}
