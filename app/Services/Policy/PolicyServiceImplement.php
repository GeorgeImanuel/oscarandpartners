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
     * uncomment this to override the default message
     * protected string $create_message = "";
     * protected string $update_message = "";
     * protected string $delete_message = "";
     */

     /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
     protected PolicyRepository $mainRepository;

    public function __construct(PolicyRepository $mainRepository)
    {
      $this->mainRepository = $mainRepository;
    }

    // Define your custom methods :)
}
