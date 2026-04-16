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
            'object_id'   => 'required|array|min:1',
            'object_id.*' => 'required|exists:objects,id',

            'coverage_code' => 'nullable|array',
            'coverage_code.*' => 'nullable|string|exists:coverages,code',
            'coverage_percentage' => 'nullable|array',
            'coverage_percentage.*' => 'nullable|numeric|min:0|max:100',
            'object_coverage_code' => 'nullable|array',
            'object_coverage_code.*' => 'nullable|array',
            'object_coverage_code.*.*' => 'nullable|string|exists:coverages,code',
            'object_coverage_percentage' => 'nullable|array',
            'object_coverage_percentage.*' => 'nullable|array',
            'object_coverage_percentage.*.*' => 'nullable|numeric|min:0|max:100',
            'policy_number' => 'required|string|max:255|unique:policies,policy_number',
            'started_date' => 'required|date',
            'ended_date' => 'required|date|after_or_equal:started_date',
            'currency_code' => 'nullable|string|exists:currencies,code', 

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

            'object_id.required' => 'Objek wajib dipilih minimal satu.',
            'object_id.*.exists' => 'Objek yang dipilih tidak valid.',

            'coverage_code.required' => 'Coverage wajib diisi.',
            'coverage_code.*.required' => 'Coverage wajib dipilih.',
            'coverage_code.*.exists' => 'Coverage yang dipilih tidak valid.',

            'coverage_percentage.required' => 'Persentase Coverage wajib diisi.',
            'coverage_percentage.*.required' => 'Persentase Coverage wajib diisi.',
            'coverage_percentage.*.numeric' => 'Persentase Coverage harus berupa angka.',
            'coverage_percentage.*.min' => 'Persentase Coverage minimal 0.0000001.',
            'coverage_percentage.*.max' => 'Persentase Coverage maksimal 100.',

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

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $hasPolicyCoverage = false;
            foreach ((array) $this->input('coverage_code', []) as $coverageCode) {
                if (!empty($coverageCode)) {
                    $hasPolicyCoverage = true;
                    break;
                }
            }

            $hasObjectCoverage = false;
            $objectCoverageCodes = (array) $this->input('object_coverage_code', []);
            foreach ($objectCoverageCodes as $rows) {
                if (!is_array($rows)) {
                    continue;
                }

                foreach ($rows as $coverageCode) {
                    if (!empty($coverageCode)) {
                        $hasObjectCoverage = true;
                        break 2;
                    }
                }
            }

            if (!$hasPolicyCoverage && !$hasObjectCoverage) {
                $validator->errors()->add(
                    'coverage_code',
                    'Coverage polis atau coverage per obyek wajib diisi minimal satu.'
                );
            }
        });
    }

}
