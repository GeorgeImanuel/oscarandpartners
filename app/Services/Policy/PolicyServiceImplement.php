<?php

namespace App\Services\Policy;

use LaravelEasyRepository\Service;
use App\Repositories\Policy\PolicyRepository;
use Illuminate\Support\Facades\DB;
use App\Models\Insurance;
use App\Models\ObjectCoverage;
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

    public function allFree()
    {
        return $this->mainRepository->allFree();
    }

    public function allActive()
    {
        return $this->mainRepository->allActive();
    }

    public function findWithRelations($policyId)
    {
        return $this->mainRepository->findWithRelations($policyId);
    }

    public function allByActiveDate($startDate, $endDate)
    {
        return $this->mainRepository->allByActiveDate($startDate, $endDate);
    }

    public function createPolicy(array $data)
    {
        return DB::transaction(function () use ($data) {
            // 1. Create Policy Header
            $policy = $this->mainRepository->create([
                'policy_number' => $data['policy_number'],
                'currency_code' => $data['currency_code'] ?? null,
                'started_date' => $data['started_date'],
                'ended_date' => $data['ended_date'],
            ]);

            // 2. Attach Objects (Many-to-Many)
            if (isset($data['object_id']) && is_array($data['object_id'])) {
                $policy->objects()->sync(array_filter($data['object_id']));
            }

            $this->syncObjectCoverages($policy->id, $data);

            // 3. Attach Coverages with Percentage (Pivot Table)
            if (isset($data['coverage_code']) && is_array($data['coverage_code'])) {
                $coverageData = [];
                foreach ($data['coverage_code'] as $index => $coverageId) {
                    if (!empty($coverageId) && isset($data['coverage_percentage'][$index])) {
                        $coverageData[$coverageId] = [
                            'percentage' => floatval($data['coverage_percentage'][$index]) / 100 // Convert to decimal
                        ];
                    }
                }
                $policy->coverages()->sync($coverageData);
            }

            // 4. Process Insurances
            // Delete any existing insurances first (in case of retry/duplicate)
            PolicyInsurance::where('policy_id', $policy->id)->delete();
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

            // 1. Update Policy Header
            $policy->update([
                'policy_number' => $data['policy_number'],
                'currency_code' => $data['currency_code'] ?? null,
                'started_date' => $data['started_date'],
                'ended_date' => $data['ended_date'],
            ]);

            // 2. Sync Objects
            if (isset($data['object_id']) && is_array($data['object_id'])) {
                $policy->objects()->sync(array_filter($data['object_id']));
            } else {
                $policy->objects()->detach();
            }

            $this->syncObjectCoverages($policy->id, $data);

            // 3. Sync Coverages with Percentage
            if (isset($data['coverage_code']) && is_array($data['coverage_code'])) {
                $coverageData = [];
                foreach ($data['coverage_code'] as $index => $coverageId) {
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

        // Track processed insurance IDs to avoid duplicates
        $processedInsuranceIds = [];

        foreach ($data['insurance_id'] as $index => $insuranceId) {
            $newName = $newNames[$index] ?? null;

            if (!empty($insuranceId) || !empty($newName)) {
                // Check is_main value for this index
                $isMain = isset($isMains[$index]) && $isMains[$index] == '1' ? 1 : 0;
                
                $percentage = isset($percentages[$index]) ? floatval($percentages[$index]) : 0;

                // Create insurance baru jika belum ada
                if (empty($insuranceId) && !empty($newName)) {
                    $insurance = Insurance::firstOrCreate(['name' => $newName]);
                    $insuranceId = $insurance->id;
                }

                if ($insuranceId) {
                    // Check if this insurance_id was already processed
                    if (in_array($insuranceId, $processedInsuranceIds)) {
                        \Log::warning("Duplicate insurance_id detected: {$insuranceId} for policy {$policyId}, skipping...");
                        continue;
                    }

                    // Use updateOrCreate to handle duplicates gracefully
                    PolicyInsurance::updateOrCreate(
                        [
                            'policy_id' => $policyId,
                            'insurance_id' => $insuranceId,
                        ],
                        [
                            'is_main' => $isMain,
                            'percentage' => $percentage / 100, // Convert % to decimal (15 -> 0.15)
                        ]
                    );

                    // Mark this insurance as processed
                    $processedInsuranceIds[] = $insuranceId;
                }
            }
        }
    }

    private function syncObjectCoverages(int $policyId, array $data): void
    {
        $objectIds = DB::table('policy_objects')
            ->where('policy_id', $policyId)
            ->pluck('object_id')
            ->toArray();

        if (empty($objectIds)) {
            return;
        }

        $hasPolicyCoverage = false;
        if (isset($data['coverage_code']) && is_array($data['coverage_code'])) {
            foreach ($data['coverage_code'] as $coverageCode) {
                if (!empty($coverageCode)) {
                    $hasPolicyCoverage = true;
                    break;
                }
            }
        }

        // Policy-level coverage takes priority for premium calculation.
        // Keep object-level data intact to avoid unintended data loss.
        if ($hasPolicyCoverage) {
            return;
        }

        foreach ($objectIds as $objectId) {
            ObjectCoverage::where('object_id', $objectId)->delete();
        }

        if (!isset($data['object_id']) || !is_array($data['object_id'])) {
            return;
        }

        foreach ($data['object_id'] as $index => $objectId) {
            if (empty($objectId)) {
                continue;
            }

            $coverageCodes = isset($data['object_coverage_code'][$index]) && is_array($data['object_coverage_code'][$index])
                ? $data['object_coverage_code'][$index]
                : [];

            $coveragePercentages = isset($data['object_coverage_percentage'][$index]) && is_array($data['object_coverage_percentage'][$index])
                ? $data['object_coverage_percentage'][$index]
                : [];

            foreach ($coverageCodes as $coverageIndex => $coverageCode) {
                if (empty($coverageCode)) {
                    continue;
                }

                ObjectCoverage::create([
                    'object_id' => (int) $objectId,
                    'coverage_code' => $coverageCode,
                    'percentage' => ((float) ($coveragePercentages[$coverageIndex] ?? 0)) / 100,
                ]);
            }
        }
    }

    /**
     * Delete policy with relationship check
     */
    public function delete($id)
    {
        $policy = $this->mainRepository->find($id);
        
        if (!$policy) {
            throw new \Exception('Data tidak ditemukan.');
        }

        // Check if policy has cards
        if (\App\Models\Card::where('policy_id', $id)->count() > 0) {
            throw new \Exception('Tidak bisa hapus data, data sudah digunakan di data lain.');
        }

        // Check if policy has claims
        if (\App\Models\Claim::where('policy_id', $id)->count() > 0) {
            throw new \Exception('Tidak bisa hapus data, data sudah digunakan di data lain.');
        }

        // Delete related records first
        DB::transaction(function () use ($policy) {
            // Delete policy insurances
            \App\Models\PolicyInsurance::where('policy_id', $policy->id)->delete();
            
            // Delete policy coverages
            \App\Models\PolicyCoverage::where('policy_id', $policy->id)->delete();
            
            // Delete policy objects
            \App\Models\PolicyObject::where('policy_id', $policy->id)->delete();

            // Delete the policy
            $policy->delete();
        });

        return true;
    }
}
