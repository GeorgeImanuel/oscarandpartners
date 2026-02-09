<?php

namespace App\Services\PolicyObject;

use LaravelEasyRepository\Service;
use App\Repositories\PolicyObject\PolicyObjectRepository;

class PolicyObjectServiceImplement extends Service implements PolicyObjectService{

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
     protected PolicyObjectRepository $mainRepository;

    public function __construct(PolicyObjectRepository $mainRepository)
    {
      $this->mainRepository = $mainRepository;
    }

    // Define your custom methods :)
}
