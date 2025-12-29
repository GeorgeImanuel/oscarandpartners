<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCoverageRequest extends FormRequest
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
            'code' => 'required|string|max:10',
            'name' => 'required|string|max:10',
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
            'code.required' => 'Kode harus diisi.',
            'code.string' => 'Kode harus berupa teks.',
            'code.max' => 'Kode maksimal 10 karakter.',

            'name.required' => 'Nama harus diisi.',
            'name.string' => 'Nama harus berupa teks.',
            'name.max' => 'Nama maksimal 10 karakter.',
        ];
    }
}
