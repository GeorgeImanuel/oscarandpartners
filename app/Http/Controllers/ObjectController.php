<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ObjectOccupation;
use App\Services\Object\ObjectService;
use App\Services\ObjectOccupation\ObjectOccupationService;
use App\Services\Interest\InterestService;
use App\Http\Requests\StoreObjectRequest;
use App\Http\Requests\UpdateObjectRequest;
use Illuminate\Support\Facades\Log; // Pastikan ini ada

class ObjectController extends Controller
{
    private $objectService;
    private $objectOccupationService;
    private $interestService;

    public function __construct(
        ObjectService $objectService,
        ObjectOccupationService $objectOccupationService,
        InterestService $interestService
    )
    {
        $this->objectService = $objectService;
        $this->objectOccupationService = $objectOccupationService;
        $this->interestService = $interestService;
    }

    public function index()
    {
        $objects = $this->objectService->allWithRelations();
        return view('objects.index', compact('objects'));
    }

    public function create()
    {
        $objectOccupations = $this->objectOccupationService->getAll();
        $interests = $this->interestService->getAll();
        return view('objects.form', compact('objectOccupations', 'interests'));
    }

    /**
     * Store a newly created resource in storage.
     * BAGIAN INI YANG DIPERBAIKI
     */
    public function store(StoreObjectRequest $request)
    {
        try {
            Log::info('Store Object Request', ['data' => $request->all()]);

            $validated = $request->validated();

            // --- MULAI PERBAIKAN ---
            // 1. Ambil ID Occupation (gunakan input() agar aman jika null)
            $occupationId = $request->input('object_occupation_id');

            // 2. Cek Logic: Jika ID Kosong TAPI ada Nama Baru (fitur Tambah Baru)
            if (empty($occupationId) && $request->filled('object_occupation_name')) {
                // Buat Occupation baru lewat Service
                try {
                    // Asumsi service kamu punya method create/store. 
                    // Jika error, sesuaikan dengan method yang ada di ObjectOccupationService kamu.
                    $newOccupation = $this->objectOccupationService->create([
                        'name' => $request->object_occupation_name
                    ]);
                    
                    $occupationId = $newOccupation->id;
                    Log::info('Created new occupation', ['id' => $occupationId]);
                } catch (\Exception $e) {
                    return redirect()->back()->withInput()->with('error', 'Gagal membuat kategori Occupation baru.');
                }
            }

            // 3. Masukkan ID yang valid ke dalam array data
            $validated['object_occupation_id'] = $occupationId;
            // --- SELESAI PERBAIKAN ---

            Log::info('Validated Data Processing', ['validated' => $validated]);

            $object = $this->objectService->createWithInterests($validated);
            
            if (!$object) {
                Log::error('Failed to create object - service returned null');
                return redirect()->back()->withInput()->with('error', 'Gagal menambahkan object.');
            }

            Log::info('Object created successfully', ['object_id' => $object->id]);

            return redirect()->route('objects.index')
                ->with('success', 'Object berhasil ditambahkan.');

        } catch (\Exception $e) {
            Log::error('Exception in store object', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(string $id)
    {
        $object = $this->objectService->findOrFail($id);
        $object->load('objectInterests.interest');
        return view('objects.detail', compact('object'));
    }

    public function edit(string $id)
    {
        $object = $this->objectService->findOrFail($id);
        $object->load('objectInterests.interest');
        $objectOccupations = $this->objectOccupationService->getAll();
        $interests = $this->interestService->getAll();
        return view('objects.form', compact('object', 'objectOccupations', 'interests'));
    }

    /**
     * Update the specified resource in storage.
     * BAGIAN INI JUGA PERLU DIPERBAIKI (Sama seperti store)
     */
    public function update(UpdateObjectRequest $request, string $id)
    {
        try {
            $validated = $request->validated();

            // --- MULAI PERBAIKAN UPDATE ---
            $occupationId = $request->input('object_occupation_id');

            if (empty($occupationId) && $request->filled('object_occupation_name')) {
                $newOccupation = $this->objectOccupationService->create([
                    'name' => $request->object_occupation_name
                ]);
                $occupationId = $newOccupation->id;
            }
            
            $validated['object_occupation_id'] = $occupationId;
            // --- SELESAI PERBAIKAN UPDATE ---

            $updated = $this->objectService->updateWithInterests($id, $validated);
            
            if (!$updated) {
                return redirect()->back()->withInput()->with('error', 'Gagal mengubah object.');
            }

            return redirect()->route('objects.index')
                ->with('success', 'Object berhasil diperbarui.');
                
        } catch (\Exception $e) {
             return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            // PASTI-KAN memanggil SERVICE, bukan repository
            $this->objectService->delete($id); 
            
            return redirect()->route('objects.index')->with('success', 'Berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}