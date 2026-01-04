<?php

namespace App\Repositories\ObjectType;

use LaravelEasyRepository\Repository;

interface ObjectTypeRepository extends Repository{

    /**
     * Find or create an object type by name
     */
    public function firstOrCreate(array $attributes);
}
