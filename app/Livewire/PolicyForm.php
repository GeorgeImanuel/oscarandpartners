<?php

namespace App\Livewire;

use App\Models\Currency;
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
    protected $policyService;

    public $policyId;
    public $policy;

    protected $listeners = ['refreshPolicyForm' => 'loadPolicy'];

    public function boot(
        ObjectService $objectService,
        CoverageService $coverageService,
        CurrencyService $currencyService,
        InsuranceService $insuranceService,
        PolicyInsuranceService $policyInsuranceService,
        PolicyService $policyService
    ) {
        $this->objectService          = $objectService;
        $this->coverageService        = $coverageService;
        $this->currencyService        = $currencyService;
        $this->insuranceService       = $insuranceService;
        $this->policyInsuranceService = $policyInsuranceService;
        $this->policyService          = $policyService;
    }

    public function mount($policyId = null)
    {
        $this->policyId = $policyId;
        $this->loadPolicy($policyId);
    }

    public function loadPolicy($policyId = null)
    {
        $currency = 'Rp';
        $objectId = null;
        $coveragePercentage = 0;
        
        if ($policyId) {
            $this->policyId = $policyId;
            $this->policy = $this->policyService->findWithRelations($policyId);
            $currency = $this->policy->currency_code ?? 'Rp';
            
            // Get object_id from first policy object if exists
            if ($this->policy && $this->policy->policyObjects && $this->policy->policyObjects->isNotEmpty()) {
                $objectId = $this->policy->policyObjects->first()->object_id;
            }
            
            // Get total coverage percentage
            if ($this->policy && $this->policy->policyCoverages && $this->policy->policyCoverages->isNotEmpty()) {
                $coveragePercentage = $this->policy->policyCoverages->sum('percentage');
            }
        } else {
            $this->policy = null;
        }

        $this->dispatch(
            'policyLoaded',
            'setCurrencyCode',
            currencyCode: $currency,
            objectId: $objectId,
            coverage_percentage: $coveragePercentage
        );
    }

    public function render()
    {
        $objects          = $this->objectService->all();
        $coverages        = $this->coverageService->all();
        $currencies       = $this->currencyService->all();
        $insurances       = $this->insuranceService->all();
        $policyInsurances = $this->policyInsuranceService->all();
        
        return view('livewire.policy-form', compact(
            'objects',
            'coverages',
            'currencies',
            'insurances',
            'policyInsurances'
        ));
    }
}
