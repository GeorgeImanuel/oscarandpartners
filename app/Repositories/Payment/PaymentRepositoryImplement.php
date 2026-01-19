<?php

namespace App\Repositories\Payment;

use App\Models\Card;
use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Payment;

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
}
