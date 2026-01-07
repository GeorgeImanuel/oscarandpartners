<?php

namespace App\Repositories\PaymentStatus;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\PaymentStatus;

class PaymentStatusRepositoryImplement extends Eloquent implements PaymentStatusRepository{

    /**
    * Model class to be used in this repository for the common methods inside Eloquent
    * Don't remove or change $this->model variable name
    * @property Model|mixed $model;
    */
    protected PaymentStatus $model;

    public function __construct(PaymentStatus $model)
    {
        $this->model = $model;
    }

}