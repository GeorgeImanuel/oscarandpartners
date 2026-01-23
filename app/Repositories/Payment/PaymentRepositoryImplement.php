<?php

namespace App\Repositories\Payment;

use App\Models\Card;
use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class PaymentRepositoryImplement extends Eloquent implements PaymentRepository{

    /**
    * Model class to be used in this repository for the common methods inside Eloquent
    * Don't remove or change $this->model variable name
    * @property Model|mixed $model;
    */
    protected Payment $model;

    public function __construct(Payment $model)
    {
        $this->model = $model;
    }

    public function getAllWithRelations()
    {
        return $this->model->with([
            'card',
            'card.policy.objects', 
            'card.client.user', 
            'paymentStatus',
            'card.paymentType',
        ])->latest()->get();
    }

    public function getByCardIdWithRelations($cardId)
    {
        return $this->model->with([
            'card.policy.policyInsurances.insurance',
            'card.policy.objects',
            'card.client.user',
            'paymentStatus'
        ])->where('card_id', $cardId)->get();
    }

    // app/Repositories/Payment/PaymentRepositoryImplement.php

    public function getOverduePayments()
    {
        return $this->model->with([
            'card.policy.objects',
            'card.client.user',
            'card.paymentType',
            'paymentStatus'
        ])
        // 1. Filter: Tanggal Deadline sudah lewat dari hari ini
        ->whereDate('deadline_at', '<=', now()->toDateString()) 
        
        // 2. Filter: Status Pembayaran adalah Pending (ID: 1)
        ->where('payment_status_id', 1)  
        
        ->latest()
        ->get();
    }

    public function getMonthlySumByYears(array $years)
    {
        return Card::query()
            // 1. Join ke Policies (Untuk ambil started_date & currency_code)
            ->join('policies', 'cards.policy_id', '=', 'policies.id')
            
            // 2. Join ke Currencies (Untuk ambil value/kurs)
            // Menggunakan LEFT JOIN supaya jika mata uang 'IDR' tidak ada di tabel currencies, data tetap muncul
            ->leftJoin('currencies', 'policies.currency_code', '=', 'currencies.code')
            
            ->selectRaw("
                -- Grouping Waktu
                YEAR(policies.started_date) as year,
                MONTH(policies.started_date) as month,
                
                -- Total Premi: (Nilai Premi x Nilai Tukar)
                -- COALESCE(currencies.value, 1) artinya: Jika value NULL (tidak ketemu), kali 1.
                SUM(cards.premi_value * COALESCE(currencies.value, 1)) as total_premi,
                
                -- Total Komisi: (Komisi Netto x Nilai Tukar)
                SUM(cards.commision_netto * COALESCE(currencies.value, 1)) as total_komisi
            ")
            
            // Filter Tahun
            ->whereIn(DB::raw('YEAR(policies.started_date)'), $years)
            
            // Filter agar data terhapus tidak ikut hitung
            ->whereNull('cards.deleted_at') 
            
            // Grouping (Wajib pakai Raw untuk menghindari error SQL Strict Mode)
            ->groupBy(DB::raw('YEAR(policies.started_date)'), DB::raw('MONTH(policies.started_date)'))
            
            // Urutkan
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();
    }
    
}
