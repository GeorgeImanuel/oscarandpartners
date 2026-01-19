<?php

namespace App\Http\Controllers;

use App\Models\Policy;
use App\Models\ObjectModel;
use App\Models\Insurance;
use App\Models\PolicyInsurance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Tambahkan ini untuk Transaction
use App\Http\Requests\UpdatePoliciesRequest;
use App\Http\Requests\StorePoliciesRequest;
use App\Services\Policy\PolicyService;
use App\Services\Coverage\CoverageService;
use App\Services\Currency\CurrencyService;
use App\Services\PolicyInsurance\PolicyInsuranceService;
use App\Services\Insurance\InsuranceService;

class PolicyController extends Controller
{
    private $policyService;
    private $coverageService;
    private $currencyService;
    private $policyInsuranceService;
    private $insuranceService;

    public function __construct(
        PolicyService $policyService,
        PolicyInsuranceService $policyInsuranceService,
        CoverageService $coverageService,
        CurrencyService $currencyService,
        InsuranceService $insuranceService
    ) {
        $this->policyService = $policyService;
        $this->policyInsuranceService = $policyInsuranceService;
        $this->coverageService = $coverageService;
        $this->currencyService = $currencyService;
        $this->insuranceService = $insuranceService;
    }

    public function index()
    {
        $policies = $this->policyService->all();
        return view('policies.index', compact('policies'));
    }

    public function create()
    {
        $objects = ObjectModel::all();
        $coverages = $this->coverageService->all();
        $currencies = $this->currencyService->all();
        $insurances = $this->insuranceService->all();

        return view('policies.form', compact('objects', 'coverages', 'currencies', 'insurances'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePoliciesRequest $request)
    {
        // Gunakan Transaction agar Data Konsisten
        return DB::transaction(function () use ($request) {
            $data = $request->validated();

            // Hitung total coverage percentages
            $totalCoveragePercentages = 0;
            if ($request->has('coverage_percentage')) {
                foreach ($request->coverage_percentage as $percentage) {
                    $totalCoveragePercentages += floatval($percentage);
                }
            }

            // 1. Simpan Header Policy (tanpa coverage_id dan coverage_percentage)
            $policy = Policy::create([
                'policy_number'         => $data['policy_number'],
                'object_id'             => $data['object_id'],
                'currency_code'         => $data['currency_code'] ?? null,
                'started_date'          => $data['started_date'],
                'ended_date'            => $data['ended_date'],
                'coverage_percentages'  => $totalCoveragePercentages,
            ]);

            // 2. Simpan Coverages dengan pivot (many-to-many)
            $this->processCoverages($policy, $request);

            // 3. Simpan Detail Asuransi
            $this->processInsurances($policy, $request);

            return redirect()->route('policies.index')->with('success', 'Polis berhasil dibuat.');
        });
    }

    public function edit(string $id)
    {
        $policy = Policy::with(['coverages', 'policyInsurances', 'policyCoverages'])->findOrFail($id);
        $objects = ObjectModel::all();
        $coverages = $this->coverageService->all();
        $currencies = $this->currencyService->all();
        $insurances = $this->insuranceService->all();
        $policyInsurances = $policy->policyInsurances;
        $policyCoverages = $policy->policyCoverages;

        return view('policies.form', compact('policy', 'objects', 'coverages', 'currencies', 'insurances', 'policyInsurances', 'policyCoverages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePoliciesRequest $request, string $id)
    {
        return DB::transaction(function () use ($request, $id) {
            $policy = Policy::findOrFail($id);
            $data = $request->validated();

            // Hitung total coverage percentages
            $totalCoveragePercentages = 0;
            if ($request->has('coverage_percentage')) {
                foreach ($request->coverage_percentage as $percentage) {
                    $totalCoveragePercentages += floatval($percentage);
                }
            }

            // 1. Update Header (tanpa coverage_id dan coverage_percentage)
            $policy->update([
                'policy_number'         => $data['policy_number'],
                'object_id'             => $data['object_id'],
                'currency_code'         => $data['currency_code'] ?? null,
                'started_date'          => $data['started_date'],
                'ended_date'            => $data['ended_date'],
                'coverage_percentages'  => $totalCoveragePercentages,
            ]);

            // 2. Update Coverages (sync pivot table)
            $this->processCoverages($policy, $request);

            // 3. Hapus Asuransi Lama (Reset)
            $policy->policyInsurances()->delete();

            // 4. Simpan Ulang Asuransi Baru
            $this->processInsurances($policy, $request);

            return redirect()->route('policies.index')->with('success', 'Polis berhasil diperbarui.');
        });
    }

    public function destroy(string $id)
    {
        try {
            $policy = $this->policyService->findOrFail($id);
            $policy->delete(); // Soft delete sesuai Model

            return redirect()->route('policies.index')
                ->with('success', 'Policy berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus policy: ' . $e->getMessage());
        }
    }

    /**
     * Helper function untuk menyimpan coverages dengan pivot table
     */
    private function processCoverages(Policy $policy, $request)
    {
        if ($request->has('coverage_id')) {
            $coverageData = [];
            
            foreach ($request->coverage_id as $index => $coverageId) {
                if (!empty($coverageId) && isset($request->coverage_percentage[$index])) {
                    // Simpan persentase apa adanya (tidak dibagi 100)
                    $coverageData[$coverageId] = [
                        'percentage' => $request->coverage_percentage[$index]
                    ];
                }
            }
            
            // Sync coverages (akan auto delete yang tidak ada, dan update/insert yang baru)
            $policy->coverages()->sync($coverageData);
        }
    }

    /**
     * Helper function untuk menyimpan detail asuransi
     * Digunakan oleh store() dan update() agar tidak duplikasi kodingan
     */
    private function processInsurances(Policy $policy, $request)
    {
        if ($request->has('insurance_id')) {
            foreach ($request->insurance_id as $index => $insuranceId) {
                
                // A. LOGIKA ASURANSI BARU VS LAMA
                // Jika ID kosong tapi ada nama baru, create Insurance dulu
                $finalInsuranceId = $insuranceId;
                if (empty($insuranceId) && !empty($request->insurance_new_name[$index])) {
                    $newIns = Insurance::firstOrCreate(
                        ['name' => $request->insurance_new_name[$index]]
                    );
                    $finalInsuranceId = $newIns->id;
                }

                // Skip jika tidak ada ID valid
                if (!$finalInsuranceId) continue;

                // B. LOGIKA IS_MAIN (SOLUSI BUG CHECKBOX)
                // Cek apakah index looping ($index) ada di array is_main request
                // HTML Checkbox tidak mengirim key jika uncheck, jadi kita harus cek key-nya manual
                $isMain = isset($request->is_main) && array_key_exists($index, $request->is_main) ? 1 : 0;

                // C. SIMPAN KE POLICY_INSURANCES
                $policy->policyInsurances()->create([
                    'insurance_id' => $finalInsuranceId,
                    'percentage'   => $request->percentage[$index] / 100, // Konversi persen ke desimal
                    'is_main'      => $isMain, // <-- Nilai 1 atau 0 yang sudah valid
                ]);
            }
        }
    }
}