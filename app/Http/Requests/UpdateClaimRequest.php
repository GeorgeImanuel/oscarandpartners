<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClaimRequest extends FormRequest
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
            'policy_id' => 'required|exists:policies,id',
            'incident_date' => 'required|date',
            'insurance_report_date' => 'required|date|after_or_equal:incident_date',
            'chronology' => 'required|string',
            'docs' => 'required|string',
            'casco' => 'required|numeric|min:0',
            'tjh' => 'required|numeric|min:0',
            'note' => 'nullable|string',
            'payment_date' => 'nullable|date',
            
            // PERUBAHAN PENTING DI SINI:
            // Menggunakan 'nullable' agar tidak error saat dikosongkan
            'amount' => 'nullable|numeric|min:0',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function messages(): array
    {
        return [
            'policy_id.required' => 'Polis harus dipilih.',
            'policy_id.exists' => 'Polis tidak valid.',
            'incident_date.required' => 'Tanggal kejadian harus diisi.',
            'incident_date.date' => 'Format tanggal kejadian tidak valid.',
            'insurance_report_date.required' => 'Tanggal lapor asuransi harus diisi.',
            'insurance_report_date.date' => 'Format tanggal lapor tidak valid.',
            'insurance_report_date.after_or_equal' => 'Tanggal lapor harus setelah atau sama dengan tanggal kejadian.',
            'chronology.required' => 'Kronologi kejadian harus diisi.',
            'docs.required' => 'Dokumen yang diperlukan harus diisi.',
            'casco.required' => 'Nilai claim casco harus diisi.',
            'casco.numeric' => 'Nilai claim casco harus berupa angka.',
            'casco.min' => 'Nilai claim casco tidak boleh negatif.',
            'tjh.required' => 'Nilai claim TJH harus diisi.',
            'tjh.numeric' => 'Nilai claim TJH harus berupa angka.',
            'tjh.min' => 'Nilai claim TJH tidak boleh negatif.',
            'payment_date.date' => 'Format tanggal bayar tidak valid.',
            
            // Pesan error amount (jika diisi tapi format salah)
            'amount.numeric' => 'Jumlah claim harus berupa angka.',
            'amount.min' => 'Jumlah claim tidak boleh negatif.',
        ];
    }
}