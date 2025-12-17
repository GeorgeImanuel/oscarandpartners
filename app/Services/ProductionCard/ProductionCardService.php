<?php

namespace App\Services\ProductionCard;

use LaravelEasyRepository\BaseService;

interface ProductionCardService extends BaseService{

    public function getAllWithRelations();
    public function createCard(array $data);
    public function updateCard($id, array $data);
    public function deleteCard($id);
}
