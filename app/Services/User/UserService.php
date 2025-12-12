<?php

namespace App\Services\User;

use LaravelEasyRepository\BaseService;

interface UserService extends BaseService{

    public function getAllAdminFinance();
    public function createUser(array $data);
    public function updateUser($id, array $data);
    public function deleteUser($id);
}
