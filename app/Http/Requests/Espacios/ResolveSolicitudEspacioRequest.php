<?php

namespace App\Http\Requests\Espacios;

use App\SolicitudEspacio;
use Illuminate\Foundation\Http\FormRequest;

class ResolveSolicitudEspacioRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => 'required|integer|exists:espacios_pivot,id',
            'estado' => 'required|in:' . SolicitudEspacio::APPROVED . ',' . SolicitudEspacio::REJECTED,
            'respuesta' => 'required|string',
        ];
    }
}
