<?php

namespace App\Services\Periodos;

use App\alter;
use App\periodo;

class PeriodExtraDaysService
{
    private PeriodExtraDaysNotifier $notifier;

    public function __construct(PeriodExtraDaysNotifier $notifier)
    {
        $this->notifier = $notifier;
    }

    public function assign(periodo $period, string $description, int $days): alter
    {
        $extra = new alter();
        $extra->periodo_id = $period->id;
        $extra->description = $description;
        $extra->days = $days;
        $extra->save();

        $period->loadMissing('user');
        $this->notifier->notifyAssigned($period->user);

        return $extra;
    }

    public function listWithUsers()
    {
        return alter::with('periodo.user')
            ->orderByDesc('id')
            ->get()
            ->each(function ($extra) {
                $extra->user = optional($extra->periodo)->user;
            });
    }
}
