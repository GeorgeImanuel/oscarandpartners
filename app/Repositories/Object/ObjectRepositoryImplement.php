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

    public function find($id)
    {
        return $this->model
            ->with(['objectOccupation', 'objectInterests.interest', 'objectCoverages.coverage'])
            ->withSum('objectInterests as object_value', 'value')
            ->find($id);
    }
    
    /**
     * Get all objects with object occupation relation
     */
    public function getAllWithRelations()
    {
        return $this->model->with('objectOccupation')->latest()->get();
    }

    /**
     * Get all objects by relation with filters
     */
    public function getAllByRelationWithFilter(array $filters = [])
    {
        $user = auth()->user();
        
        $query = $this->model
            ->with([
                'objectInterests.interest',
                'policies.mainInsurance',
                'policies.productionCard.client.relation',
                'policies.coverages',
                'objectOccupation'
            ]);
        
        // Jika user adalah client, filter berdasarkan relation_id mereka
        if ($user && $user->hasRole('client') && $user->client && $user->client->relation_id) {
            $query->whereHas('policies.productionCard.client', function($clientQuery) use ($user) {
                $clientQuery->where('relation_id', $user->client->relation_id);
            });
        }
        // Jika owner/admin dengan filter
        elseif (isset($filters['client_id']) || isset($filters['relation_id'])) {
            $query->whereHas('policies.productionCard', function($cardQuery) use ($filters) {
                if (isset($filters['client_id'])) {
                    $cardQuery->where('client_id', $filters['client_id']);
                }
                
                if (isset($filters['relation_id'])) {
                    $cardQuery->whereHas('client', function($clientQuery) use ($filters) {
                        $clientQuery->where('relation_id', $filters['relation_id']);
                    });
                }
            });
        }
        // Jika owner/admin tanpa filter
        else {
            $query->has('policies');
        }

        return $query->get();
    }
}
