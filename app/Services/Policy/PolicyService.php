<?php

namespace App\Services\Policy;

use LaravelEasyRepository\BaseService;

interface PolicyService extends BaseService
{
    public function getAllFreePolicies();
    public function findPoliciesWithRelations($policyId);
    public function createPolicy(array $data);
    public function updatePolicy($id, array $data);
}
