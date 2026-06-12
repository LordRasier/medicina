<?php

namespace App\Http\Requests\Espacios;

use Illuminate\Foundation\Http\FormRequest;

class StoreSolicitudEspacioRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'fecha' => 'required|date',
            'horario' => 'required|string',
            'espacio' => 'required|exists:espacios,id',
            'detalle' => 'required|string',
        ];
    }
}
