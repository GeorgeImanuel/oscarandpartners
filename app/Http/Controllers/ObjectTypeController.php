<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ObjectType\ObjectTypeService;
use App\Http\Requests\StoreObjectTypeRequest;
use App\Http\Requests\UpdateObjectTypeRequest;

class ObjectTypeController extends Controller
{
    private $objectTypeService;

    public function __construct(ObjectTypeService $objectTypeService)
    {
        $this->objectTypeService = $objectTypeService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $objectTypes = $this->objectTypeService->all();
        return view('object-types.index', compact('objectTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('object-types.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreObjectTypeRequest $request)
    {
        $validated = $request->validated();
        
        $objectType = $this->objectTypeService->create($validated);
        
        if (!$objectType) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan tipe objek.');
        }

        return redirect()->route('object-types.index')
            ->with('success', 'Tipe objek berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $objectType = $this->objectTypeService->findOrFail($id);
        return view('object-types.detail', compact('objectType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $objectType = $this->objectTypeService->findOrFail($id);
        return view('object-types.form', compact('objectType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateObjectTypeRequest $request, string $id)
    {
        $validated = $request->validated();

        $updated = $this->objectTypeService->update($id, $validated);
        
        if (!$updated) {
            return redirect()->back()->withInput()->with('error', 'Gagal mengubah tipe objek.');
        }

        return redirect()->route('object-types.index')
            ->with('success', 'Tipe objek berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->objectTypeService->delete($id);
        return redirect()->route('object-types.index')
            ->with('success', 'Tipe objek berhasil dihapus.');
    }
}
