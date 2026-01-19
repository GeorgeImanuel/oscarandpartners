<?php

namespace App\Services\PaymentStatus;

use LaravelEasyRepository\Service;
use App\Repositories\PaymentStatus\PaymentStatusRepository;

class PaymentStatusServiceImplement extends Service implements PaymentStatusService{

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