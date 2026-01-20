<?php

namespace App\Services\Payment;

use LaravelEasyRepository\BaseService;

interface PaymentService extends BaseService{

    public function getAllWithRelations();
    
    public function getByCardId($cardId);

    public function getOverduePayments();

    public function processClientPayment($id, array $data);
    
    public function processInsurancePayment($id, array $data);
}
