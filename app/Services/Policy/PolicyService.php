<?php

namespace App\Services\Policy;

use LaravelEasyRepository\BaseService;

interface PolicyService extends BaseService
{
    public function getFreePolicies();
}
