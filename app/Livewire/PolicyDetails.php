<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Policy;
use App\Services\Policy\PolicyService;

class PolicyDetails extends Component
{
    public $policyId;
    public $policy = null;
    public $notFound = false;

    protected $policyService;

    public function boot(PolicyService $policyService)
    {
        $this->policyService = $policyService;
    }

    public function mount($policyId = null)
    {
        if ($policyId) {
            $this->policyId = $policyId;
            $this->loadPolicy();
        }
    }

    public function updatedPolicyId($value)
    {
        $this->loadPolicy();
    }

    public function loadPolicy()
    {
        if ($this->policyId) {
            $this->policy = $this->policyService->findPoliciesWithRelations($this->policyId);
            if (!$this->policy) {
                $this->notFound = true;
                return;
            }

            // Emit object data
            if ($this->policy->object) {
                $this->dispatch(
                    'policyLoaded',
                    objectId: $this->policy->object->id
                );
            }

            // Emit insurance data
            if ($this->policy->policyInsurances) {
                $insurances = $this->policy->policyInsurances->map(function($polIns) {
                    return [
                        'id' => $polIns->id,
                        'insurance_id' => $polIns->insurance_id,
                        'insurance_name' => $polIns->insurance->name ?? '-',
                        'percentage' => $polIns->percentage,
                        'tax' => $polIns->tax ?? 0
                    ];
                })->toArray();

                $this->dispatch('insurancesLoaded', insurances: $insurances);
            }
        } else {
            $this->policy = null;
            $this->dispatch('insurancesLoaded', insurances: []);
        }
    }

    public function render()
    {
        return view('livewire.policy-details');
    }
}
