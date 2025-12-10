<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Relation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = Client::with(['relation', 'user'])->get();
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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'id_card_number' => 'nullable|string|max:255',
            'relation_id' => 'nullable|exists:relations,id',
        ]);

        // Create user first
        $user = User::create([
            'username' => $validated['username'],
            'name' => $validated['name'],
            'email' => $validated['email'] ?? $validated['username'] . '@client.local',
            'password' => Hash::make($validated['password']),
            'is_active' => true,
        ]);

        // Assign client role
        $user->assignRole('client');

        // Create client
        Client::create([
            'user_id' => $user->id,
            'relation_id' => $validated['relation_id'],
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'address' => $validated['address'],
            'id_card_number' => $validated['id_card_number'],
        ]);

        return redirect()->route('clients.index')->with('success', 'Client created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        return view('clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        $relations = Relation::all();
        return view('clients.form', compact('client', 'relations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($client->user_id)],
            'password' => 'nullable|string|min:8|confirmed',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'id_card_number' => 'nullable|string|max:255',
            'relation_id' => 'nullable|exists:relations,id',
        ]);

        // Update user
        $user = $client->user;
        $user->username = $validated['username'];
        $user->name = $validated['name'];
        $user->email = $validated['email'] ?? $validated['username'] . '@client.local';
        
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        
        $user->save();

        // Update client
        $client->update([
            'relation_id' => $validated['relation_id'],
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'address' => $validated['address'],
            'id_card_number' => $validated['id_card_number'],
        ]);

        return redirect()->route('clients.index')->with('success', 'Client updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        $client->user->delete(); // This will cascade delete the client
        return redirect()->route('clients.index')->with('success', 'Client deleted successfully.');
    }
}
