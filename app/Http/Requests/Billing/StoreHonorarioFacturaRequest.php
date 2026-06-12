<?php

namespace App\Http\Requests\Billing;

use Illuminate\Foundation\Http\FormRequest;

class StoreHonorarioFacturaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'honorario' => 'required|integer|exists:honorarios,id',
        ];
    }
}
