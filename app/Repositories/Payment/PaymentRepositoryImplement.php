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
            'card.policy.object', 
            'card.client.user', 
            'paymentStatus',
            'card.paymentType',
        ])->latest()->get();
    }

    public function getByCardIdWithRelations($cardId)
    {
        return $this->model->with([
            'card.policy.policyInsurances.insurance',
            'card.policy.object',
            'card.client.user',
            'paymentStatus'
        ])->where('card_id', $cardId)->get();
    }

    // app/Repositories/Payment/PaymentRepositoryImplement.php

    public function getOverduePayments()
    {
        return $this->model->with([
            'card.policy.object',
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
        return Payment::query()
            // 1. Join ke Cards (Jembatan)
            ->join('cards', 'payments.card_id', '=', 'cards.id')
            // 2. Join ke Policies (Untuk ambil started_date)
            ->join('policies', 'cards.policy_id', '=', 'policies.id')
            ->selectRaw('
                -- Grouping berdasarkan TANGGAL MULAI POLIS
                YEAR(policies.started_date) as year,
                MONTH(policies.started_date) as month,
                
                -- Total Premi: Jumlahkan semua payment milik polis tersebut
                SUM(COALESCE(payments.paid_amount, 0)) as total_premi,
                
                -- Total Komisi: (Total Bayar - Total Setor Asuransi)
                SUM(COALESCE(payments.paid_amount, 0) - COALESCE(payments.insurance_paid_amount, 0)) as total_komisi
            ')
            // Filter berdasarkan TAHUN MULAI POLIS
            ->whereIn(DB::raw('YEAR(policies.started_date)'), $years)
            ->groupBy('year', 'month')
            ->get();
    }
    
}
