<?php

namespace App\Repositories\Object;

use LaravelEasyRepository\Repository;

interface ObjectRepository extends Repository{

    public function getAllWithRelations();
}
