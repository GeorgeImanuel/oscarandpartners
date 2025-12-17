<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Insurances\InsurancesService;
use App\Http\Requests\StoreInsurancesRequest;
use App\Http\Requests\UpdateInsurancesRequest;

class InsurancesController extends Controller
{

    private $insurancesService;
    /**
     * @var InsurancesService
     */
    public function __construct(InsurancesService $insurancesService)
    {
        $this->insurancesService = $insurancesService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $insurances =  $this->insurancesService->all();
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
    public function store(StoreInsurancesRequest $request)
    {
        $insurances = $this->insurancesService->create($request->all());
        if(!$insurances){
            return redirect()->back()->withInput()->with('error', 'Gagal menambah data insurances.');
        }
        return redirect()->route('insurances.index')->with('success', 'Berhasil menambah data insurances.');
    }

    /**
     * Edit Page
     */
    public function edit(string $id)
    {
        $insurances = $this->insurancesService->findOrFail($id);
        return view('insurances.form', compact('insurances'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInsurancesRequest $request, string $id)
    {
        $insurances = $this->insurancesService->update($id, $request->all());
        if(!$insurances){
            return redirect()->back()->withInput()->with('error', 'Gagal mengubah data insurances.');
        }
        return redirect()->route('insurances.index')->with('success', 'Berhasil mengubah data insurances.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->insurancesService->delete($id);
        return redirect()->route('insurances.index')->with('success', 'Berhasil menghapus data insurances.');
    }
}
