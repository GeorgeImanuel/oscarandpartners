<?php

namespace App\Repositories\ProductionCard;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\ProductionCard;

class ProductionCardRepositoryImplement extends Eloquent implements ProductionCardRepository{

    /**
    * Model class to be used in this repository for the common methods inside Eloquent
    * Don't remove or change $this->model variable name
    * @property Model|mixed $model;
    */
    protected ProductionCard $model;

    public function __construct(ProductionCard $model)
    {
        $this->model = $model;
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
