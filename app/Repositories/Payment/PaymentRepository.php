<?php

namespace App\Repositories\Payment;

use LaravelEasyRepository\Repository;

interface PaymentRepository extends Repository{

    public function getAllWithRelations();

    public function getOverduePayments();

    public function getByCardIdWithRelations($cardId);

    public function getMonthlySumByYears(array $years);
}
