<?php

namespace App\Http\Controllers;

use App\Models\Policy;
use App\Models\ObjectModel;
use App\Models\Insurance;
use App\Models\PolicyInsurance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        try {
            $this->policyService->createPolicy($request->validated());

            return redirect()->route('policies.index')->with('success', 'Polis berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membuat polis: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(string $id)
    {
        $policy = $this->policyService->findPoliciesWithRelations($id);

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

            return redirect()->route('policies.index')->with('success', 'Polis berhasil diperbarui.');
        } catch (\Exception $e) {
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
    
}