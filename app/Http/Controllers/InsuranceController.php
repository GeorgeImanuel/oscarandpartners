<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Insurance\InsuranceService;
use App\Http\Requests\StoreInsuranceRequest;
use App\Http\Requests\UpdateInsuranceRequest;

class InsuranceController extends Controller
{

    private $insuranceService;
    /**
     * @var InsuranceService
     */
    public function __construct(InsuranceService $insuranceService)
    {
        $this->insuranceService = $insuranceService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $insurances =  $this->insuranceService->all();
        return view('insurances.index', compact('insurances'));
    }

    /**
     * Create page.
     */
    public function create(Request $request)
    {
        return view('insurances.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInsuranceRequest $request)
    {
        $insurance = $this->insuranceService->create($request->all());
        if(!$insurance){
            return redirect()->back()->withInput()->with('error', 'Gagal menambah data insurance.');
        }
        return redirect()->route('insurances.index')->with('success', 'Berhasil menambah data insurance.');
    }

    /**
     * Edit Page
     */
    public function edit(string $id)
    {
        $insurance = $this->insuranceService->findOrFail($id);
        return view('insurances.form', compact('insurance'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInsuranceRequest $request, string $id)
    {
        $insurance = $this->insuranceService->update($id, $request->all());
        if(!$insurance){
            return redirect()->back()->withInput()->with('error', 'Gagal mengubah data insurance.');
        }
        return redirect()->route('insurances.index')->with('success', 'Berhasil mengubah data insurance.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->insuranceService->delete($id);
            return redirect()->route('insurances.index')->with('success', 'Berhasil menghapus data insurance.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
