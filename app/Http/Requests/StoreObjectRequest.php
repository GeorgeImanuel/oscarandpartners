<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreObjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'object_occupation_id' => 'nullable|exists:object_occupation,id',
            'object_occupation_name' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'keterangan' => 'required|string',
            'interest_id' => 'nullable|array',
            'interest_name' => 'nullable|array',
            'interest_name.*' => 'nullable|string|max:255',
            'interest_value' => 'nullable|array',
            'interest_value.*' => 'nullable|numeric|min:0',
        ];
    }
}
