<?php

namespace App\Services\PaymentStatus;

use LaravelEasyRepository\Service;
use App\Repositories\PaymentStatus\PaymentStatusRepository;

class PaymentStatusServiceImplement extends Service implements PaymentStatusService{

    /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
     protected PaymentStatusRepository $mainRepository;

    public function __construct(PaymentStatusRepository $mainRepository)
    {
      $this->mainRepository = $mainRepository;
    }

    /**
     * Override delete method to check relationships
     */
    public function delete($id)
    {
        $paymentStatus = $this->mainRepository->find($id);
        
        if (!$paymentStatus) {
            throw new \Exception('Data tidak ditemukan.');
        }

        // Check if payment status is used in payments
        if (\App\Models\Payment::where('payment_status_id', $id)->orWhere('insurance_payment_status_id', $id)->count() > 0) {
            throw new \Exception('Tidak bisa hapus data, data sudah digunakan di data lain.');
        }

        return $this->mainRepository->delete($id);
    }
}