<?php

namespace App\Repositories\CardType;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\CardType;

class CardTypeRepositoryImplement extends Eloquent implements CardTypeRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected CardType $model;

    public function __construct(CardType $model)
    {
        $this->model = $model;
    }

    public function getNewId()
    {
        return 1;
    }

    public function getRenewalId()
    {
        return 2;
    }

    public function getEndorsId()
    {
        return 3;
    }
}
