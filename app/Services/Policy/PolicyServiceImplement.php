<?php

namespace App\Services\Policy;

use LaravelEasyRepository\Service;
use App\Repositories\Policy\PolicyRepository;

class PolicyServiceImplement extends Service implements PolicyService{

    /**
     * set title message api for CRUD
     * @param string $title
     */
    protected string $title = "";

    /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
    protected PolicyRepository $mainRepository;

    public function __construct(PolicyRepository $mainRepository)
    {
        $this->mainRepository = $mainRepository;
    }

    // Service ini hanya mewarisi method dasar dari BaseService
    // seperti all(), findOrFail(), create(), update(), delete()
}
