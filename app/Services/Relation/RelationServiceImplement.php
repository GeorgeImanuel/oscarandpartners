<?php

namespace App\Services\Relation;

use LaravelEasyRepository\Service;
use App\Repositories\Relation\RelationRepository;

class RelationServiceImplement extends Service implements RelationService
{

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
    protected RelationRepository $mainRepository;

    public function __construct(RelationRepository $mainRepository)
    {
        $this->mainRepository = $mainRepository;
    }

    /**
     * Override delete method to check relationships
     */
    public function delete($id)
    {
        $relation = $this->mainRepository->find($id);
        
        if (!$relation) {
            throw new \Exception('Data tidak ditemukan.');
        }

        // Check if relation is used in clients
        if (\App\Models\Client::where('relation_id', $id)->count() > 0) {
            throw new \Exception('Tidak bisa hapus data, data sudah digunakan di data lain.');
        }

        return $this->mainRepository->delete($id);
    }
}
