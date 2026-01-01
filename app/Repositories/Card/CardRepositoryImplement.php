<?php

namespace App\Repositories\Card;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Card;

class CardRepositoryImplement extends Eloquent implements CardRepository{

    /**
    * Model class to be used in this repository for the common methods inside Eloquent
    * Don't remove or change $this->model variable name
    * @property Model|mixed $model;
    */
    protected Card $model;

    public function __construct(Card $model)
    {
        $this->model = $model;
    }

    public function getAllParent()
    {
        return $this->model->whereDoesntHave('child')->get();
    }

    /**
     * Get all cards with relations
     */
    public function getAllWithRelations()
    {
        return $this->model->with([
            'client',
            'policy',
            'cardStatus',
            'creator',
            'insurancePaymentStatus',
            'paymentType'
        ])->get();
    }
}
