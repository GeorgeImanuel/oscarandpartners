@extends('layouts.master')

@section('title', 'Coverages')

@push('breadcrumb')
    <li class="breadcrumb-item">Coverage</li>
    <li class="breadcrumb-item active">{{ isset($coverage) ? 'Edit' : 'Tambah'  }} Coverage</li>
@endpush

@section('content')
    @yield('breadcrumb-list')
    <!-- Container-fluid starts-->
    <div class="container-fluid dashboard-default-sec">
        <form class="needs-validation" novalidate="" method="POST" action="{{ isset($coverage) ? route('coverages.update', $coverage->code) : route('coverages.store') }}">
            <div class="card">
                <div class="card-header">
                    <h5 style="display: inline">{{ isset($coverage) ? 'Edit' : 'Tambah' }} Coverage</h5>
                </div>
                <div class="card-body">
                    @csrf
                    @if(isset($coverage))
                        @method('PUT')
                    @endif
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="inputName">Kode</label>
                            <input class="form-control @error('code') is-invalid @enderror" id="inputName" name="code" type="text" value="{{ isset($coverage) ? $coverage->code : old('code', '') }}" required="" />
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div><br>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="inputName">Coverage</label>
                            <input class="form-control @error('name') is-invalid @enderror" id="inputName" name="name" type="text" value="{{ isset($coverage) ? $coverage->name : old('name', '') }}" required="" />
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div><br>
                </div>
                <div class="card-footer">
                    <a href="{{ route('coverages.index') }}" class="btn btn-light">Kembali</a>
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