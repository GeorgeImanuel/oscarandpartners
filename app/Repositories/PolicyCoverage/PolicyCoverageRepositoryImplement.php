<?php

namespace App\Repositories\PolicyCoverage;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\PolicyCoverage;

class PolicyCoverageRepositoryImplement extends Eloquent implements PolicyCoverageRepository{

    /**
    * Model class to be used in this repository for the common methods inside Eloquent
    * Don't remove or change $this->model variable name
    * @property Model|mixed $model;
    */
    protected PolicyCoverage $model;

    public function __construct(PolicyCoverage $model)
    {
        $this->model = $model;
    }

    // Write something awesome :)
}
