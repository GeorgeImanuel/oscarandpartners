<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Policy;
use App\Services\Policy\PolicyService;

class PolicyDetails extends Component
{
    public $policyId;
    public $policy = null;

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
            
            dd(calculate_payment($this->policy->object->object_value, $this->policy->premi_percentage, $this->policy->policy_fee, $this->policy->commision_percentage, $this->policy->tax_percentage, $this->policy->discount));
            // Dispatch event with object value for auto-fill
            if ($this->policy && $this->policy->object) {
                $this->dispatch('policyLoaded', objectValue: $this->policy->object->object_value);
            }
        } else {
            $this->policy = null;
        }
    }

    public function render()
    {
        return view('livewire.policy-details');
    }
}
