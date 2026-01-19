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
        $rules = [
            // Step 1: Card Details
            'card_type_id' => 'required|exists:card_types,id',
            'client_id' => 'required|exists:clients,id',
            
            // Step 2: Policy (either existing or new)
            'policy_id' => 'required_without_all:started_date,ended_date,coverage_id|nullable|exists:policies,id',
            
            // New Policy Fields (conditional)
            'started_date' => 'required_without:policy_id|nullable|date',
            'ended_date' => 'required_without:policy_id|nullable|date|after:started_date',
            'coverage_id' => 'required_without:policy_id|nullable|exists:coverages,id',
            'coverage_percentage' => 'required_with:coverage_id|nullable|numeric|min:0|max:100',
            'insurance_id' => 'required_with:coverage_id|nullable|array|min:1',
            'insurance_id.*' => 'exists:insurances,id',
            'percentage' => 'required_with:insurance_id|nullable|array',
            'percentage.*' => 'numeric|min:0|max:100',
            'is_main' => 'nullable|array',
            
            // Step 3: Object (either existing or new)
            'object_id' => 'required_without_all:object_occupation_id,object_address,object_city|nullable|exists:objects,id',
            
            // New Object Fields (conditional)
            'object_occupation_id' => 'required_without:object_id|nullable|exists:object_occupations,id',
            'object_address' => 'required_without:object_id|nullable|string',
            'object_city' => 'required_without:object_id|nullable|string',
            'interest_id' => 'nullable|array',
            'interest_id.*' => 'nullable|exists:interests,id',
            'value' => 'nullable|array',
            'value.*' => 'nullable|numeric|min:0',
            'object_value_input' => 'required_with:object_occupation_id|nullable|numeric|min:0',
            
            // Step 4: Premi Parameters
            'premi_percentages' => 'required|numeric|min:0|max:1',
            'commision_percentages' => 'required|numeric|min:0|max:1',
            'discount' => 'nullable|numeric|min:0',
            
            // Calculated Fields (read-only but submitted)
            'objects_value' => 'required|numeric|min:0',
            'production_value' => 'required|numeric|min:0',
            'premi' => 'required|numeric|min:0',
            'commision_bruto' => 'required|numeric|min:0',
            'commision_netto' => 'required|numeric|min:0',
            'tax' => 'required|numeric|min:0',
            'insurance_pay' => 'required|numeric|min:0',
        ];

        // Conditional: parent_card_id required only if card_type_id is renewal
        if ($this->input('card_type_id') == config('constants.card_type.renewal')) {
            $rules['parent_card_id'] = 'required|exists:cards,id';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            // Card Details
            'card_type_id.required' => 'Tipe kartu harus dipilih.',
            'card_type_id.exists' => 'Tipe kartu tidak valid.',
            'parent_card_id.required' => 'Kartu produksi sebelumnya harus dipilih untuk tipe Renewal.',
            'parent_card_id.exists' => 'Kartu produksi sebelumnya tidak valid.',
            'client_id.required' => 'Client harus dipilih.',
            'client_id.exists' => 'Client tidak valid.',
            
            // Policy
            'policy_id.required_without_all' => 'Polis harus dipilih atau dibuat baru.',
            'started_date.required_without' => 'Periode awal pertanggungan harus diisi.',
            'ended_date.required_without' => 'Periode akhir pertanggungan harus diisi.',
            'ended_date.after' => 'Periode akhir harus setelah periode awal.',
            'coverage_id.required_without' => 'Coverage harus dipilih.',
            'coverage_percentage.required_with' => 'Persentase coverage harus diisi.',
            'insurance_id.required_with' => 'Minimal satu asuransi harus dipilih.',
            'insurance_id.min' => 'Minimal satu asuransi harus dipilih.',
            'percentage.*.numeric' => 'Persentase asuransi harus berupa angka.',
            'percentage.*.max' => 'Persentase asuransi tidak boleh lebih dari 100%.',
            
            // Object
            'object_id.required_without_all' => 'Objek harus dipilih atau dibuat baru.',
            'object_occupation_id.required_without' => 'Objek Occupation harus dipilih.',
            'object_address.required_without' => 'Alamat objek harus diisi.',
            'object_city.required_without' => 'Kota objek harus diisi.',
            'object_value_input.required_with' => 'Harga pertanggungan harus diisi.',
            
            // Premi Parameters
            'premi_percentages.required' => 'Persentase premi harus diisi.',
            'premi_percentages.max' => 'Persentase premi tidak boleh lebih dari 1 (100%).',
            'commision_percentages.required' => 'Persentase komisi harus diisi.',
            'commision_percentages.max' => 'Persentase komisi tidak boleh lebih dari 1 (100%).',
            
            // Calculated Fields
            'objects_value.required' => 'Harga pertanggungan harus diisi.',
            'production_value.required' => 'Nilai produksi harus dihitung.',
            'premi.required' => 'Total premi harus dihitung.',
        ];
    }
    
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Validate insurance percentage sum = 100% when creating new policy
            if ($this->filled('insurance_id') && $this->filled('percentage')) {
                $totalPercentage = array_sum($this->percentage ?? []);
                
                if ($totalPercentage != 100) {
                    $validator->errors()->add(
                        'percentage', 
                        'Total persentase asuransi harus 100%. Saat ini: ' . number_format($totalPercentage, 2) . '%'
                    );
                }
            }
            
            // Validate at least one main insurance when creating new policy
            if ($this->filled('insurance_id') && $this->filled('is_main')) {
                $hasMainInsurance = false;
                foreach ($this->is_main ?? [] as $isMain) {
                    if ($isMain == 1) {
                        $hasMainInsurance = true;
                        break;
                    }
                }
                
                if (!$hasMainInsurance) {
                    $validator->errors()->add(
                        'is_main', 
                        'Minimal satu asuransi harus dipilih sebagai asuransi utama'
                    );
                }
            }
            
            // Validate discount tidak lebih dari commision_netto
            if ($this->filled(['discount', 'commision_bruto', 'tax'])) {
                $commision_netto = $this->commision_bruto - $this->tax;
                
                if ($this->discount > $commision_netto) {
                    $validator->errors()->add(
                        'discount', 
                        'Diskon (Rp ' . number_format($this->discount, 0, ',', '.') . 
                        ') tidak boleh lebih dari Komisi Bersih (Rp ' . number_format($commision_netto, 0, ',', '.') . ')'
                    );
                }
            }
        });
    }
}
