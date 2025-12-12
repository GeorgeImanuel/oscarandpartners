<?php

namespace App\Http\Controllers;

use App\Services\User\UserService;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = $this->userService->getAllAdminFinance();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->createUser($request->all());
        
        if (!$user) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambah user.');
        }
        
        return redirect()->route('users.index')->with('success', 'Berhasil menambah user.');
    }

    /**
     * Display the specified resource.
     */
    public function show(\App\Models\User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(\App\Models\User $user)
    {
        return view('users.form', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, \App\Models\User $user)
    {
        $updated = $this->userService->updateUser($user->id, $request->all());
        
        if (!$updated) {
            return redirect()->back()->withInput()->with('error', 'Gagal mengubah user.');
        }
        
        return redirect()->route('users.index')->with('success', 'Berhasil mengubah user.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(\App\Models\User $user)
    {
        $this->userService->deleteUser($user->id);
        return redirect()->route('users.index')->with('success', 'Berhasil menghapus user.');
    }
}
