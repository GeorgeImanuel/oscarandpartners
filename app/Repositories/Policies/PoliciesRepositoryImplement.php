<?php

namespace App\Repositories\Policies;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Policies;
use App\Models\Policy;

class PoliciesRepositoryImplement extends Eloquent implements PoliciesRepository{

    /**
    * Model class to be used in this repository for the common methods inside Eloquent
    * Don't remove or change $this->model variable name
    * @property Model|mixed $model;
    */
    protected Policy $model;

    public function __construct(Policy $model)
    {
        $this->model = $model;
    }

    // Write something awesome :)
}
