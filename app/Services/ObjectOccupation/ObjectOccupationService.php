<?php

namespace App\Services\ObjectOccupation;

use LaravelEasyRepository\BaseService;

interface ObjectOccupationService extends BaseService{
    /**
     * Get all object types
     */
    public function getAll();
}
