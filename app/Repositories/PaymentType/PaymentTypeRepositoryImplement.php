<?php

namespace App\Repositories\PaymentType;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\PaymentType;

class PaymentTypeRepositoryImplement extends Eloquent implements PaymentTypeRepository{

    /**
    * Model class to be used in this repository for the common methods inside Eloquent
    * Don't remove or change $this->model variable name
    * @property Model|mixed $model;
    */
    protected PaymentType $model;

    public function __construct(PaymentType $model)
    {
        $this->model = $model;
    }

    // Write something awesome :)
}
