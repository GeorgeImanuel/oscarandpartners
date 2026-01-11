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

            if ($this->policy->object) {
                $this->dispatch(
                    'policyLoaded',
                    // objectValue: $this->policy->object->object_value,
                    objectId: $this->policy->object->id
                );
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
