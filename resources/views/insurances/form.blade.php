@extends('layouts.master')

@section('title', 'Insurance')

@push('breadcrumb')
    <li class="breadcrumb-item">Asuransi</li>
    <li class="breadcrumb-item active">{{ isset($insurance) ? 'Edit' : 'Tambah'  }} Asuransi</li>
@endpush

@section('content')
    @yield('breadcrumb-list')
    <!-- Container-fluid starts-->
    <div class="container-fluid dashboard-default-sec">
        <form class="needs-validation" novalidate="" method="POST" action="{{ isset($insurance) ? route('insurances.update', $insurance->id) : route('insurances.store') }}">
            <div class="card">
                <div class="card-header">
                    <h5 style="display: inline">{{ isset($insurance) ? 'Edit' : 'Tambah' }} Asuransi</h5>
                </div>
                <div class="card-body">
                    @csrf
                    @if(isset($insurance))
                        @method('PUT')
                    @endif

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="inputName">Nama Asuransi</label>
                            <input class="form-control @error('name') is-invalid @enderror" id="inputName" name="name" type="text" value="{{ isset($insurance) ? $insurance->name : old('name', '') }}" required="" />
                            @error('name')
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
            formSelector: '.needs-validation',
            showConfirmDialog: true
        });
    </script>
@endpush