@extends('layouts.master')

@section('title', 'My Profile')

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <h3>My Profile</h3>
        @endslot
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">My Profile</li>
    @endcomponent
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>User Profile</h5>
                        <span>Update user information in the form below.</span>
                    </div>
                    
                    {{-- Form untuk update profile user --}}
                    <form class="theme-form" action="{{ route('profile.update') }}" method="POST" novalidate>
                        @csrf
                        @method('PUT')

                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Validation Error!</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Success!</strong> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="card-body">
                            {{-- Username --}}
                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label" for="username">Username</label>
                                <div class="col-sm-9">
                                    <input class="form-control" 
                                        id="username" 
                                        name="username" 
                                        type="text" 
                                        value="{{ $user->username }}" 
                                        disabled />
                                    <small class="form-text text-muted">Username cannot be changed</small>
                                </div>
                            </div>

                            {{-- Full Name --}}
                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label" for="name">Full Name <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input class="form-control @error('name') is-invalid @enderror" 
                                        id="name" 
                                        name="name" 
                                        type="text" 
                                        placeholder="Enter your full name" 
                                        value="{{ old('name', $user->name) }}" 
                                        required />
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Email --}}
                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label" for="email">Email Address <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input class="form-control @error('email') is-invalid @enderror" 
                                        id="email" 
                                        name="email" 
                                        type="email" 
                                        placeholder="Enter your email address" 
                                        value="{{ old('email', $user->email) }}" 
                                        required />
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <hr class="my-4" />

                            {{-- Change Password Section --}}
                            <h6 class="mb-3">Change Password (Optional)</h6>
                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label" for="password">New Password</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <input class="form-control @error('password') is-invalid @enderror toggle-password" 
                                            id="password" 
                                            name="password" 
                                            type="password" 
                                            placeholder="Enter new password" />
                                        
                                        <span class="input-group-text" style="cursor: pointer;" onclick="togglePassword('password', 'iconShow', 'iconHide')">
                                            <i data-feather="eye" id="iconShow"></i>
                                            <i data-feather="eye-off" id="iconHide" style="display: none;"></i>
                                        </span>
                                    </div>
                                    <small class="form-text text-muted d-block mt-1">Leave blank to keep your current password</small>
                                    @error('password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Confirm Password --}}
                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label" for="password_confirmation">Confirm New Password</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <input class="form-control @error('password_confirmation') is-invalid @enderror toggle-password" 
                                            id="password_confirmation" 
                                            name="password_confirmation" 
                                            type="password" 
                                            placeholder="Re-enter your new password" />
                                        <span class="input-group-text" style="cursor: pointer;" onclick="togglePassword('password_confirmation', 'iconShowConfirm', 'iconHideConfirm')">
                                            <i data-feather="eye" id="iconShowConfirm"></i>
                                            <i data-feather="eye-off" id="iconHideConfirm" style="display: none;"></i>
                                        </span>
                                    </div>
                                    @error('password_confirmation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-end">
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-left"></i> Back to Dashboard
                            </a>
                            <button class="btn btn-primary" type="submit">
                                <i class="fa fa-save"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Toggle password visibility
            $('.toggle-password-btn').on('click', function(e) {
                e.preventDefault();
                const $input = $(this).parent().find('input');
                const $icon = $(this).find('i');
                
                if ($input.attr('type') === 'password') {
                    $input.attr('type', 'text');
                    $icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    $input.attr('type', 'password');
                    $icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
        });

        function togglePassword(inputId, iconShowId, iconHideId) {
            var passwordInput = document.getElementById(inputId);
            var iconShow = document.getElementById(iconShowId);
            var iconHide = document.getElementById(iconHideId);

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                iconShow.style.display = "none";
                iconHide.style.display = "inline";
            } else {
                passwordInput.type = "password";
                iconShow.style.display = "inline";
                iconHide.style.display = "none";
            }
        }
    </script>
@endpush