<?php

namespace App\Services\Client;

use LaravelEasyRepository\BaseService;

interface ClientService extends BaseService{

    public function getAllWithRelations();
    public function createClient(array $data);
    public function updateClient($id, array $data);
    public function deleteClient($id);
}
