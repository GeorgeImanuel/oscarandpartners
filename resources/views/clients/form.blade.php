@extends('layouts.master')

@section('title'){{ isset($client) ? 'Edit Client' : 'Add Client' }}
@endsection

@push('css')
@endpush

@section('content')
	@component('components.breadcrumb')
		@slot('breadcrumb_title')
			<h3>{{ isset($client) ? 'Edit Client' : 'Add Client' }}</h3>
		@endslot
		<li class="breadcrumb-item">Clients</li>
		<li class="breadcrumb-item active">{{ isset($client) ? 'Edit' : 'Create' }}</li>
	@endcomponent
	
	<div class="container-fluid">
	    <div class="row">
	        <div class="col-sm-12">
	            <div class="card">
	                <div class="card-header pb-0">
	                    <h5>{{ isset($client) ? 'Edit Client Information' : 'Add New Client' }}</h5>
	                </div>
	                <form class="form theme-form" action="{{ isset($client) ? route('clients.update', $client) : route('clients.store') }}" method="POST">
	                    @csrf
	                    @if(isset($client))
	                        @method('PUT')
	                    @endif
	                    
	                    <div class="card-body">
	                        <!-- Username -->
	                        <div class="row mb-3">
	                            <label class="col-sm-3 col-form-label">Username <span class="text-danger">*</span></label>
	                            <div class="col-sm-9">
	                                <input class="form-control @error('username') is-invalid @enderror" type="text" name="username" value="{{ old('username', $client->user->username ?? '') }}" required>
	                                @error('username')
	                                    <div class="invalid-feedback">{{ $message }}</div>
	                                @enderror
	                            </div>
	                        </div>

	                        <!-- Password -->
	                        <div class="row mb-3">
	                            <label class="col-sm-3 col-form-label">Password <span class="text-danger">{{ isset($client) ? '' : '*' }}</span></label>
	                            <div class="col-sm-9">
	                                <input class="form-control @error('password') is-invalid @enderror" type="password" name="password" {{ isset($client) ? '' : 'required' }}>
	                                @if(isset($client))
	                                    <small class="form-text text-muted">Leave blank to keep current password</small>
	                                @endif
	                                @error('password')
	                                    <div class="invalid-feedback">{{ $message }}</div>
	                                @enderror
	                            </div>
	                        </div>

	                        <!-- Password Confirmation -->
	                        <div class="row mb-3">
	                            <label class="col-sm-3 col-form-label">Confirm Password</label>
	                            <div class="col-sm-9">
	                                <input class="form-control" type="password" name="password_confirmation">
	                            </div>
	                        </div>

	                        <hr class="mt-4 mb-4">

	                        <!-- Full Name -->
	                        <div class="row mb-3">
	                            <label class="col-sm-3 col-form-label">Full Name <span class="text-danger">*</span></label>
	                            <div class="col-sm-9">
	                                <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" value="{{ old('name', $client->id_name ?? '') }}" required>
	                                @error('name')
	                                    <div class="invalid-feedback">{{ $message }}</div>
	                                @enderror
	                            </div>
	                        </div>

	                        <!-- ID Card Number -->
	                        <div class="row mb-3">
	                            <label class="col-sm-3 col-form-label">ID Card Number</label>
	                            <div class="col-sm-9">
	                                <input class="form-control @error('id_card_number') is-invalid @enderror" type="text" name="id_card_number" value="{{ old('id_card_number', $client->id_number ?? '') }}">
	                                @error('id_card_number')
	                                    <div class="invalid-feedback">{{ $message }}</div>
	                                @enderror
	                            </div>
	                        </div>

	                        <!-- Relation -->
	                        <div class="row mb-3">
	                            <label class="col-sm-3 col-form-label">Group/Family</label>
	                            <div class="col-sm-9">
	                                <select class="form-control @error('relation_id') is-invalid @enderror" name="relation_id">
	                                    <option value="">-- Select Group --</option>
	                                    @foreach($relations as $relation)
	                                        <option value="{{ $relation->id }}" {{ old('relation_id', $client->relation_id ?? '') == $relation->id ? 'selected' : '' }}>
	                                            {{ $relation->name }}
	                                        </option>
	                                    @endforeach
	                                </select>
	                                @error('relation_id')
	                                    <div class="invalid-feedback">{{ $message }}</div>
	                                @enderror
	                            </div>
	                        </div>

	                        <!-- Address -->
	                        <div class="row mb-3">
	                            <label class="col-sm-3 col-form-label">Address</label>
	                            <div class="col-sm-9">
	                                <textarea class="form-control @error('address') is-invalid @enderror" name="address" rows="3">{{ old('address', $client->id_address ?? '') }}</textarea>
	                                @error('address')
	                                    <div class="invalid-feedback">{{ $message }}</div>
	                                @enderror
	                            </div>
	                        </div>
	                    </div>
	                    
	                    <div class="card-footer text-end">
	                        <a href="{{ route('clients.index') }}" class="btn btn-secondary">Cancel</a>
	                        <button class="btn btn-primary" type="submit">{{ isset($client) ? 'Update' : 'Create' }} Client</button>
	                    </div>
	                </form>
	            </div>
	        </div>
	    </div>
	</div>

	@push('scripts')
	@endpush
@endsection
