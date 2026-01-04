<?php

namespace App\Http\Controllers;

use App\Models\Policy;
use App\Models\ObjectModel;
use Illuminate\Http\Request;
use App\Services\Policy\PolicyService;
use App\Http\Requests\UpdatePoliciesRequest;
use App\Models\Insurance;
use App\Models\PolicyInsurance;
use App\Services\Coverage\CoverageService;
use App\Services\PolicyInsurance\PolicyInsuranceService;
use App\Services\Insurance\InsuranceService;

class PolicyController extends Controller
{
    private $policyService;
    private $coverageService;
    private $policyInsuranceService;
    private $insuranceService;

    public function __construct(
        PolicyService $policyService,
        PolicyInsuranceService $policyInsuranceService,
        CoverageService $coverageService,
        InsuranceService $insuranceService
    ) {
        $this->policyService = $policyService;
        $this->policyInsuranceService = $policyInsuranceService;
        $this->coverageService = $coverageService;
        $this->insuranceService = $insuranceService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $policies = $this->policyService->all();
        return view('policies.index', compact('policies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $objects = ObjectModel::all();
        $coverages = $this->coverageService->all();
        $insurances = $this->insuranceService->all();

        return view('policies.form', compact('objects', 'coverages', 'insurances'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'object_id' => 'nullable|exists:objects,id',
            'coverage_code' => 'nullable|exists:coverages,code',
            'coverage_percentage' => 'required|numeric',
            'policy_number' => 'required|string|max:255|unique:policies,policy_number',
            'started_date' => 'required|date',
            'ended_date' => 'required|date|after_or_equal:started_date',
            'insurance_id' => 'nullable|array',
            'insurance_id.*' => 'nullable|integer|exists:insurances,id',
            'is_main' => 'nullable|array',
            'percentage' => 'nullable|array',
            'percentage.*' => 'nullable|numeric|min:0|max:100',
        ]);

        try {
            $policy = Policy::create([
                'policy_number' => $validated['policy_number'],
                'object_id' => $validated['object_id'] ?? null,
                'coverage_code' => $validated['coverage_code'] ?? null,
                'coverage_percentage' => $validated['coverage_percentage'],
                'started_date' => $validated['started_date'],
                'ended_date' => $validated['ended_date'],
            ]);

            // Save policy insurances
            if (isset($validated['insurance_id']) && is_array($validated['insurance_id'])) {
                foreach ($validated['insurance_id'] as $index => $insuranceId) {
                    if (!empty($insuranceId)) {
                        $isMain = isset($validated['is_main'][$index]) ? (bool)$validated['is_main'][$index] : false;
                        $percentage = isset($validated['percentage'][$index]) ? floatval($validated['percentage'][$index]) : 0;

                        PolicyInsurance::create([
                            'policy_id' => $policy->id,
                            'insurance_id' => $insuranceId,
                            'is_main' => $isMain,
                            'percentage' => $percentage,
                        ]);
                    }
                }
            }

            return redirect()->route('policies.index')
                ->with('success', 'Policy berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan policy: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $policy = $this->policyService->findOrFail($id);
        $objects = ObjectModel::all();
        $coverages = $this->coverageService->all();
        $insurances = $this->insuranceService->all();
        $policyInsurances = $policy->policyInsurances;

        return view('policies.form', compact('policy', 'objects', 'coverages', 'insurances', 'policyInsurances'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePoliciesRequest $request, string $id)
    {
        try {
            $policy = $this->policyService->findOrFail($id);

            $validated = $request->validated();

            $policy->update([
                'policy_number' => $validated['policy_number'],
                'object_id' => $validated['object_id'] ?? null,
                'coverage_code' => $validated['coverage_code'] ?? null,
                'coverage_percentage' => $validated['coverage_percentage'],
                'started_date' => $validated['started_date'],
                'ended_date' => $validated['ended_date'],
            ]);

            // Delete existing insurances
            PolicyInsurance::where('policy_id', $id)->delete();

            // Save new insurances
            if (isset($validated['insurance_id']) && is_array($validated['insurance_id'])) {
                foreach ($validated['insurance_id'] as $index => $insuranceId) {
                    if (!empty($insuranceId)) {
                        $isMain = isset($validated['is_main'][$index]) ? (bool)$validated['is_main'][$index] : false;
                        $percentage = isset($validated['percentage'][$index]) ? floatval($validated['percentage'][$index]) : 0;

                        PolicyInsurance::create([
                            'policy_id' => $id,
                            'insurance_id' => $insuranceId,
                            'is_main' => $isMain,
                            'percentage' => $percentage,
                        ]);
                    }
                }
            }

            return redirect()->route('policies.index')
                ->with('success', 'Policy berhasil diubah.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengubah policy: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $policy = $this->policyService->findOrFail($id);
            $policy->delete();

            return redirect()->route('policies.index')
                ->with('success', 'Policy berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus policy: ' . $e->getMessage());
        }
    }
}
