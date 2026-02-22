<?php

namespace App\Services\Currency;

use LaravelEasyRepository\Service;
use App\Repositories\Currency\CurrencyRepository;

class CurrencyServiceImplement extends Service implements CurrencyService
{

    /**
     * Don't forget to add a service to the repository property in the constructor. 
     * This is important, otherwise your service class won't work properly.
     */
    public function __construct(CurrencyRepository $currencyRepository)
    {
        $this->mainRepository = $currencyRepository;
    }

    /**
     * Override delete method to check relationships
     */
    public function delete($id)
    {
        $currency = $this->mainRepository->find($id);
        
        if (!$currency) {
            throw new \Exception('Data tidak ditemukan.');
        }

        // Check if currency is used in policies
        if ($currency->policies()->count() > 0) {
            throw new \Exception('Tidak bisa hapus data, data sudah digunakan di data lain.');
        }

        return $this->mainRepository->delete($id);
    }
}
