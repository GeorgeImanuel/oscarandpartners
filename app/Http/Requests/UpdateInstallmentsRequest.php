<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInstallmentsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Set false jika perlu check permission
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'payment_type_id' => 'required|exists:payment_types,id',
            'installments' => 'required|array|min:1|max:12',
            'installments.*.amount' => 'required|numeric|min:0',
            'installments.*.due_date' => 'required|date',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'payment_type_id' => 'tipe pembayaran',
            'installments' => 'cicilan',
            'installments.*.amount' => 'nilai cicilan',
            'installments.*.due_date' => 'tanggal jatuh tempo',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'installments.required' => 'Minimal harus ada 1 cicilan',
            'installments.min' => 'Minimal harus ada 1 cicilan',
            'installments.max' => 'Maksimal 12 cicilan',
            'installments.*.amount.required' => 'Nilai cicilan harus diisi',
            'installments.*.amount.numeric' => 'Nilai cicilan harus berupa angka',
            'installments.*.amount.min' => 'Nilai cicilan tidak boleh negatif',
            'installments.*.due_date.required' => 'Tanggal jatuh tempo harus diisi',
            'installments.*.due_date.date' => 'Format tanggal tidak valid',
        ];
    }
}
