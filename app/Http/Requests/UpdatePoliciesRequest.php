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
        $id = $this->route('policy');

        return [
            'object_id' => 'required|exists:objects,id',
            'coverage_id' => 'required|exists:coverages,id',
            'coverage_percentage' => 'required|numeric|min:0.0000001|max:100',
            'policy_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('policies', 'policy_number')->ignore($id)
            ],
            'started_date' => 'required|date',
            'ended_date' => 'required|date|after_or_equal:started_date',
            
            'insurance_id' => 'required|array|min:1',
            'percentage' => 'required|array|min:1',
            'percentage.*' => 'required|numeric|min:0.0000001|max:100',
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
            'object_id.exists' => 'The selected object is invalid.',
            'coverage_id.exists' => 'The selected coverage code is invalid.',
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

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $insurances = $this->input('insurance_id', []);
            $newInsurances = $this->input('insurance_new_name', []);
            $isMain = $this->input('is_main', []);

            // Cek apakah ada data asuransi yang diinput
            $hasInsurance = false;
            foreach ($insurances as $val) { if (!empty($val)) $hasInsurance = true; }
            foreach ($newInsurances as $val) { if (!empty($val)) $hasInsurance = true; }

            if ($hasInsurance) {
                // Jika ada asuransi, pastikan ada yang dipilih sebagai utama
                if (empty($isMain)) {
                    $validator->errors()->add('is_main', 'Salah satu asuransi harus dipilih sebagai Asuransi Utama.');
                }
            }
        });
    }
}
