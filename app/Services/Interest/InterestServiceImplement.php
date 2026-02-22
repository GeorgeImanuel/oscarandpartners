<?php

namespace App\Services\Interest;

use LaravelEasyRepository\Service;
use App\Repositories\Interest\InterestRepository;

class InterestServiceImplement extends Service implements InterestService{

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
     protected InterestRepository $mainRepository;

    public function __construct(InterestRepository $mainRepository)
    {
      $this->mainRepository = $mainRepository;
    }

    /**
     * Get all interests
     */
    public function getAll()
    {
        return $this->mainRepository->all();
    }

    /**
     * Override delete method to check relationships
     */
    public function delete($id)
    {
        $interest = $this->mainRepository->find($id);
        
        if (!$interest) {
            throw new \Exception('Data tidak ditemukan.');
        }

        // Check if interest is used in object_interests
        if (\App\Models\ObjectInterest::where('interest_id', $id)->count() > 0) {
            throw new \Exception('Tidak bisa hapus data, data sudah digunakan di data lain.');
        }

        return $this->mainRepository->delete($id);
    }
}
