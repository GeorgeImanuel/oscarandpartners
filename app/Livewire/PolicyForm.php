<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ObjectModel;
use App\Services\Object\ObjectService;
use App\Services\Policy\PolicyService;
use App\Services\Coverage\CoverageService;
use App\Services\Currency\CurrencyService;
use App\Services\Insurance\InsuranceService;
use App\Services\PolicyInsurance\PolicyInsuranceService;

class PolicyForm extends Component
{
    protected $objectService;
    protected $coverageService;
    protected $currencyService;
    protected $insuranceService;
    protected $policyInsuranceService;

    public function boot(
        ObjectService $objectService,
        CoverageService $coverageService,
        CurrencyService $currencyService,
        InsuranceService $insuranceService,
        PolicyInsuranceService $policyInsuranceService
    ) {
        $this->objectService = $objectService;
        $this->coverageService = $coverageService;
        $this->currencyService = $currencyService;
        $this->insuranceService = $insuranceService;
        $this->policyInsuranceService = $policyInsuranceService;
    }

    public function render()
    {
        $objects = $this->objectService->all();
        $coverages = $this->coverageService->all();
        $currencies = $this->currencyService->all();
        $insurances = $this->insuranceService->all();
        $policyInsurances = $this->policyInsuranceService->all();

        return view('livewire.policy-form', [
            'objects' => $objects,
            'coverages' => $coverages,
            'currencies' => $currencies,
            'insurances' => $insurances,
            'policyInsurances' => $policyInsurances,
        ]);
    }
}
