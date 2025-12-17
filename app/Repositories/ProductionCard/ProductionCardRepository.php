<?php

namespace App\Repositories\ProductionCard;

use LaravelEasyRepository\Repository;

interface ProductionCardRepository extends Repository{

    public function getAllWithRelations();
}
