<?php

namespace App\Services\Insurance;

use LaravelEasyRepository\Service;
use App\Repositories\Insurance\InsuranceRepository;

class InsuranceServiceImplement extends Service implements InsuranceService{

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
     protected InsuranceRepository $mainRepository;

    public function __construct(InsuranceRepository $mainRepository)
    {
      $this->mainRepository = $mainRepository;
    }

    /**
     * Override delete method to check relationships
     */
    public function delete($id)
    {
        $insurance = $this->mainRepository->find($id);
        
        if (!$insurance) {
            throw new \Exception('Data tidak ditemukan.');
        }

        // Check if insurance is used in policy_insurances
        if (\App\Models\PolicyInsurance::where('insurance_id', $id)->count() > 0) {
            throw new \Exception('Tidak bisa hapus data, data sudah digunakan di data lain.');
        }

        return $this->mainRepository->delete($id);
    }
}
