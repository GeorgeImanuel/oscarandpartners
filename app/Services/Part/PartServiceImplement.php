<?php

namespace App\Services\Part;

use LaravelEasyRepository\Service;
use App\Repositories\Part\PartRepository;

class PartServiceImplement extends Service implements PartService{

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
     protected PartRepository $mainRepository;

    public function __construct(PartRepository $mainRepository)
    {
      $this->mainRepository = $mainRepository;
    }

    /**
     * Get all parts
     */
    public function getAll()
    {
        return $this->mainRepository->all();
    }
}
