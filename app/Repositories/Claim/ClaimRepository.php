<?php

namespace App\Repositories\Claim;

use LaravelEasyRepository\Repository;

interface ClaimRepository extends Repository
{
    public function getAllWithRelations($perPage, $search = null);
}
