<?php

namespace App\Repositories\Card;

use LaravelEasyRepository\Repository;

interface CardRepository extends Repository
{
    public function getAllParent();
    public function getAllWithRelations();
}
