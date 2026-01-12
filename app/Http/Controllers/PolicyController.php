<?php

namespace App\Http\Controllers;

use App\Models\Policy;
use App\Models\ObjectModel;
use Illuminate\Http\Request;
use App\Services\Policy\PolicyService;
use App\Http\Requests\UpdatePoliciesRequest;
use App\Http\Requests\StorePoliciesRequest;
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
    // Pastikan use di bagian atas:

    public function store(StorePoliciesRequest $request) // <-- Pakai class request kamu
    {
        // 1. Ambil data yang sudah lolos validasi
        // Ini otomatis menyertakan object_id, coverage_code, started_date, dll.
        $data = $request->validated();

        // 2. Simpan ke database
        // Catatan: Jangan bagi 100 di sini karena di Model Policy sudah ada Mutator
        // yang otomatis membagi nilai dengan 100.
        $policy = Policy::create($data);

        // 3. Logika tambahan untuk Detail Asuransi (Opsional)
        // Karena di request ada 'insurance_id' dan 'percentage' (array)
        // Kamu bisa melanjutkannya di sini jika dibutuhkan...

        return redirect()->route('policies.index')->with('success', 'Policy created successfully');
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
            $data = $request->validated();
            
            // KONVERSI KE DESIMAL 0.0 - 1.0
            $data['coverage_percentage'] = $request->coverage_percentage / 100;
            
            // Jika ada array percentage untuk detail asuransi:
            if ($request->has('percentage')) {
                $formattedPercentages = [];
                foreach ($request->percentage as $val) {
                    $formattedPercentages[] = $val / 100;
                }
                $data['percentage'] = $formattedPercentages;
            }

            $this->policyService->updatePolicy($id, $data);

            return redirect()->route('policies.index')->with('success', 'Policy berhasil diubah.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal: ' . $e->getMessage());
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
