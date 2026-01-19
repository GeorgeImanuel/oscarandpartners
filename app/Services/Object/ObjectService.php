<?php

namespace App\Services\Object;

use LaravelEasyRepository\BaseService;

interface ObjectService extends BaseService
{
    public function allWithRelations();
    public function createWithInterests(array $data);
    public function updateWithInterests($id, array $data);
    public function getAllByRelationWithFilter(array $filters = []);
}
