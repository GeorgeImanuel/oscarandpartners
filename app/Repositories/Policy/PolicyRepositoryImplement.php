<?php

namespace App\Repositories\Policy;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Policy;

class PolicyRepositoryImplement extends Eloquent implements PolicyRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected Policy $model;

    public function __construct(Policy $model)
    {
        $this->model = $model;
    }

    public function getAllFreePolicies()
    {
        return $this->model->whereDoesntHave('productionCard')->get();
    }

    public function findPoliciesWithRelations($policyId)
    {
        return $this->model->with([
            'object' => function ($q) {
                $q->with(['objectType', 'objectPart',]);
                $q->withSum('objectPart as object_value', 'value');
            },
            'coverage'
        ])->find($policyId);
    }
}
