<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCardRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Foreign Keys (tetap pakai dropdown)
            'client_id' => 'required|exists:clients,id',
            'policy_id' => 'required|exists:policies,id',
            'card_status_id' => 'required|exists:card_statuses,id',
            'insurance_payment_status_id' => 'required|exists:payment_statuses,id',
            'payment_type_id' => 'required|exists:payment_types,id',
            
            // Periode & Perhitungan
            'periode_mulai' => 'required|date',
            'periode_akhir' => 'required|date|after:periode_mulai',
            'harga_pertanggungan' => 'required|numeric|min:0',
            
            // Perhitungan Premi
            'tarif_premi' => 'required|numeric|min:0',
            'biaya_polis' => 'required|numeric|min:0',
            
            // Perhitungan Komisi
            'persentase_komisi' => 'required|numeric|min:0|max:100',
            'pajak_komisi' => 'required|numeric|min:0|max:100',
            
            // Subsidi & Tagihan
            'diskon_dari_komisi' => 'nullable|numeric|min:0',
            
            // Payment Info
            'insurance_paid_at' => 'nullable|date',
            'insurance_evident_file' => 'nullable|string',
            'insurance_evident_number' => 'nullable|string',
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
            'insurance_payment_status_id.required' => 'Status Pembayaran Asuransi harus dipilih.',
            'payment_type_id.required' => 'Tipe Pembayaran harus dipilih.',
            
            'periode_mulai.required' => 'Periode Mulai harus diisi.',
            'periode_akhir.required' => 'Periode Akhir harus diisi.',
            'periode_akhir.after' => 'Periode Akhir harus setelah Periode Mulai.',
            'harga_pertanggungan.required' => 'Harga Pertanggungan harus diisi.',
            
            'tarif_premi.required' => 'Tarif Premi harus diisi.',
            'biaya_polis.required' => 'Biaya Polis harus diisi.',
            'persentase_komisi.required' => 'Persentase Komisi harus diisi.',
            'pajak_komisi.required' => 'Pajak Komisi harus diisi.',
        ];
    }
    
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->filled(['harga_pertanggungan', 'tarif_premi', 'persentase_komisi', 'pajak_komisi', 'diskon_dari_komisi'])) {
                // Hitung Komisi Bersih Awal
                $nilai_produksi = $this->harga_pertanggungan * ($this->tarif_premi / 100);
                $komisi_bruto = $nilai_produksi * ($this->persentase_komisi / 100);
                $nilai_pajak = $komisi_bruto * ($this->pajak_komisi / 100);
                $komisi_bersih_awal = $komisi_bruto - $nilai_pajak;
                
                // Validasi: Diskon tidak boleh lebih dari Komisi Bersih Awal
                if ($this->diskon_dari_komisi > $komisi_bersih_awal) {
                    $validator->errors()->add(
                        'diskon_dari_komisi', 
                        'Diskon dari komisi (Rp ' . number_format($this->diskon_dari_komisi, 0, ',', '.') . 
                        ') tidak boleh lebih dari Komisi Bersih Awal (Rp ' . number_format($komisi_bersih_awal, 0, ',', '.') . '). Agen akan rugi!'
                    );
                }
            }
        });
    }
}
