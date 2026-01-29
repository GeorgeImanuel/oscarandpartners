@extends('layouts.master')

@section('title'){{ isset($client) ? 'Edit Client' : 'Menambah Client' }}
@endsection

@push('css')
@endpush

@section('content')
	<div class="d-flex justify-content-between align-items-center">
		@component('components.breadcrumb')
			@slot('breadcrumb_title')
				<h3>{{ isset($client) ? 'Edit Client' : 'Menambah Client' }}</h3>
			@endslot
			<li class="breadcrumb-item"><a href="{{ route('clients.index') }}">Clients</a></li>
			<li class="breadcrumb-item active">{{ isset($client) ? 'Edit' : 'Create' }}</li>
		@endcomponent
		<div class="d-flex gap-2">
			<a href="{{ route('clients.index') }}" class="btn btn-info btn-sm">
				<i class="fa fa-arrow-left"></i> Kembali
			</a>
		</div>
	</div>
	
	<div class="container-fluid">
	    <div class="row">
	        <div class="col-sm-12">
	            <div class="card">
	                <div class="card-header pb-0">
	                    <h5>{{ isset($client) ? 'Edit Informasi Client' : 'Menambah Client Baru' }}</h5>
	                </div>
	                <form class="form theme-form" action="{{ isset($client) ? route('clients.update', $client) : route('clients.store') }}" method="POST">
	                    @csrf
	                    @if(isset($client))
	                        @method('PUT')
	                    @endif
	                    
	                    <div class="card-body">
	                        <div class="row mb-3">
	                            <label class="col-sm-3 col-form-label">Nama Lengkap <span class="text-danger">*</span></label>
	                            <div class="col-sm-9">
	                                <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" value="{{ old('name', $client->id_name ?? '') }}" required>
	                                @error('name')
	                                    <div class="invalid-feedback">{{ $message }}</div>
	                                @enderror
	                            </div>
	                        </div>

	                        <!-- Relation -->
	                        <div class="row mb-3">
	                            <label class="col-sm-3 col-form-label">Relasi/Grup</label>
	                            <div class="col-sm-9">
	                                <select class="form-control @error('relation_id') is-invalid @enderror" name="relation_id">
	                                    <option value="">-- Pilih Grup --</option>
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
	                            <label class="col-sm-3 col-form-label">Alamat</label>
	                            <div class="col-sm-9">
	                                <textarea class="form-control @error('address') is-invalid @enderror" name="address" rows="3">{{ old('address', $client->id_address ?? '') }}</textarea>
	                                @error('address')
	                                    <div class="invalid-feedback">{{ $message }}</div>
	                                @enderror
	                            </div>
	                        </div>
	                    
							<div class="text-end">
								<a href="{{ route('clients.index') }}" class="btn btn-secondary">Batal</a>
								<button class="btn btn-primary" type="submit">{{ isset($client) ? 'Update' : 'Tambah' }} Client</button>
							</div>
						</div>
	                </form>
	            </div>
	        </div>
	    </div>
	</div>

	@push('scripts')
	@endpush
@endsection
