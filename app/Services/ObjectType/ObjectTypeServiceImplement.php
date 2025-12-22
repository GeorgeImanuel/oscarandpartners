<?php

namespace App\Services\ObjectType;

use LaravelEasyRepository\Service;
use App\Repositories\ObjectType\ObjectTypeRepository;

class ObjectTypeServiceImplement extends Service implements ObjectTypeService{

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
     protected ObjectTypeRepository $mainRepository;

    public function __construct(ObjectTypeRepository $mainRepository)
    {
      $this->mainRepository = $mainRepository;
    }

    // Define your custom methods :)
}
