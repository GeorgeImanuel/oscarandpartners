<?php

namespace App\Repositories\Card;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Card;

class CardRepositoryImplement extends Eloquent implements CardRepository
{

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
        return $this->model->whereDoesntHave('child')->with('policy.object')->get();
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

    public function getAllWithFilter(array $data){
        $query = $this->model->with(['client.relation', 'policy.object', 'cardType','cardStatus', 'insurancePaymentStatus']);

        $user = auth()->user();
        if ($user && $user->hasRole('client')) {
            if ($user->client) {
                $query->where('client_id', $user->client->id);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        if(isset($data['started_date']) && isset($data['ended_date'])){
            $query->whereHas('policy', function($q) use ($data) {
                $q->whereBetween('ended_date', [$data['started_date'], $data['ended_date']]);
            });
        }


        if(isset($data['card_status_id'])){
            $query->where('card_status_id', $data['card_status_id']);
        }

        if(isset($data['client_id']) && (!$user || !$user->hasRole('client'))){
            $query->where('client_id', $data['client_id']);
        }

        if(isset($data['relation_id'])){
            $query->whereHas('client', function($q) use ($data) {
                $q->where('relation_id', $data['relation_id']);
            });
        }

        if(isset($data['object_id'])){
            $query->whereHas('policy', function($q) use ($data) {
                $q->where('object_id', $data['object_id']);
            });
        }

        return $query->get();
    }
}
