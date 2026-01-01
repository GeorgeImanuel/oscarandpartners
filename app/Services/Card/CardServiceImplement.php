<?php

namespace App\Services\Card;

use LaravelEasyRepository\ServiceApi;
use App\Repositories\Card\CardRepository;

class CardServiceImplement extends ServiceApi implements CardService
{

    /**
     * set title message api for CRUD
     * @param string $title
     */
    protected string $title = "";
    /**
     * uncomment this to override the default message
     * protected string $create_message = "";
     * protected string $update_message = "";
     * protected string $delete_message = "";
     */

    /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
    protected CardRepository $mainRepository;

    public function __construct(CardRepository $mainRepository)
    {
        $this->mainRepository = $mainRepository;
    }

    public function __construct(CardRepository $mainRepository)
    {
        $this->mainRepository = $mainRepository;
    }

    public function getAllParent()
    {
        return $this->mainRepository->getAllParent();
    }

    /**
     * Get all cards with relations
     */
    public function getAllWithRelations()
    {
        return $this->mainRepository->getAllWithRelations();
    }

    /**
     * Create production card
     */
    public function createCard(array $data)
    {
        // Add created_by from auth user
        $data['created_by'] = auth()->id();

        // Get input values
        $premium_gross = $data['harga_pertanggungan'] ?? 0;
        $commission_pct = $data['tarif_premi'] ?? 0;
        $admin_fee = $data['biaya_polis'] ?? 0;
        $commission_percentage = $data['persentase_komisi'] ?? 0;
        $tax_percentage = $data['pajak_komisi'] ?? 0;
        $discount = $data['diskon_dari_komisi'] ?? 0;

        // Calculate all fields
        $nilai_produksi = $premium_gross * ($commission_pct / 100);
        $total_premi = $nilai_produksi + $admin_fee;
        $komisi_bruto = $nilai_produksi * ($commission_percentage / 100);
        $nilai_pajak = $komisi_bruto * ($tax_percentage / 100);
        $komisi_bersih_awal = $komisi_bruto - $nilai_pajak;
        $tagihan_ke_nasabah = $total_premi - $discount;
        $sisa_komisi_agen = $komisi_bersih_awal - $discount;
        $netto_bayar_asuransi = $total_premi - $komisi_bersih_awal;

        // Map form fields to database columns
        $mappedData = [
            'client_id' => $data['client_id'],
            'policy_id' => $data['policy_id'],
            'card_status_id' => $data['card_status_id'],
            'created_by' => $data['created_by'],

            // Date mapping
            'start_date' => $data['periode_mulai'] ?? null,
            'end_date' => $data['periode_akhir'] ?? null,

            // Input fields
            'premium_gross' => $premium_gross,
            'commission_pct' => $commission_pct,
            'admin_fee' => $admin_fee,
            'commission_percentage' => $commission_percentage,
            'tax_percentage' => $tax_percentage,
            'discount' => $discount,

            // Calculated fields
            'commission_amount' => $komisi_bruto,
            'net_to_insurance' => $netto_bayar_asuransi,
            'premi_amount' => $nilai_produksi,
            'polis_amount' => $admin_fee,
            'client_total' => $total_premi,
            'commission_amount_initial' => $komisi_bersih_awal,
            'tax_amount' => $nilai_pajak,
            'discount_amount' => $discount,
            'commission_total' => $sisa_komisi_agen,

            // Payment info
            'insurance_payment_status_id' => $data['insurance_payment_status_id'] ?? null,
            'payment_type_id' => $data['payment_type_id'],
            'insurance_paid_at' => $data['insurance_paid_at'] ?? null,
            'insurance_evident_number' => $data['insurance_evident_number'] ?? null,
        ];

        return $this->mainRepository->create($mappedData);
    }

    /**
     * Update production card
     */
    public function updateCard($id, array $data)
    {
        // Get input values
        $premium_gross = $data['harga_pertanggungan'] ?? 0;
        $commission_pct = $data['tarif_premi'] ?? 0;
        $admin_fee = $data['biaya_polis'] ?? 0;
        $commission_percentage = $data['persentase_komisi'] ?? 0;
        $tax_percentage = $data['pajak_komisi'] ?? 0;
        $discount = $data['diskon_dari_komisi'] ?? 0;

        // Calculate all fields
        $nilai_produksi = $premium_gross * ($commission_pct / 100);
        $total_premi = $nilai_produksi + $admin_fee;
        $komisi_bruto = $nilai_produksi * ($commission_percentage / 100);
        $nilai_pajak = $komisi_bruto * ($tax_percentage / 100);
        $komisi_bersih_awal = $komisi_bruto - $nilai_pajak;
        $tagihan_ke_nasabah = $total_premi - $discount;
        $sisa_komisi_agen = $komisi_bersih_awal - $discount;
        $netto_bayar_asuransi = $total_premi - $komisi_bersih_awal;

        // Map form fields to database columns
        $mappedData = [
            'client_id' => $data['client_id'],
            'policy_id' => $data['policy_id'],
            'card_status_id' => $data['card_status_id'],

            // Date mapping
            'start_date' => $data['periode_mulai'] ?? null,
            'end_date' => $data['periode_akhir'] ?? null,

            // Input fields
            'premium_gross' => $premium_gross,
            'commission_pct' => $commission_pct,
            'admin_fee' => $admin_fee,
            'commission_percentage' => $commission_percentage,
            'tax_percentage' => $tax_percentage,
            'discount' => $discount,

            // Calculated fields
            'commission_amount' => $komisi_bruto,
            'net_to_insurance' => $netto_bayar_asuransi,
            'premi_amount' => $nilai_produksi,
            'polis_amount' => $admin_fee,
            'client_total' => $total_premi,
            'commission_amount_initial' => $komisi_bersih_awal,
            'tax_amount' => $nilai_pajak,
            'discount_amount' => $discount,
            'commission_total' => $sisa_komisi_agen,

            // Payment info
            'insurance_payment_status_id' => $data['insurance_payment_status_id'] ?? null,
            'payment_type_id' => $data['payment_type_id'],
            'insurance_paid_at' => $data['insurance_paid_at'] ?? null,
            'insurance_evident_number' => $data['insurance_evident_number'] ?? null,
        ];

        return $this->mainRepository->update($id, $mappedData);
    }

    /**
     * Delete production card
     */
    public function deleteCard($id)
    {
        return $this->mainRepository->delete($id);
    }
}
