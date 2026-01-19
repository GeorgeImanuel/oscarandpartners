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

            // 1. Simpan Header Policy
            // Kita bagi 100 manual disini agar konsisten input 50% masuk db 0.5
            // (Sesuaikan jika mutator kamu sudah membagi otomatis)
            $policy = Policy::create([
                'policy_number'       => $data['policy_number'],
                'object_id'           => $data['object_id'],
                'coverage_id'         => $data['coverage_id'],
                'currency_code'       => $data['currency_code'], // <-- Ini pasti tersimpan skrg
                'coverage_percentage' => $data['coverage_percentage'] / 100,
                'started_date'        => $data['started_date'],
                'ended_date'          => $data['ended_date'],
            ]);

            // 2. Simpan Detail Asuransi (Panggil helper function)
            $this->processInsurances($policy, $request);

            return redirect()->route('policies.index')->with('success', 'Polis berhasil dibuat.');
        });
    }

    public function edit(string $id)
    {
        $policy = $this->policyService->findOrFail($id);
        $objects = ObjectModel::all();
        $coverages = $this->coverageService->all();
        $currencies = $this->currencyService->all();
        $insurances = $this->insuranceService->all();
        $policyInsurances = $policy->policyInsurances;

        return view('policies.form', compact('policy', 'objects', 'coverages', 'currencies', 'insurances', 'policyInsurances'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePoliciesRequest $request, string $id)
    {
        return DB::transaction(function () use ($request, $id) {
            $policy = Policy::findOrFail($id); // Gunakan Model langsung atau Service
            $data = $request->validated();

            // 1. Update Header
            $policy->update([
                'policy_number'       => $data['policy_number'],
                'object_id'           => $data['object_id'],
                'coverage_id'         => $data['coverage_id'],
                'currency_code'       => $data['currency_code'], // <-- Ini pasti terupdate skrg
                'coverage_percentage' => $data['coverage_percentage'] / 100,
                'started_date'        => $data['started_date'],
                'ended_date'          => $data['ended_date'],
            ]);

            // 2. Hapus Asuransi Lama (Reset)
            $policy->policyInsurances()->delete();

            // 3. Simpan Ulang Asuransi Baru
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