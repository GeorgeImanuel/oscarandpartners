<?php

namespace App\Repositories\Policy;

use LaravelEasyRepository\Repository;

interface PolicyRepository extends Repository
{
    public function allFreePolicies();
    public function allActivePolicies();
    public function findPoliciesWithRelations($policyId);
}
