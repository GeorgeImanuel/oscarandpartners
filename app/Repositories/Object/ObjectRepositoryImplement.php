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

    /**
     * Get all objects by relation with filters
     */
    public function getAllByRelationWithFilter(array $filters = [])
    {
        $user = auth()->user();
        
        $query = $this->model
            ->with([
                'objectParts.part',
                'policies.mainInsurance',
                'policies.productionCard.client.relation',
                'objectType'
            ]);
        
        // Hanya filter di level database jika ada filter spesifik atau user adalah client
        $hasFilter = isset($filters['client_id']) || isset($filters['relation_id']) || ($user && $user->hasRole('client') && $user->client);
        
        if ($hasFilter) {
            $query->whereHas('policies', function($policyQuery) use ($filters, $user) {
                $policyQuery->whereHas('productionCard', function($cardQuery) use ($filters, $user) {
                    // For client users, only show their relation
                    if ($user && $user->hasRole('client') && $user->client) {
                        $cardQuery->whereHas('client', function($clientQuery) use ($user) {
                            $clientQuery->where('relation_id', $user->client->relation_id);
                        });
                    }
                    
                    // For owner/admin filters
                    if (isset($filters['client_id'])) {
                        $cardQuery->where('client_id', $filters['client_id']);
                    }
                    
                    if (isset($filters['relation_id'])) {
                        $cardQuery->whereHas('client', function($clientQuery) use ($filters) {
                            $clientQuery->where('relation_id', $filters['relation_id']);
                        });
                    }
                });
            });
        } else {
            // Kalau tidak ada filter, load semua objects yang punya policies
            $query->has('policies');
        }

        return $query->get();
    }
}
