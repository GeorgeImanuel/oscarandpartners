<?php

namespace App\Repositories\Interest;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Interest;

class InterestRepositoryImplement extends Eloquent implements InterestRepository{

    /**
    * Model class to be used in this repository for the common methods inside Eloquent
    * Don't remove or change $this->model variable name
    * @property Model|mixed $model;
    */
    protected Interest $model;

    public function __construct(Interest $model)
    {
        $this->model = $model;
    }

    /**
     * Find or create a interest by name
     */
    public function firstOrCreate(array $attributes)
    {
        return $this->model->firstOrCreate($attributes);
    }
}
