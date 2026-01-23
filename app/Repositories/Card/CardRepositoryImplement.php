<?php

namespace App\Repositories\Card;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Card;

class CardRepositoryImplement extends Eloquent implements CardRepository
{
    protected Card $model;

    public function __construct(Card $model)
    {
        $this->model = $model;
    }

    public function findWithRelation($id)
    {
        return $this->model->with([
            'client',
            'policy.policyInsurances.insurance',
            'policy.policyCoverages.coverage',
            
            // PERBAIKAN DI SINI:
            // Kita gunakan function untuk menambahkan withSum
            'policy.objects' => function($query) {
                // Load detail interest
                $query->with('objectInterests.interest');
                // Hitung total value (akan muncul atribut 'total_value' di tiap object)
                $query->withSum('objectInterests as total_value', 'value');
            },
            
            'payments.paymentStatus'
        ])->find($id);
    }

    public function getAllParent()
    {
        return $this->model->whereDoesntHave('child')
            ->with([
                'policy.objects' => function($query) {
                    $query->withSum('objectInterests as total_value', 'value');
                }
            ])->get();
    }

    public function getAllWithRelations()
    {
        return $this->model->with([
            'client',
            'policy' => function($q) {
                // Load objects beserta total nilainya
                $q->with(['objects' => function($subQ) {
                    $subQ->withSum('objectInterests as total_value', 'value');
                }]);
            },
            'cardStatus',
            'creator',
            'paymentType',
            'payments.insurancePaymentStatus'
        ])->get();
    }

    public function getAllWithFilter(array $data)
    {
        // PERBAIKAN DI SINI JUGA:
        // Ubah string 'policy.objects' menjadi array key => function
        $query = $this->model->with([
            'client.relation', 
            'policy.objects' => function($q) {
                // Ini kuncinya: Menjumlahkan kolom 'value' dari tabel 'object_interests'
                // Hasilnya bisa diakses via $object->total_value
                $query->withSum('objectInterests as total_value', 'value');
            },
            'cardType', 
            'cardStatus', 
            'payments.insurancePaymentStatus'
        ]);

        $user = auth()->user();
        if ($user && $user->hasRole('client')) {
            if ($user->client) {
                $query->where('client_id', $user->client->id);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        if (isset($data['started_date']) && isset($data['ended_date'])) {
            $query->whereHas('policy', function ($q) use ($data) {
                $q->whereBetween('ended_date', [$data['started_date'], $data['ended_date']]);
            });
        }

        if (isset($data['card_status_id'])) {
            $query->where('card_status_id', $data['card_status_id']);
        }

        if (isset($data['client_id']) && (!$user || !$user->hasRole('client'))) {
            $query->where('client_id', $data['client_id']);
        }

        if (isset($data['relation_id'])) {
            $query->whereHas('client', function ($q) use ($data) {
                $q->where('relation_id', $data['relation_id']);
            });
        }

        if (isset($data['object_id'])) {
            $query->whereHas('policy.objects', function ($q) use ($data) {
                $q->where('objects.id', $data['object_id']);
            });
        }

        return $query->get();
    }
}