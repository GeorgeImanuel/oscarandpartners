<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInsurancePaymentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'insurance_paid_at'        => 'required|date',
            'insurance_paid_amount'    => 'required|numeric|min:0',
        ];
    }
}