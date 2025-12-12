<?php

namespace App\Repositories\User;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\User;

class UserRepositoryImplement extends Eloquent implements UserRepository{

    /**
    * Model class to be used in this repository for the common methods inside Eloquent
    * Don't remove or change $this->model variable name
    * @property Model|mixed $model;
    */
    protected User $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * Get all admin and finance users with roles
     */
    public function getAdminFinanceUsers()
    {
        return $this->model->whereHas('roles', function($query) {
            $query->whereIn('name', ['admin', 'finance']);
        })->with('roles')->get();
    }
}
