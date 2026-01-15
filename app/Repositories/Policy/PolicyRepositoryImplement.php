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

    public function allFreePolicies()
    {
        return $this->model->whereDoesntHave('productionCard')->get();
    }

    public function allActivePolicies()
    {
        return $this->model->where('ended_date', '>', now())->get();
    }

    public function findPoliciesWithRelations($policyId)
    {
        return $this->model->with([
            'object' => function ($q) {
                $q->with(['objectOccupation', 'objectInterests.interest']);
                $q->withSum('objectInterests as object_value', 'value');
            },
            'coverage'
        ])->find($policyId);
    }
}
