<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePoliciesRequest;
use App\Http\Requests\UpdatePoliciesRequest;
use App\Models\Insurance;
use App\Models\ObjectModel;
use App\Models\Policy;
use App\Models\PolicyInsurance;
use App\Services\Coverage\CoverageService;
use App\Services\Currency\CurrencyService;
use App\Services\Insurance\InsuranceService;
use App\Services\Object\ObjectService;
use App\Services\Policy\PolicyService;
use App\Services\PolicyInsurance\PolicyInsuranceService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PolicyController extends Controller
{
    private $policyService;
    private $coverageService;
    private $currencyService;
    private $policyInsuranceService;
    private $objectService;
    private $insuranceService;

    public function __construct(
        PolicyService $policyService,
        PolicyInsuranceService $policyInsuranceService,
        CoverageService $coverageService,
        CurrencyService $currencyService,
        InsuranceService $insuranceService,
        ObjectService $objectService
    ) {
        $this->policyService = $policyService;
        $this->policyInsuranceService = $policyInsuranceService;
        $this->coverageService = $coverageService;
        $this->currencyService = $currencyService;
        $this->insuranceService = $insuranceService;
        $this->objectService = $objectService;
    }

    public function index()
    {
        $policies = $this->policyService->all();
        return view('policies.index', compact('policies'));
    }

    public function create()
    {
        $policies       = $this->policyService->allActive();
        $objects = $this->objectService->all();

        return view('policies.form', compact('objects','policies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePoliciesRequest $request)
    {
        try {
            $this->policyService->createPolicy($request->validated());

            // Handle AJAX request
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Polis berhasil dibuat.',
                    'redirect' => route('policies.index')
                ]);
            }

            return redirect()->route('policies.index')->with('success', 'Polis berhasil dibuat.');
        } catch (\Exception $e) {
            // Handle AJAX request
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membuat polis: ' . $e->getMessage()
                ], 422);
            }

            return redirect()->back()->with('error', 'Gagal membuat polis: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(string $id)
    {
        $policy = $this->policyService->findWithRelations($id);

        if (!$policy) {
            return redirect()->route('policies.index')->with('error', 'Data tidak ditemukan.');
        }

        $objects = ObjectModel::all();
        $coverages = $this->coverageService->all();
        $currencies = $this->currencyService->all();
        $insurances = $this->insuranceService->all();
        
        // Ambil relasi dari policy yang sudah di-load
        $policyInsurances = $policy->policyInsurances;
        $policyCoverages = $policy->policyCoverages;

        return view('policies.form', compact('policy', 'objects', 'coverages', 'currencies', 'insurances', 'policyInsurances', 'policyCoverages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePoliciesRequest $request, string $id)
    {
        try {
            $this->policyService->updatePolicy($id, $request->validated());

            // Handle AJAX request
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Polis berhasil diperbarui.',
                    'redirect' => route('policies.index')
                ]);
            }

            return redirect()->route('policies.index')->with('success', 'Polis berhasil diperbarui.');
        } catch (\Exception $e) {
            // Handle AJAX request
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal update polis: ' . $e->getMessage()
                ], 422);
            }

            return redirect()->back()->with('error', 'Gagal update polis: ' . $e->getMessage())->withInput();
        }
    }

    public function show(string $id)
    {
        $policy = Policy::with([
            'productionCard.client',
            'productionCard.cardType',
            'productionCard.cardStatus',
            'objects.objectInterests.interest',
            'objects.objectOccupation',
            'policyCoverages.coverage', 
            'policyInsurances.insurance',
            'insurances',
            'coverages',
            'currency'
        ])->findOrFail($id);

        return view('policies.detail', compact('policy'));
    }

    public function destroy(string $id)
    {
        try {
            $this->policyService->delete($id);
            return redirect()->route('policies.index')->with('success', 'Policy berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus policy: ' . $e->getMessage());
        }
    }

    public function thisMonth()
    {
        $startDate = Carbon::now()->startOfMonth()->toDateString();
        $endDate = Carbon::now()->endOfMonth()->toDateString();
        
        $month = Carbon::now()->translatedFormat('F');
        $year = Carbon::now()->year;

        // Gunakan service supaya seragam dengan nextMonth
        $policies = $this->policyService->allByActiveDate($startDate, $endDate)
            ->load(['productionCard.client', 'objects.objectInterests']);

        return view('policies.monthly', compact('policies', 'month', 'year'));
    }

    public function nextMonth()
    {
        // Ambil tanggal awal dan akhir bulan depan
        $startDate = Carbon::now()->addMonthNoOverflow()->startOfMonth()->toDateString();
        $endDate = Carbon::now()->addMonthNoOverflow()->endOfMonth()->toDateString();
        
        $month = Carbon::now()->addMonthNoOverflow()->translatedFormat('F');
        $year = Carbon::now()->addMonthNoOverflow()->year;
        // Ambil data dan Load relasi agar bisa di-loop per object di Blade
        $policies = $this->policyService->allByActiveDate($startDate, $endDate)
            ->load(['productionCard.client', 'objects.objectInterests']);

        return view('policies.monthly', compact('policies', 'month', 'year'));
    }    
}