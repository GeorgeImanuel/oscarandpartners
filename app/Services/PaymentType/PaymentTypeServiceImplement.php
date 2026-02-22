<?php

namespace App\Services\PaymentType;

use LaravelEasyRepository\Service;
use App\Repositories\PaymentType\PaymentTypeRepository;

class PaymentTypeServiceImplement extends Service implements PaymentTypeService{

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
     protected PaymentTypeRepository $mainRepository;

    public function __construct(PaymentTypeRepository $mainRepository)
    {
      $this->mainRepository = $mainRepository;
    }

    /**
     * Override delete method to check relationships
     */
    public function delete($id)
    {
        $paymentType = $this->mainRepository->find($id);
        
        if (!$paymentType) {
            throw new \Exception('Data tidak ditemukan.');
        }

        // Check if payment type is used in cards
        if (\App\Models\Card::where('payment_type_id', $id)->count() > 0) {
            throw new \Exception('Tidak bisa hapus data, data sudah digunakan di data lain.');
        }

        return $this->mainRepository->delete($id);
    }
}
