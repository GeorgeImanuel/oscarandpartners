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
        // if ($this->policyId) {
            $this->policy = $this->policyService->findWithRelations($this->policyId);

            if (!$this->policy) {
                $this->notFound = true;
                return;
            }

            // Emit objects data (multiple objects support)
            if ($this->policy->objects && $this->policy->objects->isNotEmpty()) {
                // Hitung total nilai dari semua objects
                $totalObjectValue = $this->policy->getTotalObjectValue();
                
                // Get first object for backward compatibility
                $firstObject = $this->policy->objects->first();
                
                $this->dispatch(
                    'policyLoaded',
                    objectId: $firstObject->id,
                    coverage_percentage: $this->policy->coverage_percentages,
                    totalObjectValue: $totalObjectValue,
                    objectsCount: $this->policy->objects->count()
                );
            }

            // Emit insurance data
            if ($this->policy->policyInsurances) {
                $insurances = $this->policy->policyInsurances->map(function ($polIns) {
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

            // Emit coverage data
            if ($this->policy->policyCoverages) {
                $coverages = $this->policy->policyCoverages->map(function ($polCov) {
                    return [
                        'id' => $polCov->id,
                        'coverage_id' => $polCov->coverage_id,
                        'coverage_name' => $polCov->coverage->name ?? '-',
                        'percentage' => $polCov->percentage
                    ];
                })->toArray();

                $this->dispatch('coveragesLoaded', coverages: $coverages);
            }
        } else {
            $this->policy = null;
            $this->dispatch('insurancesLoaded', insurances: []);
            $this->dispatch('coveragesLoaded', coverages: []);
        }
    }

    public function render()
    {
        return view('livewire.policy-details');
    }
}
