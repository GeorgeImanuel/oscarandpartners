<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ObjectType;
use App\Services\Object\ObjectService;

class ObjectController extends Controller
{
    private $objectService;

    public function __construct(ObjectService $objectService)
    {
        $this->objectService = $objectService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $objects = $this->objectService->getAllWithRelations();
        return view('objects.index', compact('objects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $objectTypes = $this->objectService->getObjectTypes();
        $parts = $this->objectService->getParts();
        return view('objects.form', compact('objectTypes', 'parts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'object_type_id' => 'nullable|exists:object_types,id',
            'object_type_name' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'part_id' => 'nullable|array',
            'part_name' => 'nullable|array',
            'part_name.*' => 'nullable|string|max:255',
            'part_value' => 'nullable|array',
            'part_value.*' => 'nullable|numeric|min:0',
        ]);

        // Custom validation for part_id
        if (isset($validated['part_id']) && is_array($validated['part_id'])) {
            foreach ($validated['part_id'] as $index => $partId) {
                $partName = $validated['part_name'][$index] ?? '';
                // If part_id is not empty, validate it exists in database
                if (!empty($partId) && $partId !== '' && !empty(trim($partId))) {
                    if (!is_numeric($partId) || !\App\Models\Part::where('id', $partId)->exists()) {
                        return redirect()->back()->withInput()->with('error', 'Part yang dipilih tidak valid.');
                    }
                }
                // If both part_id and part_name are empty, error
                elseif (empty($partName) || trim($partName) === '') {
                    return redirect()->back()->withInput()->with('error', 'Setiap part harus dipilih atau diisi dengan nama baru.');
                }
            }
        }

        // Validate that either object_type_id or object_type_name is provided
        if (empty($validated['object_type_id']) && empty($validated['object_type_name'])) {
            return redirect()->back()->withInput()->with('error', 'Tipe objek harus dipilih atau diisi.');
        }

        $object = $this->objectService->createWithParts($validated);
        
        if (!$object) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan object.');
        }

        return redirect()->route('objects.index')
            ->with('success', 'Object berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $object = $this->objectService->findOrFail($id);
        $object->load('objectParts.part');
        return view('objects.detail', compact('object'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $object = $this->objectService->findOrFail($id);
        $object->load('objectParts.part');
        $objectTypes = $this->objectService->getObjectTypes();
        $parts = $this->objectService->getParts();
        return view('objects.form', compact('object', 'objectTypes', 'parts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'object_type_id' => 'nullable|exists:object_types,id',
            'object_type_name' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'part_id' => 'nullable|array',
            'part_name' => 'nullable|array',
            'part_name.*' => 'nullable|string|max:255',
            'part_value' => 'nullable|array',
            'part_value.*' => 'nullable|numeric|min:0',
        ]);

        // Custom validation for part_id
        if (isset($validated['part_id']) && is_array($validated['part_id'])) {
            foreach ($validated['part_id'] as $index => $partId) {
                $partName = $validated['part_name'][$index] ?? '';
                // If part_id is not empty, validate it exists in database
                if (!empty($partId) && $partId !== '' && !empty(trim($partId))) {
                    if (!is_numeric($partId) || !\App\Models\Part::where('id', $partId)->exists()) {
                        return redirect()->back()->withInput()->with('error', 'Part yang dipilih tidak valid.');
                    }
                }
                // If both part_id and part_name are empty, error
                elseif (empty($partName) || trim($partName) === '') {
                    return redirect()->back()->withInput()->with('error', 'Setiap part harus dipilih atau diisi dengan nama baru.');
                }
            }
        }

        // Validate that either object_type_id or object_type_name is provided
        if (empty($validated['object_type_id']) && empty($validated['object_type_name'])) {
            return redirect()->back()->withInput()->with('error', 'Tipe objek harus dipilih atau diisi.');
        }

        $updated = $this->objectService->updateWithParts($id, $validated);
        
        if (!$updated) {
            return redirect()->back()->withInput()->with('error', 'Gagal mengubah object.');
        }

        return redirect()->route('objects.index')
            ->with('success', 'Object berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->objectService->delete($id);
        return redirect()->route('objects.index')
            ->with('success', 'Object berhasil dihapus.');
    }
}
