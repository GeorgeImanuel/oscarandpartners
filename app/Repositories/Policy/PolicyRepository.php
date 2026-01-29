<?php

namespace App\Repositories\Policy;

use LaravelEasyRepository\Repository;

interface PolicyRepository extends Repository
{
    public function allFree();
    public function allActive();
    public function findWithRelations($policyId);
    public function allByActiveDate($startDate, $endDate);
}
