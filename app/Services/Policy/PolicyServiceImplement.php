
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
            $policy = $this->mainRepository->create([
                'policy_number' => $data['policy_number'],
                'object_id' => $data['object_id'] ?? null,
                'coverage_id' => $data['coverage_id'] ?? null,
                'coverage_percentage' => $data['coverage_percentage'],
                'started_date' => $data['started_date'],
                'ended_date' => $data['ended_date'],
            ]);

            if (isset($data['insurance_id']) && is_array($data['insurance_id'])) {
                $newNames = $data['insurance_new_name'] ?? [];
                $isMains = $data['is_main'] ?? [];
                $percentages = $data['percentage'] ?? [];

                foreach ($data['insurance_id'] as $index => $insuranceId) {
                    $newName = $newNames[$index] ?? null;

                    if (!empty($insuranceId) || !empty($newName)) {
                        $isMain = isset($isMains[$index]) ? (bool)$isMains[$index] : false;
                        $percentage = isset($percentages[$index]) ? floatval($percentages[$index]) : 0;

                        if (empty($insuranceId) && !empty($newName)) {
                            $insurance = Insurance::firstOrCreate(['name' => $newName]);
                            $insuranceId = $insurance->id;
                        }

                        PolicyInsurance::create([
                            'policy_id' => $policy->id,
                            'insurance_id' => $insuranceId,
                            'is_main' => $isMain,
                            'percentage' => $percentage,
                        ]);
                    }
                }
            }

            return $policy;
        });
    }

    public function updatePolicy($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $policy = $this->mainRepository->findOrFail($id);

            $policy->update([
                'policy_number' => $data['policy_number'],
                'object_id' => $data['object_id'] ?? null,
                'coverage_code' => $data['coverage_code'] ?? null,
                'coverage_percentage' => $data['coverage_percentage'],
                'started_date' => $data['started_date'],
                'ended_date' => $data['ended_date'],
            ]);

            // Delete existing insurances
            PolicyInsurance::where('policy_id', $id)->delete();

            // Save new insurances
            if (isset($data['insurance_id']) && is_array($data['insurance_id'])) {
                $newNames = $data['insurance_new_name'] ?? [];
                $isMains = $data['is_main'] ?? [];
                $percentages = $data['percentage'] ?? [];

                foreach ($data['insurance_id'] as $index => $insuranceId) {
                    $newName = $newNames[$index] ?? null;

                    if (!empty($insuranceId) || !empty($newName)) {
                        $isMain = isset($isMains[$index]) ? (bool)$isMains[$index] : false;
                        $percentage = isset($percentages[$index]) ? floatval($percentages[$index]) : 0;

                        if (empty($insuranceId) && !empty($newName)) {
                            $insurance = Insurance::firstOrCreate(['name' => $newName]);
                            $insuranceId = $insurance->id;
                        }

                        PolicyInsurance::create([
                            'policy_id' => $id,
                            'insurance_id' => $insuranceId,
                            'is_main' => $isMain,
                            'percentage' => $percentage,
                        ]);
                    }
                }
            }

            return $policy;
        });
    }
}
