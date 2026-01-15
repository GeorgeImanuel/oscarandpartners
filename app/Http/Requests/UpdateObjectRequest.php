<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateObjectRequest extends FormRequest
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
            'object_type_id' => 'nullable|exists:object_occupations,id',
            'object_type_name' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'part_id' => 'nullable|array',
            'part_name' => 'nullable|array',
            'part_name.*' => 'nullable|string|max:255',
            'part_value' => 'nullable|array',
            'part_value.*' => 'nullable|numeric|min:0',
        ];
    }
}
