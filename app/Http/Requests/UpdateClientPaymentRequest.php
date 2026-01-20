<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientPaymentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'paid_at'               => 'required|date',
            'paid_amount'           => 'required|numeric|min:0',
        ];
    }
}