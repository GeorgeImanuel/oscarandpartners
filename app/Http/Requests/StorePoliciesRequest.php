<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePoliciesRequest extends FormRequest
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
     */
    // App\Http\Requests\StorePoliciesRequest.php

    public function rules(): array
    {
        return [
            'object_id' => 'required|exists:objects,id',
            'coverage_id' => 'required|exists:coverages,id',
            'coverage_percentage' => 'required|numeric|min:0.0000001|max:100',
            'policy_number' => 'required|string|max:255|unique:policies,policy_number',
            'started_date' => 'required|date',
            'ended_date' => 'required|date|after_or_equal:started_date',
            'currency_code' => 'required|string|exists:currencies,code', 

            // Detail Asuransi
            'insurance_id' => 'required|array|min:1',
            'insurance_id.*' => 'required_without:insurance_new_name.*|nullable|integer|exists:insurances,id',
            'insurance_new_name.*' => 'required_without:insurance_id.*|nullable|string|max:255',
            
            // Hapus 'is_main' dari rules array utama jika ingin validasi custom di withValidator,
            // TAPI sebaiknya biarkan 'nullable|array' disini agar lolos validated()
            'is_main' => 'nullable|array', 
            
            'percentage' => 'required|array|min:1',
            'percentage.*' => 'required|numeric|min:0.0000001|max:100',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'policy_number.required' => 'Nomor Polis wajib diisi.',
            'policy_number.unique' => 'Nomor Polis ini sudah terdaftar.',
            'policy_number.max' => 'Nomor Polis maksimal 255 karakter.',

            'object_id.exists' => 'Objek yang dipilih tidak valid.',

            'coverage_code.exists' => 'Coverage yang dipilih tidak valid.',

            'coverage_percentage.required' => 'Persentase Coverage wajib diisi.',
            'coverage_percentage.numeric' => 'Persentase Coverage harus berupa angka.',

            'started_date.required' => 'Tanggal Mulai wajib diisi.',
            'started_date.date' => 'Tanggal Mulai harus format tanggal yang valid.',

            'ended_date.required' => 'Tanggal Selesai wajib diisi.',
            'ended_date.date' => 'Tanggal Selesai harus format tanggal yang valid.',
            'ended_date.after_or_equal' => 'Tanggal Selesai harus sama atau lebih besar dari Tanggal Mulai.',

            'insurance_id.*.exists' => 'Asuransi yang dipilih tidak valid.',

            'percentage.*.numeric' => 'Persentase Asuransi harus berupa angka.',
            'percentage.*.min' => 'Persentase Asuransi minimal 0.',
            'percentage.*.max' => 'Persentase Asuransi maksimal 100.',
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
