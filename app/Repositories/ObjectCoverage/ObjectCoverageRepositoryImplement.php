<?php

namespace App\Repositories\ObjectCoverage;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\ObjectCoverage;

class ObjectCoverageRepositoryImplement extends Eloquent implements ObjectCoverageRepository{

    /**
    * Model class to be used in this repository for the common methods inside Eloquent
    * Don't remove or change $this->model variable name
    * @property Model|mixed $model;
    */
    protected ObjectCoverage $model;

    public function __construct(ObjectCoverage $model)
    {
        $this->model = $model;
    }

    // Write something awesome :)
}
