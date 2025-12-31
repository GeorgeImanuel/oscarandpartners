<?php

namespace App\Http\Controllers;

use App\Models\Policy;
use App\Models\Insurance;
use App\Models\ObjectType;
use Illuminate\Http\Request;
use App\Services\Policy\PolicyService;
use App\Http\Requests\StorePoliciesRequest;
use App\Http\Requests\UpdatePoliciesRequest;
use App\Services\Insurance\InsuranceService;
use App\Services\ObjectType\ObjectTypeService;

class PolicyController extends Controller
{

    private $policyService;
    private $objectTypeService;
    private $insuranceService;
    
    /**
     * @var PolicyService
     */
    public function __construct(
        PolicyService $policyService,
        ObjectTypeService $objectTypeService,
        InsuranceService $insuranceService
    ) {
        $this->policyService = $policyService;
        $this->objectTypeService = $objectTypeService;
        $this->insuranceService = $insuranceService;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $policies =  $this->policyService->all();
        return view('policies.index', compact('policies'));
    }

    /**
     * Create page.
     */
    public function create(Request $request)
    {
        $insurances      = $this->insuranceService->all();
        $object_types    = $this->objectTypeService->all();
        $parent_policies = $this->policyService->all();
    
        return view('policies.form', compact('insurances', 'object_types', 'parent_policies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePoliciesRequest $request)
    {
        $data = $request->validated(); // Ambil data yang sudah divalidasi

        // Simpan policy dulu
        $policy = $this->policyService->create($data);

        // Attach multiple insurances (asumsi relasi many-to-many)
        if (isset($data['insurance_id']) && is_array($data['insurance_id'])) {
            $policy->insurances()->sync($data['insurance_id']); // Sync untuk attach/detach
        }

        return redirect()->route('policies.index')->with('success', 'Policy created successfully!');
    }

    /**
     * Edit Page
     */
    public function edit(string $id)
    {
        $policy          = $this->policyService->findOrFail($id);
        $insurances      = $this->insuranceService->all();
        $object_types    = $this->objectTypeService->all();
        $parent_policies = $this->policyService->all();
        return view('policies.form', compact('policy', 'insurances', 'object_types', 'parent_policies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePoliciesRequest $request, string $id)
    {

        $policies = $this->policyService->update($id, $request->all());
        if(!$policies){
            return redirect()->back()->withInput()->with('error', 'Gagal mengubah data policies.');
        }
        return redirect()->route('policies.index')->with('success', 'Berhasil mengubah data policies.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->policyService->delete($id);
        return redirect()->route('policies.index')->with('success', 'Berhasil menghapus data policies.');
    }
}
