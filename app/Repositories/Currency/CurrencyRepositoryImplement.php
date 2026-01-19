<?php

namespace App\Repositories\Currency;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Currency;

class CurrencyRepositoryImplement extends Eloquent implements CurrencyRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected Currency $model;

    public function __construct(Currency $model)
    {
        $this->model = $model;
    }
}
