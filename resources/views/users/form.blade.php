@extends('layouts.master')

@section('title'){{ isset($user) ? 'Edit Staff' : 'Tambah Staff' }}
@endsection

@push('css')
@endpush

@section('content')
	<div class="d-flex justify-content-between align-items-center">
		@component('components.breadcrumb')
			@slot('breadcrumb_title')
				<h3>{{ isset($user) ? 'Edit Staff' : 'Tambah Staff' }}</h3>
			@endslot
			<li class="breadcrumb-item"><a href="{{ route('users.index') }}">Staff</a></li>
			<li class="breadcrumb-item active">{{ isset($user) ? 'Edit' : 'Tambah' }}</li>
		@endcomponent
		<div class="d-flex gap-2">
			<a href="{{ route('users.index') }}" class="btn btn-info btn-sm">
				Kembali
			</a>
		</div>
	</div>
	
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="card">
					<div class="card-header pb-0">
						<h5>Data Staff</h5>
						<span>{{ isset($user) ? 'Update data staff pada form di bawah.' : 'Isi form di bawah untuk menambahkan staff baru ke sistem.' }}</span>
					</div>
					<form class="theme-form" action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}" method="POST">
						@csrf
						@if(isset($user))
							@method('PUT')
						@endif
						<div class="card-body">
							<div class="mb-3 row">
								<label class="col-sm-3 col-form-label" for="username">Username <span class="text-danger">*</span></label>
								<div class="col-sm-9">
									<input class="form-control @error('username') is-invalid @enderror" 
										id="username" 
										name="username" 
										type="text" 
										placeholder="Masukkan username" 
										value="{{ old('username', $user->username ?? '') }}" 
										required />
									@error('username')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
							</div>
							<div class="mb-3 row">
								<label class="col-sm-3 col-form-label" for="name">Nama Lengkap <span class="text-danger">*</span></label>
								<div class="col-sm-9">
									<input class="form-control @error('name') is-invalid @enderror" 
										id="name" 
										name="name" 
										type="text" 
										placeholder="Masukkan nama lengkap" 
										value="{{ old('name', $user->name ?? '') }}" 
										required />
									@error('name')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
							</div>
							<div class="mb-3 row">
								<label class="col-sm-3 col-form-label" for="email">Email <span class="text-danger">*</span></label>
								<div class="col-sm-9">
									<input class="form-control @error('email') is-invalid @enderror" 
										id="email" 
										name="email" 
										type="email" 
										placeholder="Masukkan alamat email" 
										value="{{ old('email', $user->email ?? '') }}" 
										required />
									@error('email')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
							</div>
							<div class="mb-3 row">
								<label class="col-sm-3 col-form-label" for="password">Password @if(!isset($user))<span class="text-danger">*</span>@endif</label>
								<div class="col-sm-9">
									<input class="form-control @error('password') is-invalid @enderror" 
										id="password" 
										name="password" 
										type="password" 
										placeholder="{{ isset($user) ? 'Kosongkan jika tidak ingin mengubah password' : 'Masukkan password' }}" 
										{{ !isset($user) ? 'required' : '' }} />
									@error('password')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
									@if(isset($user))
										<small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password</small>
									@endif
								</div>
							</div>
							<div class="mb-3 row">
								<label class="col-sm-3 col-form-label" for="password_confirmation">Konfirmasi Password @if(!isset($user))<span class="text-danger">*</span>@endif</label>
								<div class="col-sm-9">
									<input class="form-control" 
										id="password_confirmation" 
										name="password_confirmation" 
										type="password" 
										placeholder="{{ isset($user) ? 'Konfirmasi password baru' : 'Masukkan ulang password' }}" 
										{{ !isset($user) ? 'required' : '' }} />
								</div>
							</div>
							<fieldset class="mb-3">
								<div class="row">
									<label class="col-form-label col-sm-3 pt-0">Peran <span class="text-danger">*</span></label>
									<div class="col-sm-9">
										<div class="form-check radio radio-primary">
											<input class="form-check-input @error('role') is-invalid @enderror" 
												id="role_admin" 
												type="radio" 
												name="role" 
												value="admin" 
												{{ old('role', isset($user) && $user->hasRole('admin') ? 'admin' : '') == 'admin' ? 'checked' : '' }} 
												required />
											<label class="form-check-label" for="role_admin">
												<span class="badge badge-primary">Admin</span>
												<small class="text-muted d-block">Akses ke semua administrasi</small>
											</label>
										</div>
										<div class="form-check radio radio-info">
											<input class="form-check-input @error('role') is-invalid @enderror" 
												id="role_finance" 
												type="radio" 
												name="role" 
												value="finance" 
												{{ old('role', isset($user) && $user->hasRole('finance') ? 'finance' : '') == 'finance' ? 'checked' : '' }} />
											<label class="form-check-label" for="role_finance">
												<span class="badge badge-info">Finance</span>
												<small class="text-muted d-block">Akses ke fitur keuangan</small>
											</label>
										</div>
										@error('role')
											<div class="invalid-feedback d-block">{{ $message }}</div>
										@enderror
									</div>
								</div>
							</fieldset>
							<div class="mb-3 row">
								<label class="col-sm-3 col-form-label pt-0">Status</label>
								<div class="col-sm-9">
									<div class="form-check checkbox checkbox-primary">
										<input class="form-check-input" 
											id="is_active" 
											name="is_active" 
											type="checkbox" 
											value="1" 
											{{ old('is_active', $user->is_active ?? true) ? 'checked' : '' }} />
										<label class="form-check-label" for="is_active">
											Active
											<small class="text-muted d-block">Staff dapat login</small>
										</label>
									</div>
								</div>
							</div>
						
							<div class="text-end">
								<a href="{{ route('users.index') }}" class="btn btn-secondary">
									<i class="fa fa-times"></i> Cancel
								</a>
								<button class="btn btn-primary" type="submit">
									<i class="fa fa-save"></i> {{ isset($user) ? 'Update' : 'Save' }} User
								</button>
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
