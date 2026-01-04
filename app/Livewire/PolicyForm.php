<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\Policy\PolicyService;
use App\Services\Coverage\CoverageService;
use App\Services\Insurance\InsuranceService;
use App\Services\PolicyInsurance\PolicyInsuranceService;

class PolicyForm extends Component
{
    public $policyInsurances, $coverages, $insurances;

    private $policyService;
    private $coverageService;
    private $policyInsuranceService;
    private $insuranceService;

    public function boot(
        PolicyInsuranceService $policyInsuranceService,
        CoverageService $coverageService,
        InsuranceService $insuranceService
    ) {
        $this->policyInsuranceService = $policyInsuranceService;
        $this->coverageService = $coverageService;
        $this->insuranceService = $insuranceService;
    }

    public function mount()
    {
        $this->coverages = $this->coverageService->all();
        $this->insurances = $this->insuranceService->all();
    }

    public function render()
    {
        return view('livewire.policy-form');
    }
}
