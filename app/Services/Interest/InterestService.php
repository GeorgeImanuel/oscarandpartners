<?php

namespace App\Services\Interest;

use LaravelEasyRepository\BaseService;

interface InterestService extends BaseService{
    /**
     * Get all interests
     */
    public function getAll();
}
