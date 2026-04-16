<?php

namespace App\Services\Coverage;

use LaravelEasyRepository\Service;
use App\Repositories\Coverage\CoverageRepository;

class CoverageServiceImplement extends Service implements CoverageService{

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
     protected CoverageRepository $mainRepository;

    public function __construct(CoverageRepository $mainRepository)
    {
      $this->mainRepository = $mainRepository;
    }

    /**
     * Override delete method to check relationships
     */
    public function delete($id)
    {
        $coverage = $this->mainRepository->find($id);
        
        if (!$coverage) {
            throw new \Exception('Data tidak ditemukan.');
        }

        // Check if coverage is used in policy_coverages or object_coverages
        if ($coverage->policyCoverages()->count() > 0 || $coverage->objectCoverages()->count() > 0) {
            throw new \Exception('Tidak bisa hapus data, data sudah digunakan di data lain.');
        }

        return $this->mainRepository->delete($id);
    }
}
