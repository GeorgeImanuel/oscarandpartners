<?php

namespace App\Services\PolicyCoverage;

use LaravelEasyRepository\Service;
use App\Repositories\PolicyCoverage\PolicyCoverageRepository;

class PolicyCoverageServiceImplement extends Service implements PolicyCoverageService{

    /**
     * set title message api for CRUD
     * @param string $title
     */
     protected string $title = "";
     /**
     * uncomment this to override the default message
     * protected string $create_message = "";
     * protected string $update_message = "";
     * protected string $delete_message = "";
     */

     /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
     protected PolicyCoverageRepository $mainRepository;

    public function __construct(PolicyCoverageRepository $mainRepository)
    {
      $this->mainRepository = $mainRepository;
    }

    // Define your custom methods :)
}
