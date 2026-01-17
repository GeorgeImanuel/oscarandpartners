<?php

namespace App\Repositories\Interest;

use LaravelEasyRepository\Repository;

interface InterestRepository extends Repository{
    /**
     * Get all interests
     */
    public function all();
    /**
     * Find or create a interest by name
     */
    public function firstOrCreate(array $attributes);
}
