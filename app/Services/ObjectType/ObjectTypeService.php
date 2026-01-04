<?php

namespace App\Services\ObjectType;

use LaravelEasyRepository\BaseService;

interface ObjectTypeService extends BaseService{

    /**
     * Get all object types
     */
    public function getAll();
}
