<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Claim\ClaimService;
use App\Services\Policy\PolicyService;
use App\Http\Requests\StoreClaimRequest;
use App\Http\Requests\UpdateClaimRequest;

class ClaimController extends Controller
{
    private $claimService;
    private $policyService;

    public function __construct(
        ClaimService $claimService,
        PolicyService $policyService
    ) {
        $this->claimService = $claimService;
        $this->policyService = $policyService;
    }

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');

        $claims = $this->claimService->getAllWithRelations($perPage, $search);

        return view('claims.index', compact('claims'));
    }

    public function create()
    {
        $policies = $this->policyService->all();
        return view('claims.create', compact('policies'));
    }

    public function store(StoreClaimRequest $request)
    {
        try {
            $this->claimService->create($request->validated());
            
            return redirect()
                ->route('claims.index')
                ->with('success', 'Claim berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $claim = $this->claimService->find($id);
        
        if (!$claim) {
            return redirect()
                ->route('claims.index')
                ->with('error', 'Claim tidak ditemukan.');
        }

        return view('claims.show', compact('claim'));
    }

    public function edit($id)
    {
        $claim = $this->claimService->find($id);
        
        if (!$claim) {
            return redirect()
                ->route('claims.index')
                ->with('error', 'Claim tidak ditemukan.');
        }

        $policies = $this->policyService->all();
        
        return view('claims.edit', compact('claim', 'policies'));
    }

    public function update(UpdateClaimRequest $request, $id)
    {
        try {
            $this->claimService->update($id, $request->validated());
            
            return redirect()
                ->route('claims.show', $id)
                ->with('success', 'Claim berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->claimService->delete($id);
            
            return redirect()
                ->route('claims.index')
                ->with('success', 'Claim berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()
                ->route('claims.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
