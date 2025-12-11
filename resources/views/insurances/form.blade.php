@extends('layouts.master')

@section('title', 'Insurances')

@push('breadcrumb')
    <li class="breadcrumb-item">Insurances</li>
    <li class="breadcrumb-item active">{{ isset($insurances) ? 'Ubah' : 'Tambah'  }} Insurances</li>
@endpush

@section('content')
    @yield('breadcrumb-list')
    <!-- Container-fluid starts-->
    <div class="container-fluid dashboard-default-sec">
        <form class="needs-validation" novalidate="" method="POST" action="{{ isset($insurances) ? route('insurances.update', $insurances->id) : route('insurances.store') }}">
            <div class="card">
                <div class="card-header">
                    <h5 style="display: inline">Tambah Insurances</h5>
                </div>
                <div class="card-body">
                    @csrf
                    @if(isset($insurances))
                        @method('PUT')
                    @endif

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="inputName">Nama Bank</label>
                            <input class="form-control @error('name') is-invalid @enderror" id="inputName" name="name" type="text" value="{{ isset($insurances) ? $insurances->name : old('name', '') }}" required="" />
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div><br>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="inputbank_account">Akun Bank</label>
                            <input class="form-control @error('bank_account') is-invalid @enderror" id="inputbank_account" name="bank_account" type="text" value="{{ isset($insurances) ? $insurances->bank_account : old('bank_account', '') }}" required="" />
                            @error('bank_account')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div><br>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="inputva_number">No VA</label>
                            <input class="form-control @error('va_number') is-invalid @enderror" id="inputva_number" name="va_number" type="text" value="{{ isset($insurances) ? $insurances->va_number : old('va_number', '') }}" required="" />
                            @error('va_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div><br>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="inputdefault_commission_pct">Default Commission Percentage<br> (Ex. 3.6/2.06)</label>
                            <input class="form-control @error('default_commission_pct') is-invalid @enderror" id="inputdefault_commission_pct" name="default_commission_pct" type="text" value="{{ isset($insurances) ? $insurances->default_commission_pct : old('default_commission_pct', '') }}" required="" />
                            @error('default_commission_pct')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div><br>
                </div>
                <div class="card-footer">
                    <a href="{{ route('insurances.index') }}" class="btn btn-light">Kembali</a>
                    <button class="btn btn-primary float-end" type="submit">Simpan</button>
                </div>
            </div>
        </form>
    </div>
    <!-- Container-fluid Ends-->
@endsection
@push('scripts')
    <script src="{{ asset('assets/js/form-validation.js') }}"></script>
    <script>
        initializeFormValidation({
            formSelector: '.needs-validation'
        });
    </script>
@endpush