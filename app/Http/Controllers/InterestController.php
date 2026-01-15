<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Interest\InterestService;
use App\Http\Requests\StoreInterestRequest;
use App\Http\Requests\UpdateInterestRequest;

class InterestController extends Controller
{
    private $interestService;
    public function __construct(InterestService $interestService)
    {
        $this->interestService = $interestService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $interests = $this->interestService->all();
        return view('interests.index', compact('interests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('interests.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInterestRequest $request)
    {
        $validated = $request->validated();
        
        $interest = $this->interestService->create($validated);
        
        if (!$interest) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan interest.');
        }

        return redirect()->route('interests.index')
            ->with('success', 'Interest berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $interest = $this->interestService->findOrFail($id);
        return view('interests.detail', compact('interest'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $interest = $this->interestService->findOrFail($id);
        return view('interests.form', compact('interest'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInterestRequest $request, string $id)
    {
        $validated = $request->validated();

        $updated = $this->interestService->update($id, $validated);
        
        if (!$updated) {
            return redirect()->back()->withInput()->with('error', 'Gagal mengubah interest.');
        }

        return redirect()->route('interests.index')
            ->with('success', 'Interest berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->interestService->delete($id);
        return redirect()->route('interests.index')
            ->with('success', 'Interest berhasil dihapus.');
    }
}
