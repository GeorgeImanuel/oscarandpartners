<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePoliciesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Ambil ID policy dari route untuk pengecualian unique validation
        $id = $this->route('policy'); 
        // Jika route parameter kamu namanya 'policies', ganti jadi $this->route('policies')

        return [
            'object_id'   => 'required|array|min:1',
            'object_id.*' => 'required|exists:objects,id',

            'coverage_code' => 'required|array|min:1',
            'coverage_code.*' => 'required|string|exists:coverages,code',
            'coverage_percentage' => 'required|array|min:1',
            'coverage_percentage.*' => 'required|numeric|min:0|max:100',
            'currency_code' => 'nullable|string|exists:currencies,code',
            'policy_number' => [
                'required',
                'string',
                'max:255',
                // Pastikan validasi unique mengabaikan ID yang sedang diedit
                Rule::unique('policies', 'policy_number')->ignore($id) 
            ],
            'started_date' => 'required|date',
            'ended_date' => 'required|date|after_or_equal:started_date',
            
            // --- FIX 2: Validasi Asuransi Komplit (Copy dari Store) ---
            'insurance_id' => 'required|array|min:1',
            'insurance_id.*' => 'required_without:insurance_new_name.*|nullable|integer|exists:insurances,id',
            'insurance_new_name.*' => 'required_without:insurance_id.*|nullable|string|max:255',
            
            // --- FIX 3: Izinkan is_main lolos validasi ---
            'is_main' => 'nullable|array', 
            
            'percentage' => 'required|array|min:1',
            'percentage.*' => 'required|numeric|min:0|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'object_id.required' => 'Objek wajib dipilih minimal satu.',
            'object_id.*.exists' => 'Objek yang dipilih tidak valid.',

            'currency_code.exists' => 'Mata uang yang dipilih tidak valid.',
            
            'coverage_code.required' => 'Coverage wajib diisi.',
            'coverage_code.*.required' => 'Coverage wajib dipilih.',
            'coverage_code.*.exists' => 'Coverage yang dipilih tidak valid.',
            
            'coverage_percentage.required' => 'Persentase Coverage wajib diisi.',
            'coverage_percentage.*.required' => 'Persentase Coverage wajib diisi.',
            'coverage_percentage.*.numeric' => 'Persentase Coverage harus berupa angka.',
            'coverage_percentage.*.min' => 'Persentase Coverage minimal 0.',
            'coverage_percentage.*.max' => 'Persentase Coverage maksimal 100.',
            
            'policy_number.required' => 'Policy number is required.',
            'policy_number.string' => 'Policy number must be a string.',
            'policy_number.max' => 'Policy number may not be greater than 255 characters.',
            'policy_number.unique' => 'The policy number has already been taken.',
            'started_date.required' => 'Start date is required.',
            'started_date.date' => 'Start date is not a valid date.',
            'ended_date.required' => 'End date is required.',
            'ended_date.date' => 'End date is not a valid date.',
            'ended_date.after_or_equal' => 'End date must be after or equal to start date.',
            'insurance_id.array' => 'Insurance ID must be an array.',
            'insurance_id.*.integer' => 'Insurance ID must be an integer.',
            'insurance_id.*.exists' => 'The selected insurance ID is invalid.',
            'is_main.array' => 'Main status must be an array.',
            'percentage.array' => 'Percentage must be an array.',
            'percentage.*.numeric' => 'Percentage must be a number.',
            'percentage.*.min' => 'Percentage must be at least 0.',
            'percentage.*.max' => 'Percentage may not be greater than 100.',
        ];
    }

}