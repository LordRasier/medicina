<?php

namespace App\Http\Requests\Billing;

use Illuminate\Foundation\Http\FormRequest;

class StoreHonorariosRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'file' => 'required|array',
            'file.*' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
        ];
    }
}
