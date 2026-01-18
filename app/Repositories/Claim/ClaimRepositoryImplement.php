<?php

namespace App\Repositories\Claim;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Claim;

class ClaimRepositoryImplement extends Eloquent implements ClaimRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected $model;

    public function __construct(Claim $model)
    {
        $this->model = $model;
    }

    public function getAllWithRelations($perPage, $search = null)
    {
        $query = $this->model
            ->with(['policy.productionCard.client'])
            ->orderBy('created_at', 'desc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('policy', function ($subQ) use ($search) {
                    $subQ->where('policy_number', 'like', "%{$search}%");
                })->orWhereHas('policy.productionCard.client', function ($subQ) use ($search) {
                    $subQ->where('id_name', 'like', "%{$search}%");
                });
            });
        }

        return $query->paginate($perPage);
    }
}
