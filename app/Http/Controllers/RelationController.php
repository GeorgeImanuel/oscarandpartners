<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Relation\RelationService;
use App\Http\Requests\storeRelationRequest;
use App\Http\Requests\UpdateRelationRequest;

class RelationController extends Controller
{

    private $relationService;
    /**
     * @var RelationService
     */
    public function __construct(RelationService $relationService)
    {
        $this->relationService = $relationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $relations =  $this->relationService->all();
        return view('relations.index', compact('relations'));
    }

    /**
     * Create page.
     */
    public function create(Request $request)
    {
        return view('relations.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRelationRequest $request)
    {
        $relation = $this->relationService->create($request->all());
        if(!$relation){
            return redirect()->back()->withInput()->with('error', 'Gagal menambah data relation.');
        }
        return redirect()->route('relations.index')->with('success', 'Berhasil menambah data relation.');
    }

    /**
     * Edit Page
     */
    public function edit(string $id)
    {
        $relation = $this->relationService->findOrFail($id);
        return view('relations.form', compact('relation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRelationRequest $request, string $id)
    {
        $relation = $this->relationService->update($id, $request->all());
        if(!$relation){
            return redirect()->back()->withInput()->with('error', 'Gagal mengubah data relation.');
        }
        return redirect()->route('relations.index')->with('success', 'Berhasil mengubah data relation.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->relationService->delete($id);
            return redirect()->route('relations.index')->with('success', 'Berhasil menghapus data relation.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
