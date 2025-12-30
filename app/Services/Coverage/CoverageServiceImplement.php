<?php

namespace App\Services\Coverage;

use LaravelEasyRepository\Service;
use App\Repositories\Coverage\CoverageRepository;

class CoverageServiceImplement extends Service implements CoverageService{

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
     protected CoverageRepository $mainRepository;

    public function __construct(CoverageRepository $mainRepository)
    {
      $this->mainRepository = $mainRepository;
    }

    // Define your custom methods :)
}
