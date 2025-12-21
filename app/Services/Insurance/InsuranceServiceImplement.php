<?php

namespace App\Services\Insurance;

use LaravelEasyRepository\Service;
use App\Repositories\Insurance\InsuranceRepository;

class InsuranceServiceImplement extends Service implements InsuranceService{

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
     protected InsuranceRepository $mainRepository;

    public function __construct(InsuranceRepository $mainRepository)
    {
      $this->mainRepository = $mainRepository;
    }

    // Define your custom methods :)
}
