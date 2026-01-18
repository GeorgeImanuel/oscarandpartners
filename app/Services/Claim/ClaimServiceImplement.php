<?php

namespace App\Services\Claim;

use LaravelEasyRepository\Service;
use App\Repositories\Claim\ClaimRepository;

class ClaimServiceImplement extends Service implements ClaimService
{
    /**
     * set title message api for CRUD
     * @param string $title
     */
    protected string $title = "Claim";

    /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
    protected ClaimRepository $mainRepository;

    public function __construct(ClaimRepository $mainRepository)
    {
        $this->mainRepository = $mainRepository;
    }

    public function getAllWithRelations($perPage, $search = null)
    {
        return $this->mainRepository->getAllWithRelations($perPage, $search);
    }
}
