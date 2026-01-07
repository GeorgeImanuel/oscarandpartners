<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ObjectType;
use App\Services\Object\ObjectService;
use App\Services\ObjectType\ObjectTypeService;
use App\Services\Part\PartService;
use App\Http\Requests\StoreObjectRequest;
use App\Http\Requests\UpdateObjectRequest;

class ObjectController extends Controller
{
    private $objectService;
    private $objectTypeService;
    private $partService;

    public function __construct(
        ObjectService $objectService,
        ObjectTypeService $objectTypeService,
        PartService $partService
    )
    {
        $this->objectService = $objectService;
        $this->objectTypeService = $objectTypeService;
        $this->partService = $partService;
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
        $objectTypes = $this->objectTypeService->getAll();
        $parts = $this->partService->getAll();
        return view('objects.form', compact('objectTypes', 'parts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreObjectRequest $request)
    {
        try {
            \Log::info('Store Object Request', [
                'data' => $request->all()
            ]);

            $validated = $request->validated();

            \Log::info('Validated Data', [
                'validated' => $validated
            ]);

            $object = $this->objectService->createWithParts($validated);
            
            if (!$object) {
                \Log::error('Failed to create object - service returned null');
                return redirect()->back()->withInput()->with('error', 'Gagal menambahkan object.');
            }

            \Log::info('Object created successfully', [
                'object_id' => $object->id
            ]);

            return redirect()->route('objects.index')
                ->with('success', 'Object berhasil ditambahkan.');
        } catch (\Exception $e) {
            \Log::error('Exception in store object', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $object = $this->objectService->findOrFail($id);
        $object->load('objectParts');
        return view('objects.detail', compact('object'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $object = $this->objectService->findOrFail($id);
        $object->load('objectParts');
        $objectTypes = $this->objectTypeService->getAll();
        $parts = $this->partService->getAll();
        return view('objects.form', compact('object', 'objectTypes', 'parts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateObjectRequest $request, string $id)
    {
        $validated = $request->validated();

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
