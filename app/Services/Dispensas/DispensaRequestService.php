<?php

namespace App\Services\Dispensas;

use App\day;
use App\periodo;
use App\request as DispensaRequest;
use App\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class DispensaRequestService
{
    private DispensaRequestNotifier $notifier;

    public function __construct(DispensaRequestNotifier $notifier)
    {
        $this->notifier = $notifier;
    }

    /**
     * Creates a dispensa request for the owner's period and registers each selected day.
     */
    public function create(User $requester, periodo $period, array $data, ?UploadedFile $file = null): DispensaRequest
    {
        abort_unless((int) $period->user_id === (int) $requester->id, 403);

        $request = DB::transaction(function () use ($requester, $period, $data, $file) {
            $request = new DispensaRequest();
            $request->user_id = $requester->id;
            $request->licence_id = $data['licencia'];
            $request->description = $data['description'];
            $request->periodo_id = $period->id;

            if ($file != null) {
                $request->file = $file->store('app/dispensas');
            }

            $request->save();

            foreach ($data['dia'] as $date) {
                $day = new day();
                $day->request_id = $request->id;
                $day->date = $date;
                $day->save();
            }

            return $request;
        });

        $this->notifier->notifySecondAuthorizers();

        return $request;
    }
}
