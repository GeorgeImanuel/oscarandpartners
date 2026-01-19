<?php

namespace App\Services\Client;

use LaravelEasyRepository\Service;
use App\Repositories\Client\ClientRepository;

class ClientServiceImplement extends Service implements ClientService{

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
     protected ClientRepository $mainRepository;

    public function __construct(ClientRepository $mainRepository)
    {
      $this->mainRepository = $mainRepository;
    }

    /**
     * Get all clients with relations
     */
    public function getAllWithRelations()
    {
        return $this->mainRepository->getAllWithRelations();
    }

    /**
     * Create client with user account
     */
    public function createClient(array $data)
    {
        // Create user first
        $user = \App\Models\User::create([
            'username' => $data['username'],
            'name' => $data['name'],
            'email' => $data['email'] ?? $data['username'] . '@client.local',
            'password' => \Hash::make($data['password']),
            'is_active' => true,
        ]);

        // Assign client role
        $user->assignRole('client');

        // Create client
        $client = $this->mainRepository->create([
            'user_id' => $user->id,
            'relation_id' => $data['relation_id'] ?? null,
            'id_name' => $data['name'],
            'id_number' => $data['id_card_number'] ?? null,
            'id_address' => $data['address'] ?? null,
        ]);

        return $client;
    }

    /**
     * Update client with user account
     */
    public function updateClient($id, array $data)
    {
        $client = $this->mainRepository->find($id);
        
        if (!$client) {
            return false;
        }

        // Update user
        $user = $client->user;
        $user->username = $data['username'];
        $user->name = $data['name'];
        $user->email = $data['email'] ?? $data['username'] . '@client.local';
        
        if (!empty($data['password'])) {
            $user->password = \Hash::make($data['password']);
        }
        
        $user->save();

        // Update client
        $updated = $this->mainRepository->update($id, [
            'relation_id' => $data['relation_id'] ?? null,
            'id_name' => $data['name'],
            'id_number' => $data['id_card_number'] ?? null,
            'id_address' => $data['address'] ?? null,
        ]);

        return $updated;
    }

    /**
     * Delete client (will cascade delete user)
     */
    public function deleteClient($id)
    {
        $client = $this->mainRepository->find($id);
        
        if (!$client) {
            return false;
        }

        // Delete user (will cascade delete client)
        $client->user->delete();
        
        return true;
    }
}
