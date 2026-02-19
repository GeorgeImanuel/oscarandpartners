<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetClaimsRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Ubah ke false jika ada permission khusus
    }

    public function rules()
    {
        return [
            'year' => 'required|integer|digits:4|min:2020|max:' . (date('Y') + 1),
        ];
    }
}