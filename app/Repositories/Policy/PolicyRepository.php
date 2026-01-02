<?php

namespace App\Repositories\Policy;

use LaravelEasyRepository\Repository;

interface PolicyRepository extends Repository
{
    public function getAllFreePolicies();
    public function findPoliciesWithRelations($policyId);
}
