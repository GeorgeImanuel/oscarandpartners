<?php

namespace App\Services\ObjectOccupation;

use LaravelEasyRepository\Service;
use App\Repositories\ObjectOccupation\ObjectOccupationRepository;

class ObjectOccupationServiceImplement extends Service implements ObjectOccupationService{

    /**
     * set title message api for CRUD
     * @param string $title
     */
     protected string $title = "Object Occupation";
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
     protected ObjectOccupationRepository $mainRepository;

    public function __construct(ObjectOccupationRepository $mainRepository)
    {
      $this->mainRepository = $mainRepository;
    }

    /**
     * Get all object occupations
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
        $objectOccupation = $this->mainRepository->find($id);
        
        if (!$objectOccupation) {
            throw new \Exception('Data tidak ditemukan.');
        }

        // Check if object occupation is used in objects
        if (\App\Models\ObjectModel::where('object_occupation_id', $id)->count() > 0) {
            throw new \Exception('Tidak bisa hapus data, data sudah digunakan di data lain.');
        }

        return $this->mainRepository->delete($id);
    }
}
