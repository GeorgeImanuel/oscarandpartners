@extends('layouts.master')

@section('title'){{ isset($user) ? 'Edit User' : 'Add User' }}
@endsection

@push('css')
@endpush

@section('content')
	@component('components.breadcrumb')
		@slot('breadcrumb_title')
			<h3>{{ isset($user) ? 'Edit User' : 'Add User' }}</h3>
		@endslot
		<li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
		<li class="breadcrumb-item active">{{ isset($user) ? 'Edit' : 'Add' }}</li>
	@endcomponent
	
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="card">
					<div class="card-header pb-0">
						<h5>User Information</h5>
						<span>{{ isset($user) ? 'Update user information in the form below.' : 'Fill in the form below to add a new user to the system.' }}</span>
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
										placeholder="Enter username" 
										value="{{ old('username', $user->username ?? '') }}" 
										required />
									@error('username')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
							</div>
							<div class="mb-3 row">
								<label class="col-sm-3 col-form-label" for="name">Full Name <span class="text-danger">*</span></label>
								<div class="col-sm-9">
									<input class="form-control @error('name') is-invalid @enderror" 
										id="name" 
										name="name" 
										type="text" 
										placeholder="Enter full name" 
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
										placeholder="Enter email address" 
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
										placeholder="{{ isset($user) ? 'Leave blank to keep current password' : 'Enter password' }}" 
										{{ !isset($user) ? 'required' : '' }} />
									@error('password')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
									@if(isset($user))
										<small class="form-text text-muted">Leave blank if you don't want to change the password</small>
									@endif
								</div>
							</div>
							<div class="mb-3 row">
								<label class="col-sm-3 col-form-label" for="password_confirmation">Confirm Password @if(!isset($user))<span class="text-danger">*</span>@endif</label>
								<div class="col-sm-9">
									<input class="form-control" 
										id="password_confirmation" 
										name="password_confirmation" 
										type="password" 
										placeholder="{{ isset($user) ? 'Confirm new password' : 'Re-enter password' }}" 
										{{ !isset($user) ? 'required' : '' }} />
								</div>
							</div>
							<fieldset class="mb-3">
								<div class="row">
									<label class="col-form-label col-sm-3 pt-0">Role <span class="text-danger">*</span></label>
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
												<small class="text-muted d-block">Full access to all features</small>
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
												<small class="text-muted d-block">Access to financial features</small>
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
											<small class="text-muted d-block">User can login to the system</small>
										</label>
									</div>
								</div>
							</div>
						</div>
						<div class="card-footer">
							<button class="btn btn-primary" type="submit">
								<i class="fa fa-save"></i> {{ isset($user) ? 'Update' : 'Save' }} User
							</button>
							<a href="{{ route('users.index') }}" class="btn btn-secondary">
								<i class="fa fa-times"></i> Cancel
							</a>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

@push('scripts')
@endpush

@endsection
