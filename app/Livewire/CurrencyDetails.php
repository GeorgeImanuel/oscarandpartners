<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Currency;
use App\Services\Currency\CurrencyService;

class CurrencyDetails extends Component
{
    public $currencyCode;
    public $currency = null;
    public $notFound = false;

    protected $currencyService;

    public function boot(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    public function mount($currencyCode = null)
    {
        if ($currencyCode) {
            $this->currencyCode = $currencyCode;
            $this->loadCurrency();
        }
    }

    public function updatedCurrencyCode($value)
    {
        $this->loadCurrency();
    }

    public function loadCurrency()
    {
        if ($this->currencyCode) {
            $this->currency = $this->currencyService->find($this->currencyCode);
            if (!$this->currency) {
                $this->notFound = true;
                return;
            }
            $this->notFound = false;
        } else {
            $this->currency = null;
            $this->notFound = false;
        }
    }

    public function render()
    {
        return view('livewire.currency-details');
    }
}
