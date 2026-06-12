<?php

namespace App\Http\Requests\Dispensas;

use Illuminate\Foundation\Http\FormRequest;

class StoreDispensaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'licencia' => 'required|integer|exists:licences,id',
            'dia' => 'required|array|min:1',
            'dia.*' => 'required|date',
            'description' => 'required|string',
            'periodo' => 'required|integer|exists:periodos,id',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ];
    }
}
