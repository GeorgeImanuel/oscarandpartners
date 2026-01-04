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
    public function rules(): array
    {
        return [
            'object_id' => 'nullable|exists:objects,id',
            'coverage_code' => 'nullable|exists:coverages,code',
            'coverage_percentage' => 'required|numeric',
            'policy_number' => 'required|string|max:255|unique:policies,policy_number',
            'started_date' => 'required|date',
            'ended_date' => 'required|date|after_or_equal:started_date',
            'insurance_id' => 'nullable|array',
            'insurance_id.*' => 'nullable|integer|exists:insurances,id',
            'is_main' => 'nullable|array',
            'percentage' => 'nullable|array',
            'percentage.*' => 'nullable|numeric|min:0|max:100',
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
}
