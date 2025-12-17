<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInsurancesRequest extends FormRequest
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
            'name' => 'required|string|max:10',
            'bank_account' => 'required|string|max:255',
            'va_number' => 'required|string|max:255',
            'default_commission_pct' => 'required|string|max:5,2',
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
            'name.required' => 'Nama harus diisi.',
            'name.string' => 'Nama harus berupa teks.',
            'name.max' => 'Nama maksimal 10 karakter.',
            'bank_account.required' => 'Akun Bank harus diisi.',
            'bank_account.string' => 'Akun Bank harus berupa teks.',
            'bank_account.max' => 'Akun Bank maksimal 255 karakter.',
            'va_number.required' => 'Nomor VA harus diisi.',
            'va_number.string' => 'Nomor VA harus berupa teks.',
            'va_number.max' => 'Nomor VA maksimal 255 karakter.',
            'default_commission_pct.required' => 'Komisi harus diisi.',
            'default_commission_pct.decimal' => 'Komisi harus berupa nomor decimal.',
            'default_commission_pct.max' => 'Komisi maksimal 5,2.',
        ];
    }
}
