<?php

namespace App\Services\Object;

use LaravelEasyRepository\BaseService;

interface ObjectService extends BaseService{

    public function getAllWithRelations();
    public function getObjectTypes();
    public function getParts();
    public function createWithParts(array $data);
    public function updateWithParts($id, array $data);
}
