@extends('layouts.master')

@section('title', 'Coverages')

@push('breadcrumb')
    <li class="breadcrumb-item">Coverage</li>
    <li class="breadcrumb-item active">{{ isset($coverage) ? 'Edit' : 'Tambah'  }} Coverage</li>
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center">
        @component('components.breadcrumb')
            @slot('breadcrumb_title')
                <h3>{{ isset($coverage) ? 'Edit' : 'Tambah'  }} Coverage</h3>
            @endslot
            <li class="breadcrumb-item"><a href="{{ route('coverages.index') }}">Coverage</a></li>
            <li class="breadcrumb-item active">{{ isset($coverage) ? 'Edit' : 'Tambah'  }} Coverage</li>
        @endcomponent 
        <a href="{{ route('coverages.index') }}" class="btn btn-info btn-sm">
            <i class="fa fa-arrow-left"></i> Kembali
        </a>
    </div>   

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
                <div class="card-footer text-end gap-2">
                    <a href="{{ route('coverages.index') }}" class="btn btn-secondary">Batal</a>
                    <button class="btn btn-primary" type="submit">Simpan</button>
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