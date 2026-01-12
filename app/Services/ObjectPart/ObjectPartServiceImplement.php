<?php

namespace App\Services\ObjectPart;

use LaravelEasyRepository\Service;
use App\Repositories\ObjectPart\ObjectPartRepository;

class ObjectPartServiceImplement extends Service implements ObjectPartService{

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
     protected ObjectPartRepository $mainRepository;

    public function __construct(ObjectPartRepository $mainRepository)
    {
      $this->mainRepository = $mainRepository;
    }

    // Define your custom methods :)
}
