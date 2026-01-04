<?php

namespace App\Repositories\Part;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Part;

class PartRepositoryImplement extends Eloquent implements PartRepository{

    /**
    * Model class to be used in this repository for the common methods inside Eloquent
    * Don't remove or change $this->model variable name
    * @property Model|mixed $model;
    */
    protected Part $model;

    public function __construct(Part $model)
    {
        $this->model = $model;
    }

    /**
     * Find or create a part by name
     */
    public function firstOrCreate(array $attributes)
    {
        return $this->model->firstOrCreate($attributes);
    }
}
