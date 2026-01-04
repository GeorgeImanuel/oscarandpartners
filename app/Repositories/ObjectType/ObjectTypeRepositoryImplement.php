<?php

namespace App\Repositories\ObjectType;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\ObjectType;

class ObjectTypeRepositoryImplement extends Eloquent implements ObjectTypeRepository{

    /**
    * Model class to be used in this repository for the common methods inside Eloquent
    * Don't remove or change $this->model variable name
    * @property Model|mixed $model;
    */
    protected ObjectType $model;

    public function __construct(ObjectType $model)
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
