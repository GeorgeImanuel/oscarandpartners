<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSettingRequest extends FormRequest
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
            'commision_percentage' => 'required|string|max:255',
            'tax_percentage' => 'required|string|max:255',
        ];
    }
    /**
         * Get custom validation messages.
         *
         * @return array<string, string>
         */
        public function messages(): array
        {
        return [
            'commision_percentage.required' => 'Kode harus diisi.',
            'commision_percentage.string' => 'Kode harus berupa teks.',
            'commision_percentage.max' => 'Kode maksimal 10 karakter.',

            'tax_percentage.required' => 'Nama harus diisi.',
            'tax_percentage.string' => 'Nama harus berupa teks.',
            'tax_percentage.max' => 'Nama maksimal 255 karakter.',
        ];
        }
}
