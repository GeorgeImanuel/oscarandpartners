<?php

namespace App\Services\PolicyInsurance;

use LaravelEasyRepository\Service;
use App\Repositories\PolicyInsurance\PolicyInsuranceRepository;

class PolicyInsuranceServiceImplement extends Service implements PolicyInsuranceService{

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
     protected PolicyInsuranceRepository $mainRepository;

    public function __construct(PolicyInsuranceRepository $mainRepository)
    {
      $this->mainRepository = $mainRepository;
    }

    // Define your custom methods :)
}
