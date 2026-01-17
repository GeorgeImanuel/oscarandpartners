<?php

namespace App\Repositories\ObjectInterest;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\ObjectInterest;

class ObjectInterestRepositoryImplement extends Eloquent implements ObjectInterestRepository{

    /**
    * Model class to be used in this repository for the common methods inside Eloquent
    * Don't remove or change $this->model variable name
    * @property Model|mixed $model;
    */
    protected ObjectInterest $model;

    public function __construct(ObjectInterest $model)
    {
        $this->model = $model;
    }

    // Write something awesome :)
}
