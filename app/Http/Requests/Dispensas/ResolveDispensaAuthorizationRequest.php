<?php

namespace App\Http\Requests\Dispensas;

use App\request as DispensaRequest;
use Illuminate\Foundation\Http\FormRequest;

class ResolveDispensaAuthorizationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => 'required|integer|exists:requests,id',
            'respuesta' => 'required|string',
            'estado' => 'required|in:' . DispensaRequest::REJECTED . ',' . DispensaRequest::APPROVED,
        ];
    }
}
