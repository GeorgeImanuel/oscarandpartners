<?php

namespace App\Repositories\CollectorType;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\CollectorType;

class CollectorTypeRepositoryImplement extends Eloquent implements CollectorTypeRepository{

    /**
    * Model class to be used in this repository for the common methods inside Eloquent
    * Don't remove or change $this->model variable name
    * @property Model|mixed $model;
    */
    protected CollectorType $model;

    public function __construct(CollectorType $model)
    {
        $this->model = $model;
    }

}
