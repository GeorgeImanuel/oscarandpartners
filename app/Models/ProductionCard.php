<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionCard extends Model
{
    protected $table = 'cards';
    
    protected $fillable = [
        // Foreign Keys
        'client_id',
        'policy_id',
        'card_status_id',
        'insurance_payment_status_id',
        'payment_type_id',
        'created_by',
        
        // Date fields (periode)
        'start_date',      // periode_mulai di form
        'end_date',        // periode_akhir di form
        
        // Calculation fields
        'premium_gross',              // harga_pertanggungan di form
        'commission_percentage',      // persentase_komisi di form
        'tax_percentage',             // pajak_komisi di form
        'discount',                   // diskon_dari_komisi di form
        'admin_fee',                  // biaya_polis di form
        'commission_pct',             // tarif_premi di form
        'commission_amount',
        'net_to_insurance',
        'premi_amount',
        'polis_amount',
        'client_total',
        'commission_amount_initial',
        'tax_amount',
        'discount_amount',
        'commission_total',
        
        // Payment Info
        'insurance_paid_at',
        'insurance_evident_file',
        'insurance_evident_number',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'insurance_paid_at' => 'datetime',
        'premium_gross' => 'decimal:2',
        'commission_pct' => 'decimal:3',
        'admin_fee' => 'decimal:2',
        'commission_percentage' => 'decimal:2',
        'tax_percentage' => 'decimal:2',
        'discount' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'net_to_insurance' => 'decimal:2',
        'premi_amount' => 'decimal:2',
        'polis_amount' => 'decimal:2',
        'client_total' => 'decimal:2',
        'commission_amount_initial' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'commission_total' => 'decimal:2',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function policy()
    {
        return $this->belongsTo(Policy::class);
    }

    public function cardStatus()
    {
        return $this->belongsTo(CardStatus::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function insurancePaymentStatus()
    {
        return $this->belongsTo(PaymentStatus::class, 'insurance_payment_status_id');
    }

    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class);
    }

    // ========== CALCULATED FIELDS (ACCESSOR) ==========

    /**
     * Nilai Produksi = Harga Pertanggungan * (Tarif Premi / 100)
     * DB: premium_gross * (commission_pct / 100)
     */
    public function getNilaiProduksiAttribute()
    {
        return $this->premium_gross * ($this->commission_pct / 100);
    }

    /**
     * Total Premi = Nilai Produksi + Biaya Polis
     * DB: nilai_produksi + admin_fee
     */
    public function getTotalPremiAttribute()
    {
        return $this->nilai_produksi + $this->admin_fee;
    }

    /**
     * Komisi Bruto = Nilai Produksi * (Persentase Komisi / 100)
     * DB: nilai_produksi * (commission_percentage / 100)
     */
    public function getKomisiBrutoAttribute()
    {
        return $this->nilai_produksi * ($this->commission_percentage / 100);
    }

    /**
     * Nilai Pajak = Komisi Bruto * (Pajak Komisi / 100)
     * DB: komisi_bruto * (tax_percentage / 100)
     */
    public function getNilaiPajakAttribute()
    {
        return $this->komisi_bruto * ($this->tax_percentage / 100);
    }

    /**
     * Komisi Bersih Awal = Komisi Bruto - Nilai Pajak
     */
    public function getKomisiBersihAwalAttribute()
    {
        return $this->komisi_bruto - $this->nilai_pajak;
    }

    /**
     * Tagihan ke Nasabah = Total Premi - Diskon dari Komisi
     * DB: total_premi - discount
     */
    public function getTagihanKeNasabahAttribute()
    {
        return $this->total_premi - ($this->discount ?? 0);
    }

    /**
     * Sisa Komisi Agen = Komisi Bersih Awal - Diskon dari Komisi
     * DB: komisi_bersih_awal - discount
     */
    public function getSisaKomisiAgenAttribute()
    {
        return $this->komisi_bersih_awal - ($this->discount ?? 0);
    }

    /**
     * Netto Bayar Asuransi = Total Premi - Komisi Bersih Awal
     */
    public function getNettoBayarAsuransiAttribute()
    {
        return $this->total_premi - $this->komisi_bersih_awal;
    }
}
