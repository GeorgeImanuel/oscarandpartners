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
            'object_id' => 'required|exists:objects,id',
            'coverage_id' => 'required|exists:coverages,id',
            
            // --- FIX 1: Tambahkan Currency Code agar tidak hilang ---
            'currency_code' => 'required|string|exists:currencies,code',
            
            'coverage_percentage' => 'required|numeric|min:0.0000001|max:100',
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
            'percentage.*' => 'required|numeric|min:0.0000001|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'object_id.exists' => 'The selected object is invalid.',
            'currency_code.required' => 'Mata uang wajib dipilih.',
            'currency_code.exists' => 'Mata uang yang dipilih tidak valid.',
            'coverage_code.exists' => 'The selected coverage code is invalid.',
            'coverage_percentage.required' => 'Coverage percentage is required.',
            'coverage_percentage.numeric' => 'Coverage percentage must be a number.',
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

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $insurances = $this->input('insurance_id', []);
            $newInsurances = $this->input('insurance_new_name', []);
            $isMain = $this->input('is_main', []);

            // Cek apakah ada input asuransi (baik ID maupun Nama Baru)
            $hasInsurance = false;
            foreach ($insurances as $val) { if (!empty($val)) $hasInsurance = true; }
            foreach ($newInsurances as $val) { if (!empty($val)) $hasInsurance = true; }

            if ($hasInsurance) {
                // Validasi manual: Pastikan ada yang dicentang sebagai main
                if (empty($isMain)) {
                    $validator->errors()->add('is_main', 'Salah satu asuransi harus dipilih sebagai Asuransi Utama.');
                }
            }
        });
    }
}