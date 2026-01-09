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
            'clientPaymentStatus',
            'card.paymentType',
        ])->latest()->get();
    }
}
