<?php

namespace App\Services\Object;

use LaravelEasyRepository\BaseService;

interface ObjectService extends BaseService
{
    public function allWithRelations();
    public function createWithParts(array $data);
    public function updateWithParts($id, array $data);
    public function getAllByRelationWithFilter(array $filters = []);
}
