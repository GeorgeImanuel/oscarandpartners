<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Part\PartService;
use App\Http\Requests\StorePartRequest;
use App\Http\Requests\UpdatePartRequest;

class PartController extends Controller
{
    private $partService;

    public function __construct(PartService $partService)
    {
        $this->partService = $partService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $parts = $this->partService->all();
        return view('parts.index', compact('parts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('parts.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePartRequest $request)
    {
        $validated = $request->validated();
        
        $part = $this->partService->create($validated);
        
        if (!$part) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan part.');
        }

        return redirect()->route('parts.index')
            ->with('success', 'Part berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $part = $this->partService->findOrFail($id);
        return view('parts.detail', compact('part'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $part = $this->partService->findOrFail($id);
        return view('parts.form', compact('part'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePartRequest $request, string $id)
    {
        $validated = $request->validated();

        $updated = $this->partService->update($id, $validated);
        
        if (!$updated) {
            return redirect()->back()->withInput()->with('error', 'Gagal mengubah part.');
        }

        return redirect()->route('parts.index')
            ->with('success', 'Part berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->partService->delete($id);
        return redirect()->route('parts.index')
            ->with('success', 'Part berhasil dihapus.');
    }
}
