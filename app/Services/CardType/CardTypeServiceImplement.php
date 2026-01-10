<?php

namespace App\Services\CardType;

use LaravelEasyRepository\ServiceApi;
use App\Repositories\CardType\CardTypeRepository;

class CardTypeServiceImplement extends ServiceApi implements CardTypeService{

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
     protected CardTypeRepository $mainRepository;

    public function __construct(CardTypeRepository $mainRepository)
    {
      $this->mainRepository = $mainRepository;
    }

    // Define your custom methods :)
}
