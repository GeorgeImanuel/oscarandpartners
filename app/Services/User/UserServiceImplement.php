<?php

namespace App\Services\User;

use LaravelEasyRepository\ServiceApi;
use App\Repositories\User\UserRepository;

class UserServiceImplement extends ServiceApi implements UserService{

    /**
     * set title message api for CRUD
     * @param string $title
     */
     protected string $title = "";
     /**
     * uncomment this to override the default message
     * protected string $create_message = "";
     * protected string $update_message = "";
     * protected string $delete_message = "";
     */

     /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
     protected UserRepository $mainRepository;

    public function __construct(UserRepository $mainRepository)
    {
      $this->mainRepository = $mainRepository;
    }

    /**
     * Get all admin and finance users
     */
    public function getAllAdminFinance()
    {
        return $this->mainRepository->getAdminFinanceUsers();
    }

    /**
     * Create user with role assignment
     */
    public function createUser(array $data)
    {
        // Hash password
        $data['password'] = \Hash::make($data['password']);
        $data['is_active'] = isset($data['is_active']) ? true : false;

        // Create user
        $user = $this->mainRepository->create($data);

        // Assign role
        if ($user && isset($data['role'])) {
            $user->assignRole($data['role']);
        }

        return $user;
    }

    /**
     * Update user with role sync
     */
    public function updateUser($id, array $data)
    {
        $user = $this->mainRepository->find($id);
        
        if (!$user) {
            return false;
        }

        // Prepare update data
        $updateData = [
            'username' => $data['username'],
            'name' => $data['name'],
            'email' => $data['email'],
            'is_active' => isset($data['is_active']) ? true : false,
        ];

        // Update password if provided
        if (!empty($data['password'])) {
            $updateData['password'] = \Hash::make($data['password']);
        }

        // Update user
        $updated = $this->mainRepository->update($id, $updateData);

        // Sync roles
        if ($updated && isset($data['role'])) {
            $user->syncRoles([$data['role']]);
        }

        return $updated;
    }

    /**
     * Delete user
     */
    public function deleteUser($id)
    {
        return $this->mainRepository->delete($id);
    }
}
