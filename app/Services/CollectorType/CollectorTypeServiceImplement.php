<?php

namespace App\Services\CollectorType;

use LaravelEasyRepository\Service;
use App\Repositories\CollectorType\CollectorTypeRepository;

class CollectorTypeServiceImplement extends Service implements CollectorTypeService{

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
     protected CollectorTypeRepository $mainRepository;

    public function __construct(CollectorTypeRepository $mainRepository)
    {
      $this->mainRepository = $mainRepository;
    }

}
