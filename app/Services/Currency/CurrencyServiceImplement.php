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
}
