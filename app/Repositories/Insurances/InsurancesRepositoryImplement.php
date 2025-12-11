<?php

namespace App\Repositories\Insurances;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Insurances;

class InsurancesRepositoryImplement extends Eloquent implements InsurancesRepository{

    /**
    * Model class to be used in this repository for the common methods inside Eloquent
    * Don't remove or change $this->model variable name
    * @property Model|mixed $model;
    */
    protected Insurances $model;

    public function __construct(Insurances $model)
    {
        $this->model = $model;
    }

    // Write something awesome :)
}
