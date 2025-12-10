<?php

namespace App\Repositories\Relation;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Relation;

class RelationRepositoryImplement extends Eloquent implements RelationRepository{

    /**
    * Model class to be used in this repository for the common methods inside Eloquent
    * Don't remove or change $this->model variable name
    * @property Model|mixed $model;
    */
    protected Relation $model;

    public function __construct(Relation $model)
    {
        $this->model = $model;
    }

    // Write something awesome :)
}
