<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePoliciesRequest extends FormRequest
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
        $policyId = $this->route('policy'); 

        return [
            'id' => [
                'required',
                'string',
                'max:10',
            ],
            'insurance_id' => 'required|string|max:255|exists:insurances,id',
            'parent_policy_id' => 'nullable|string|max:255|exists:policies,id',
            'object_type_id' => 'required|string|max:255|exists:object_types,id',
            'policy_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('policies', 'policy_number')->ignore($policyId),
            ],
            'object' => 'required|string|max:255',
            'object_address' => 'required|string|max:255',
            'object_value' => 'required|numeric|min:0',
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
            'id.required' => 'ID wajib diisi.',
            'id.string' => 'ID harus berupa teks.',
            'id.max' => 'ID maksimal 10 karakter.',

            'insurance_id.required' => 'ID Asuransi wajib diisi.',
            'insurance_id.string' => 'ID Asuransi harus berupa teks.',
            'insurance_id.max' => 'ID Asuransi maksimal 255 karakter.',
            'insurance_id.exists' => 'ID Asuransi yang dipilih tidak valid atau tidak ditemukan.',

            'parent_policy_id.string' => 'ID Polis Induk harus berupa teks.',
            'parent_policy_id.max' => 'ID Polis Induk maksimal 255 karakter.',
            'parent_policy_id.exists' => 'ID Polis Induk yang dipilih tidak valid atau tidak ditemukan.',

            'object_type_id.required' => 'Tipe Objek wajib diisi.',
            'object_type_id.string' => 'Tipe Objek harus berupa teks.',
            'object_type_id.max' => 'Tipe Objek maksimal 255 karakter.',
            'object_type_id.exists' => 'Tipe Objek yang dipilih tidak valid atau tidak ditemukan.',

            'policy_number.required' => 'Nomor Polis wajib diisi.',
            'policy_number.string' => 'Nomor Polis harus berupa teks.',
            'policy_number.max' => 'Nomor Polis maksimal 255 karakter.',
            'policy_number.unique' => 'Nomor Polis ini sudah terdaftar.',

            'object.required' => 'Objek wajib diisi.',
            'object.string' => 'Objek harus berupa teks.',
            'object.max' => 'Objek maksimal 255 karakter.',

            'object_address.required' => 'Alamat Objek wajib diisi.',
            'object_address.string' => 'Alamat Objek harus berupa teks.',
            'object_address.max' => 'Alamat Objek maksimal 255 karakter.',

            'object_value.required' => 'Nilai Objek wajib diisi.',
            'object_value.numeric' => 'Nilai Objek harus berupa angka.',
            'object_value.min' => 'Nilai Objek minimal adalah 0.',
        ];
    }
}
