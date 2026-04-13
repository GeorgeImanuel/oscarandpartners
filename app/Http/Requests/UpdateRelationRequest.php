<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRelationRequest extends FormRequest
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
            'code' => 'required|string|unique:relations,code,' . $this->route('relation'),
            'name' => 'required|string',
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
            'code.unique' => 'Kode sudah digunakan.',
            'name.required' => 'Nama harus diisi.',
            'name.string' => 'Nama harus berupa teks.',
        ];
    }
}
