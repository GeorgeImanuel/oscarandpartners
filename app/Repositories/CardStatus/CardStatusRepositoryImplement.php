<?php

namespace App\Repositories\CardStatus;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\CardStatus;

class CardStatusRepositoryImplement extends Eloquent implements CardStatusRepository{

    /**
    * Model class to be used in this repository for the common methods inside Eloquent
    * Don't remove or change $this->model variable name
    * @property Model|mixed $model;
    */
    protected CardStatus $model;

    public function __construct(CardStatus $model)
    {
        $this->model = $model;
    }

    // Write something awesome :)
}
