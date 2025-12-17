<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductionCardRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_id' => 'required|exists:clients,id',
            'policy_id' => 'required|exists:policies,id',
            'card_status_id' => 'required|exists:card_statuses,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'commission_percentage' => 'required|numeric|min:0|max:100',
            'tax_percentage' => 'required|numeric|min:0|max:100',
            'premium_gross' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'admin_fee' => 'nullable|numeric|min:0',
            'commission_pct' => 'nullable|numeric|min:0|max:100',
            'commission_amount' => 'nullable|numeric|min:0',
            'net_to_insurance' => 'nullable|numeric|min:0',
            'premi_amount' => 'nullable|numeric|min:0',
            'polis_amount' => 'nullable|numeric|min:0',
            'client_total' => 'nullable|numeric|min:0',
            'commission_amount_initial' => 'nullable|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'commission_total' => 'nullable|numeric|min:0',
            'insurance_payment_status_id' => 'required|exists:payment_statuses,id',
            'insurance_paid_at' => 'nullable|date',
            'insurance_evident_file' => 'nullable|string',
            'insurance_evident_number' => 'nullable|string',
            'payment_type_id' => 'required|exists:payment_types,id',
        ];
    }

    public function messages(): array
    {
        return [
            'client_id.required' => 'Client harus dipilih.',
            'client_id.exists' => 'Client tidak valid.',
            'policy_id.required' => 'Policy harus dipilih.',
            'policy_id.exists' => 'Policy tidak valid.',
            'card_status_id.required' => 'Status kartu harus dipilih.',
            'start_date.required' => 'Tanggal mulai harus diisi.',
            'end_date.required' => 'Tanggal berakhir harus diisi.',
            'end_date.after' => 'Tanggal berakhir harus setelah tanggal mulai.',
            'premium_gross.required' => 'Premi gross harus diisi.',
            'commission_percentage.required' => 'Persentase komisi harus diisi.',
            'tax_percentage.required' => 'Persentase pajak harus diisi.',
            'payment_type_id.required' => 'Tipe pembayaran harus dipilih.',
        ];
    }
}
