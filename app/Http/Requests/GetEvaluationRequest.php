<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetEvaluationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'year_current' => 'required|integer|digits:4', // Misal 2025
            'year_compare' => 'required|integer|digits:4', // Misal 2024
        ];
    }
}