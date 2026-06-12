<?php

namespace App\Http\Requests\Dispensas;

use Illuminate\Foundation\Http\FormRequest;

class StorePeriodExtraDaysRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => 'required|integer|exists:periodos,id',
            'observacion' => 'required|string',
            'cantidad' => 'required|integer|min:1',
        ];
    }
}
