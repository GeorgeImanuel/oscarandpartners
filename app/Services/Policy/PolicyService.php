<?php

namespace App\Services\Policy;

use LaravelEasyRepository\BaseService;

interface PolicyService extends BaseService
{
    public function allFree();
    public function allActive();
    public function findWithRelations($policyId);
    public function createPolicy(array $data);
    public function updatePolicy($id, array $data);
    public function allByActiveDate($startDate, $endDate);
}
