<?php

namespace App\Services\Policy;

use LaravelEasyRepository\Service;
use App\Repositories\Policy\PolicyRepository;
use Illuminate\Support\Facades\DB;
use App\Models\Insurance;
use App\Models\PolicyInsurance;

class PolicyServiceImplement extends Service implements PolicyService
{

    /**
     * set title message api for CRUD
     * @param string $title
     */
    protected string $title = "";
    /**
     * uncomment this to override the default message
     * protected string $create_message = "";
     * protected string $update_message = "";
     * protected string $delete_message = "";
     */

    /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
    protected PolicyRepository $mainRepository;

    public function __construct(PolicyRepository $mainRepository)
    {
        $this->mainRepository = $mainRepository;
        $this->mainRepository = $mainRepository;
    }

    public function allFreePolicies()
    {
        return $this->mainRepository->allFreePolicies();
    }

    public function allActivePolicies()
    {
        return $this->mainRepository->allActivePolicies();
    }

    public function findPoliciesWithRelations($policyId)
    {
        return $this->mainRepository->findPoliciesWithRelations($policyId);
    }

    public function createPolicy(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Hitung total coverage percentages
            $totalCoveragePercentages = 0;
            if (isset($data['coverage_percentage']) && is_array($data['coverage_percentage'])) {
                foreach ($data['coverage_percentage'] as $percentage) {
                    $totalCoveragePercentages += floatval($percentage);
                }
            }

            // 1. Create Policy Header
            $policy = $this->mainRepository->create([
                'policy_number' => $data['policy_number'],
                'currency_code' => $data['currency_code'] ?? null,
                'started_date' => $data['started_date'],
                'ended_date' => $data['ended_date'],
                'coverage_percentages' => $totalCoveragePercentages / 100, // Convert to decimal
            ]);

            // 2. Attach Objects (Many-to-Many)
            if (isset($data['object_id']) && is_array($data['object_id'])) {
                $policy->objects()->sync(array_filter($data['object_id']));
            }

            // 3. Attach Coverages with Percentage (Pivot Table)
            if (isset($data['coverage_id']) && is_array($data['coverage_id'])) {
                $coverageData = [];
                foreach ($data['coverage_id'] as $index => $coverageId) {
                    if (!empty($coverageId) && isset($data['coverage_percentage'][$index])) {
                        $coverageData[$coverageId] = [
                            'percentage' => floatval($data['coverage_percentage'][$index]) / 100 // Convert to decimal
                        ];
                    }
                }
                $policy->coverages()->sync($coverageData);
            }

            // 4. Process Insurances
            if (isset($data['insurance_id']) && is_array($data['insurance_id'])) {
                $this->processInsurances($policy->id, $data);
            }

            return $policy;
        });
    }

    public function updatePolicy($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $policy = $this->mainRepository->findOrFail($id);

            // Hitung total coverage percentages
            $totalCoveragePercentages = 0;
            if (isset($data['coverage_percentage']) && is_array($data['coverage_percentage'])) {
                foreach ($data['coverage_percentage'] as $percentage) {
                    $totalCoveragePercentages += floatval($percentage);
                }
            }

            // 1. Update Policy Header
            $policy->update([
                'policy_number' => $data['policy_number'],
                'currency_code' => $data['currency_code'] ?? null,
                'started_date' => $data['started_date'],
                'ended_date' => $data['ended_date'],
                'coverage_percentages' => $totalCoveragePercentages / 100, // Convert to decimal
            ]);

            // 2. Sync Objects
            if (isset($data['object_id']) && is_array($data['object_id'])) {
                $policy->objects()->sync(array_filter($data['object_id']));
            } else {
                $policy->objects()->detach();
            }

            // 3. Sync Coverages with Percentage
            if (isset($data['coverage_id']) && is_array($data['coverage_id'])) {
                $coverageData = [];
                foreach ($data['coverage_id'] as $index => $coverageId) {
                    if (!empty($coverageId) && isset($data['coverage_percentage'][$index])) {
                        $coverageData[$coverageId] = [
                            'percentage' => floatval($data['coverage_percentage'][$index]) / 100
                        ];
                    }
                }
                $policy->coverages()->sync($coverageData);
            } else {
                $policy->coverages()->detach();
            }

            // 4. Delete and Re-create Insurances
            PolicyInsurance::where('policy_id', $id)->delete();
            if (isset($data['insurance_id']) && is_array($data['insurance_id'])) {
                $this->processInsurances($id, $data);
            }

            return $policy;
        });
    }

    private function processInsurances($policyId, $data)
    {
        $newNames = $data['insurance_new_name'] ?? [];
        $isMains = $data['is_main'] ?? [];
        $percentages = $data['percentage'] ?? [];

        foreach ($data['insurance_id'] as $index => $insuranceId) {
            $newName = $newNames[$index] ?? null;

            if (!empty($insuranceId) || !empty($newName)) {
                // Fix: Checkbox hanya kirim key yang checked
                // Kita cek apakah index ini ada di array is_main
                $isMain = in_array($index, array_keys($isMains)) ? 1 : 0;
                
                $percentage = isset($percentages[$index]) ? floatval($percentages[$index]) : 0;

                // Create insurance baru jika belum ada
                if (empty($insuranceId) && !empty($newName)) {
                    $insurance = Insurance::firstOrCreate(['name' => $newName]);
                    $insuranceId = $insurance->id;
                }

                if ($insuranceId) {
                    PolicyInsurance::create([
                        'policy_id' => $policyId,
                        'insurance_id' => $insuranceId,
                        'is_main' => $isMain,
                        'percentage' => $percentage / 100, // Convert % to decimal (15 -> 0.15)
                    ]);
                }
            }
        }
    }
}
