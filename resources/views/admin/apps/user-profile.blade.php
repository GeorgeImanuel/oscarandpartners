@extends('layouts.master')

@section('title', 'User Profile')

@section('content')
@php
    // Dummy data untuk demo
    $user = (object) [
        'full_name' => 'John Doe',
        'username' => 'admin',
        'role' => 'ADMIN',
        'is_active' => true,
        'last_login_at' => now(),
        'created_at' => now()->subMonths(6),
    ];
    
    $client = null; // Set null atau buat dummy client jika role CLIENT
@endphp

<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-6">
                <h3>User Profile</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item active">User Profile</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Profile Card -->
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header pb-0">
                    <h5>Profile</h5>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <div class="avatar-showcase">
                            <div class="avatars">
                                <div class="avatar avatar-xl">
                                    <img class="img-100 rounded-circle" src="{{ asset('assets/images/user/7.jpg') }}" alt="Profile Photo">
                                </div>
                            </div>
                        </div>
                        <h5 class="mt-3 mb-1">{{ $user->full_name }}</h5>
                        <p class="text-muted">
                            <span class="badge badge-{{ $user->role === 'OWNER' ? 'danger' : ($user->role === 'ADMIN' ? 'primary' : ($user->role === 'FINANCE' ? 'success' : 'info')) }}">
                                {{ $user->role }}
                            </span>
                        </p>
                    </div>
                    <hr>
                    <div class="user-info">
                        <div class="row mb-2">
                            <div class="col-sm-5 col-5">
                                <h6 class="f-w-600">Username</h6>
                            </div>
                            <div class="col-sm-7 col-7">
                                <span>{{ $user->username }}</span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-5 col-5">
                                <h6 class="f-w-600">Status</h6>
                            </div>
                            <div class="col-sm-7 col-7">
                                @if($user->is_active)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-danger">Blocked</span>
                                @endif
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-5 col-5">
                                <h6 class="f-w-600">Last Login</h6>
                            </div>
                            <div class="col-sm-7 col-7">
                                <span>{{ $user->last_login_at ? $user->last_login_at->format('d M Y H:i') : 'Never' }}</span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-5 col-5">
                                <h6 class="f-w-600">Member Since</h6>
                            </div>
                            <div class="col-sm-7 col-7">
                                <span>{{ $user->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Information -->
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header pb-0">
                    <h5>Account Information</h5>
                    <span>Update your account details</span>
                </div>
                <div class="card-body">
                    <form action="#" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('full_name') is-invalid @enderror" 
                                    name="full_name" value="{{ old('full_name', $user->full_name) }}" required>
                                @error('full_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Username <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('username') is-invalid @enderror" 
                                    name="username" value="{{ old('username', $user->username) }}" required>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Role</label>
                                <input type="text" class="form-control" value="{{ $user->role }}" disabled>
                                <small class="form-text text-muted">Role cannot be changed from profile</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <input type="text" class="form-control" 
                                    value="{{ $user->is_active ? 'Active' : 'Blocked' }}" disabled>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h6 class="mb-3">Change Password</h6>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Current Password</label>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                    name="current_password">
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">New Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                    name="password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" name="password_confirmation">
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if($user->role === 'CLIENT' && isset($client))
    <!-- Client Additional Info -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h5>Client Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="f-w-600">Name (As per KTP/Policy)</h6>
                            <p>{{ $client->name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="f-w-600">Phone</h6>
                            <p>{{ $client->phone }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="f-w-600">Email</h6>
                            <p>{{ $client->email ?? '-' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="f-w-600">ID Card Number</h6>
                            <p>{{ $client->id_card_number ?? '-' }}</p>
                        </div>
                        <div class="col-md-12 mb-3">
                            <h6 class="f-w-600">Address</h6>
                            <p>{{ $client->address ?? '-' }}</p>
                        </div>
                        @if($client->relation)
                        <div class="col-md-12 mb-3">
                            <h6 class="f-w-600">Family/Group</h6>
                            <p><span class="badge badge-light-primary">{{ $client->relation->code }}</span> {{ $client->relation->name }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
