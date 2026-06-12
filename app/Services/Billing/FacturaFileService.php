<?php

namespace App\Services\Billing;

use App\honorario;
use App\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FacturaFileService
{
    public function uploadForHonorario(honorario $honorario, UploadedFile $file): honorario
    {
        if ($honorario->factura != null && Storage::exists($honorario->factura)) {
            Storage::delete($honorario->factura);
        }

        $honorario->factura = $file->store('app/facturas');
        $honorario->save();

        return $honorario;
    }

    public function deleteForHonorario(honorario $honorario): honorario
    {
        if ($honorario->factura != null && Storage::exists($honorario->factura)) {
            Storage::delete($honorario->factura);
        }

        $honorario->factura = null;
        $honorario->save();

        return $honorario;
    }

    public function viewablePathFor(int $id, User $user): string
    {
        $honorario = honorario::findOrFail($id);
        $this->authorizeOwnerOrBillingAdmin((int) $honorario->user_id, $user);
        abort_unless($honorario->factura != null && Storage::exists($honorario->factura), 404);

        return $honorario->factura;
    }

    private function authorizeOwnerOrBillingAdmin(int $ownerId, User $user): void
    {
        abort_unless(
            $ownerId === (int) $user->id || $user->canAccessAnyMenu(['/honorarios']),
            403
        );
    }
}
