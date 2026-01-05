    <?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\Policy\PolicyService;
use App\Services\Coverage\CoverageService;
use App\Services\Insurance\InsuranceService;
use App\Services\Object\ObjectService;
use App\Services\PolicyInsurance\PolicyInsuranceService;

class PolicyForm extends Component
{
    public $policyInsurances, $coverages, $insurances,$objects;

    private $policyService;
    private $coverageService;
    private $policyInsuranceService;
    private $insuranceService;
    private $objectService;

    public function boot(
        PolicyInsuranceService $policyInsuranceService,
        CoverageService $coverageService,
        InsuranceService $insuranceService,
        ObjectService $objectService
    ) {
        $this->policyInsuranceService = $policyInsuranceService;
        $this->coverageService = $coverageService;
        $this->insuranceService = $insuranceService;
        $this->objectService = $objectService;
    }

    public function mount()
    {
        $this->coverages        = $this->coverageService->all();
        $this->insurances       = $this->insuranceService->all();
        $this->objects          = $this->objectService->all();
        $this->policyInsurances = $this->policyInsuranceService->all();
    }

    public function render()
    {
        return view('livewire.policy-form');
    }
}
