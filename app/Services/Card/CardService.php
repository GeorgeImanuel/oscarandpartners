<?php

namespace App\Services\Card;

use LaravelEasyRepository\BaseService;

interface CardService extends BaseService
{
    public function getAllParent();
    public function getAllWithRelations();
    public function create($data);
    public function findWithRelation($id);
    public function updateCard($id, array $data);
    public function getAllWithFilter(array $data);
}
