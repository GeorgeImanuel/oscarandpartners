<?php

namespace App\Services\PaymentType;

use LaravelEasyRepository\Service;
use App\Repositories\PaymentType\PaymentTypeRepository;

class PaymentTypeServiceImplement extends Service implements PaymentTypeService{

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
     protected PaymentTypeRepository $mainRepository;

    public function __construct(PaymentTypeRepository $mainRepository)
    {
      $this->mainRepository = $mainRepository;
    }

    // Define your custom methods :)
}
