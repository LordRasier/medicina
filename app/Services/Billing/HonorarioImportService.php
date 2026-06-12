<?php

namespace App\Services\Billing;

use App\honorario;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Throwable;

class HonorarioImportService
{
    private HonorarioNotifier $notifier;

    public function __construct(HonorarioNotifier $notifier)
    {
        $this->notifier = $notifier;
    }

    /**
     * Imports honorario files whose names include date and professional code.
     */
    public function import(iterable $files): array
    {
        $summary = [
            'processed' => 0,
            'imported' => 0,
            'skipped' => 0,
        ];

        foreach ($files as $file) {
            $summary['processed']++;
            $parsedFile = $this->parseFileName($file);

            if ($parsedFile == null) {
                $summary['skipped']++;
                continue;
            }

            $user = User::where('code', $parsedFile['user_code'])->first();

            if ($user == null) {
                $summary['skipped']++;
                continue;
            }

            $honorario = honorario::firstOrNew([
                'user_id' => $user->id,
                'fecha' => $parsedFile['date'],
            ]);
            $honorario->file = $file->store('app/honorarios');
            $honorario->save();

            $this->notifier->notifyUploaded($user);
            $summary['imported']++;
        }

        return $summary;
    }

    private function parseFileName(UploadedFile $file): ?array
    {
        $parts = explode(' - ', $file->getClientOriginalName());

        if (count($parts) < 3) {
            return null;
        }

        try {
            $date = Carbon::parse($parts[1])->toDateString();
        } catch (Throwable $exception) {
            return null;
        }

        $userCode = explode('-', $parts[2])[0];

        return [
            'date' => $date,
            'user_code' => $userCode,
        ];
    }
}
