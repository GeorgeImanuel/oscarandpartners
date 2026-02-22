<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Coverage\CoverageService;
use App\Http\Requests\StoreCoverageRequest;
use App\Http\Requests\UpdateCoverageRequest;

class CoverageController extends Controller
{

    private $coverageService;
    /**
     * @var CoverageService
     */
    public function __construct(CoverageService $coverageService)
    {
        $this->coverageService = $coverageService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coverages =  $this->coverageService->all();
        return view('coverages.index', compact('coverages'));
    }

    /**
     * Create page.
     */
    public function create(Request $request)
    {
        return view('coverages.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCoverageRequest $request)
    {
        $coverage = $this->coverageService->create($request->all());
        if(!$coverage){
            return redirect()->back()->withInput()->with('error', 'Gagal menambah data coverage.');
        }
        return redirect()->route('coverages.index')->with('success', 'Berhasil menambah data coverage.');
    }

    /**
     * Edit Page
     */
    public function edit(string $id)
    {
        $coverage = $this->coverageService->findOrFail($id);
        return view('coverages.form', compact('coverage'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCoverageRequest $request, string $id)
    {
        $coverage = $this->coverageService->update($id, $request->all());
        if(!$coverage){
            return redirect()->back()->withInput()->with('error', 'Gagal mengubah data coverage.');
        }
        return redirect()->route('coverages.index')->with('success', 'Berhasil mengubah data coverage.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->coverageService->delete($id);
            return redirect()->route('coverages.index')->with('success', 'Berhasil menghapus data coverage.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
