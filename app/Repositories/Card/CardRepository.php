<?php

namespace App\Repositories\Card;

use LaravelEasyRepository\Repository;

interface CardRepository extends Repository
{
    public function findWithRelation($id);
    public function getAllParent();
    public function getAllWithRelations();
    public function getAllWithFilter(array $data);
}
