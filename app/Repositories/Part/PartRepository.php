<?php

namespace App\Repositories\Part;

use LaravelEasyRepository\Repository;

interface PartRepository extends Repository{

    /**
     * Find or create a part by name
     */
    public function firstOrCreate(array $attributes);
}
