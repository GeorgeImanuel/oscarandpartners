<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ObjectOccupation\ObjectOccupationService;
use App\Http\Requests\StoreObjectOccupationRequest;
use App\Http\Requests\UpdateObjectOccupationRequest;

class ObjectOccupationController extends Controller
{
    private $objectOccupationService;

    public function __construct(ObjectOccupationService $objectOccupationService)
    {
        $this->objectOccupationService = $objectOccupationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $objectOccupations = $this->objectOccupationService->all();
        return view('object-occupations.index', compact('objectOccupations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('object-occupations.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreObjectOccupationRequest $request)
    {
        $validated = $request->validated();

        $objectOccupation = $this->objectOccupationService->create($validated);
        
        if (!$objectOccupation) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan objek occupation.');
        }

        return redirect()->route('object-occupations.index')
            ->with('success', 'occupation objek berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $objectOccupation = $this->objectOccupationService->findOrFail($id);
        return view('object-occupations.detail', compact('objectOccupation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $objectOccupation = $this->objectOccupationService->findOrFail($id);
        return view('object-occupations.form', compact('objectOccupation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateObjectOccupationRequest $request, string $id)
    {
        $validated = $request->validated();

        $updated = $this->objectOccupationService->update($id, $validated);
        
        if (!$updated) {
            return redirect()->back()->withInput()->with('error', 'Gagal mengubah objek occupation.');
        }

        return redirect()->route('object-occupations.index')
            ->with('success', 'Objek occupation berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->objectOccupationService->delete($id);
            return redirect()->route('object-occupations.index')
                ->with('success', 'Objek occupation berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
