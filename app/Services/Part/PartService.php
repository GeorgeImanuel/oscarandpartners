<?php

namespace App\Services\Part;

use LaravelEasyRepository\BaseService;

interface PartService extends BaseService{

    /**
     * Get all parts
     */
    public function getAll();
}
