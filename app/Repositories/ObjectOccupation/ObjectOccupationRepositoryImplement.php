<?php

namespace App\Repositories\ObjectOccupation;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\ObjectOccupation;

class ObjectOccupationRepositoryImplement extends Eloquent implements ObjectOccupationRepository{

    /**
    * Model class to be used in this repository for the common methods inside Eloquent
    * Don't remove or change $this->model variable name
    * @property Model|mixed $model;
    */
    protected ObjectOccupation $model;

    public function __construct(ObjectOccupation $model)
    {
        $this->model = $model;
    }

    /**
     * Find or create an object type by name
     */
    public function firstOrCreate(array $attributes)
    {
        return $this->model->firstOrCreate($attributes);
    }
}
