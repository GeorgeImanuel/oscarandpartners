<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\Currency\CurrencyService;

class CurrencyForm extends Component
{
    protected $currencyService;

    public function boot(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    public function render()
    {
        return view('livewire.currency-form');
    }
}
