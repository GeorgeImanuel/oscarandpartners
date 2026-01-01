<?php

namespace App\Repositories\Object;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\ObjectModel;

class ObjectRepositoryImplement extends Eloquent implements ObjectRepository{

    /**
    * Model class to be used in this repository for the common methods inside Eloquent
    * Don't remove or change $this->model variable name
    * @property Model|mixed $model;
    */
    protected ObjectModel $model;

    public function __construct(ObjectModel $model)
    {
        $this->model = $model;
    }

    /**
     * Get all objects with object type relation
     */
    public function getAllWithRelations()
    {
        return $this->model->with('objectType')->latest()->get();
    }
}
