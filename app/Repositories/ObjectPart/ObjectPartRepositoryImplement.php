<?php

namespace App\Repositories\ObjectPart;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\ObjectPart;

class ObjectPartRepositoryImplement extends Eloquent implements ObjectPartRepository{

    /**
    * Model class to be used in this repository for the common methods inside Eloquent
    * Don't remove or change $this->model variable name
    * @property Model|mixed $model;
    */
    protected ObjectPart $model;

    public function __construct(ObjectPart $model)
    {
        $this->model = $model;
    }

    // Write something awesome :)
}
