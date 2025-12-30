<?php

namespace App\Services\PaymentStatus;

use LaravelEasyRepository\Service;
use App\Repositories\PaymentStatus\PaymentStatusRepository;

class PaymentStatusServiceImplement extends Service implements PaymentStatusService{

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
     protected PaymentStatusRepository $mainRepository;

    public function __construct(PaymentStatusRepository $mainRepository)
    {
      $this->mainRepository = $mainRepository;
    }

    // Define your custom methods :)
}
