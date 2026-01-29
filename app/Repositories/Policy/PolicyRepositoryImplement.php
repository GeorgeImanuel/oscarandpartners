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

    public function allFree()
    {
        return $this->model->whereDoesntHave('productionCard')->get();
    }

    public function allActive()
    {
        return $this->model->where('ended_date', '>', now())->get();
    }

    public function findWithRelations($policyId)
    {
        return $this->model->with([
            'objects' => function ($q) {
                $q->with(['objectOccupation', 'objectInterests.interest']);
                $q->withSum('objectInterests as object_value', 'value');
            },
            'policyCoverages.coverage',
            'policyInsurances.insurance'
        ])->find($policyId);
    }

    public function allByActiveDate($startDate, $endDate)
    {
        return Policy::query()
        // Load relasi yang diperlukan buat tampilan
        // productionCard.client -> buat ambil nama Client
        // currency -> buat mata uang (kalau ada relasinya)
        ->with(['productionCard.client', 'currency']) 
        
        // Filter Tanggal Berakhir (Jatuh Tempo) ada di range bulan ini
        ->whereBetween('ended_date', [$startDate, $endDate])
        
        // Urutkan dari tanggal berakhir paling dekat
        ->orderBy('ended_date', 'asc')
        ->get();
    }
}
