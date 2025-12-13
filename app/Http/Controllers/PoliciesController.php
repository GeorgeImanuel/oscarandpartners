<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Policies\PoliciesService;
use App\Http\Requests\storePoliciesRequest;
use App\Http\Requests\UpdatePoliciesRequest;

class PoliciesController extends Controller
{

    private $policiesService;
    /**
     * @var PoliciesService
     */
    public function __construct(PoliciesService $policiesService)
    {
        $this->policiesService = $policiesService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $policies =  $this->policiesService->all();
        return view('policies.index', compact('policies'));
    }

    /**
     * Create page.
     */
    public function create(Request $request)
    {
        return view('policies.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePoliciesRequest $request)
    {
        $policies = $this->policiesService->create($request->all());
        if(!$policies){
            return redirect()->back()->withInput()->with('error', 'Gagal menambah data policies.');
        }
        return redirect()->route('policies.index')->with('success', 'Berhasil menambah data policies.');
    }

    /**
     * Edit Page
     */
    public function edit(string $id)
    {
        $policies = $this->policiesService->findOrFail($id);
        return view('policies.form', compact('policies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePoliciesRequest $request, string $id)
    {
        $policies = $this->policiesService->update($id, $request->all());
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
        $this->policiesService->delete($id);
        return redirect()->route('policies.index')->with('success', 'Berhasil menghapus data policies.');
    }
}
