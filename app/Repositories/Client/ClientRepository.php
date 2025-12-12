<?php

namespace App\Repositories\Client;

use LaravelEasyRepository\Repository;

interface ClientRepository extends Repository{

    public function getAllWithRelations();
}
