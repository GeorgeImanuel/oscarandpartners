<?php

namespace App\Repositories\ObjectOccupation;

use LaravelEasyRepository\Repository;

interface ObjectOccupationRepository extends Repository{
    /**
     * Find or create an object type by name
     */
    public function firstOrCreate(array $attributes);
}
