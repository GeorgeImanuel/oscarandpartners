<?php

namespace App\Http\Controllers;

use App\Models\Relation;
use App\Services\Client\ClientService;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;

class ClientController extends Controller
{
    private $clientService;

    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = $this->clientService->getAllWithRelations();
        return view('clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $relations = Relation::all();
        return view('clients.form', compact('relations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request)
    {
        $client = $this->clientService->createClient($request->all());
        
        if (!$client) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambah client.');
        }
        
        return redirect()->route('clients.index')->with('success', 'Berhasil menambah client.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $client = $this->clientService->findOrFail($id);
        return view('clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(\App\Models\Client $client)
    {
        $relations = Relation::all();
        return view('clients.form', compact('client', 'relations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClientRequest $request, \App\Models\Client $client)
    {
        $updated = $this->clientService->updateClient($client->id, $request->all());
        
        if (!$updated) {
            return redirect()->back()->withInput()->with('error', 'Gagal mengubah client.');
        }
        
        return redirect()->route('clients.index')->with('success', 'Berhasil mengubah client.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(\App\Models\Client $client)
    {
        try {
            $this->clientService->deleteClient($client->id);
            return redirect()->route('clients.index')->with('success', 'Berhasil menghapus client.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
