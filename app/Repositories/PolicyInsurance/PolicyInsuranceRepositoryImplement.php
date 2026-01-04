<?php

namespace App\Repositories\PolicyInsurance;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\PolicyInsurance;

class PolicyInsuranceRepositoryImplement extends Eloquent implements PolicyInsuranceRepository{

    /**
    * Model class to be used in this repository for the common methods inside Eloquent
    * Don't remove or change $this->model variable name
    * @property Model|mixed $model;
    */
    protected PolicyInsurance $model;

    public function __construct(PolicyInsurance $model)
    {
        $this->model = $model;
    }

    // Write something awesome :)
}
