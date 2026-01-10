<?php

namespace App\Services\Payment;

use LaravelEasyRepository\BaseService;

interface PaymentService extends BaseService{

    public function getAllWithRelations();
    
    public function getByCardId($cardId);
}
