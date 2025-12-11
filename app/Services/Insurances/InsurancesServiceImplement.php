<?php

namespace App\Services\Insurances;

use LaravelEasyRepository\Service;
use App\Repositories\Insurances\InsurancesRepository;

class InsurancesServiceImplement extends Service implements InsurancesService{

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
     protected InsurancesRepository $mainRepository;

    public function __construct(InsurancesRepository $mainRepository)
    {
      $this->mainRepository = $mainRepository;
    }

    // Define your custom methods :)
}
