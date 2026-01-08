<?php

namespace App\Services\Payment;

use LaravelEasyRepository\Service;
use App\Repositories\Payment\PaymentRepository;

class PaymentServiceImplement extends Service implements PaymentService{

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
     protected PaymentRepository $mainRepository;

    public function __construct(PaymentRepository $mainRepository)
    {
      $this->mainRepository = $mainRepository;
    }

    public function getAllWithRelations()
    {
        return $this->mainRepository->getAllWithRelations();
    }

    public function create($data)
    {
        // Handle File Upload
        if (isset($data['client_evident_file']) && $data['client_evident_file'] instanceof \Illuminate\Http\UploadedFile) {
            $file = $data['client_evident_file'];
            $path = $file->store('payments/evidents', 'public');
            $data['client_evident_file'] = $path;
        }

        // Map card_id (dari form/ERD) ke production_card_id (di Model Payment)
        if (isset($data['card_id'])) {
            $data['production_card_id'] = $data['card_id'];
            unset($data['card_id']);
        }

        return $this->mainRepository->create($data);
    }

    public function update($id, $data)
    {
        if (isset($data['client_evident_file']) && $data['client_evident_file'] instanceof \Illuminate\Http\UploadedFile) {
            $file = $data['client_evident_file'];
            $path = $file->store('payments/evidents', 'public');
            $data['client_evident_file'] = $path;
        }

        if (isset($data['card_id'])) {
            $data['production_card_id'] = $data['card_id'];
            unset($data['card_id']);
        }

        return $this->mainRepository->update($id, $data);
    }
}
