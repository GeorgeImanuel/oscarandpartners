@extends('layouts.master')

@section('title', 'Relasi')

@push('breadcrumb')
    <li class="breadcrumb-item">Relasi</li>
    <li class="breadcrumb-item active">{{ isset($relation) ? 'Ubah' : 'Tambah'  }} Relasi</li>
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center">
        @component('components.breadcrumb')
            @slot('breadcrumb_title')
                <h3>{{ isset($relation) ? 'Ubah' : 'Tambah'  }} Relasi</h3>
            @endslot
            <li class="breadcrumb-item"><a href="{{ route('relations.index') }}">Relasi</a></li>
            <li class="breadcrumb-item active">{{ isset($relation) ? 'Ubah' : 'Tambah'  }} Relasi</li>
        @endcomponent
        <a href="{{ route('relations.index') }}" class="btn btn-info btn-sm">
            <i class="fa fa-arrow-left"></i> Kembali
        </a>
    </div> 

    <!-- Container-fluid starts-->
    <div class="container-fluid dashboard-default-sec">
        <form class="needs-validation" novalidate="" method="POST" action="{{ isset($relation) ? route('relations.update', $relation->id) : route('relations.store') }}">
            <div class="card">
                <div class="card-header">
                    <h5 style="display: inline">{{ isset($relation) ? 'Ubah' : 'Tambah'  }} Relasi</h5>
                </div>
                <div class="card-body">
                    @csrf
                    @if(isset($relation))
                        @method('PUT')
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label" for="inputCode">Kode Relasi</label>
                            <input class="form-control @error('code') is-invalid @enderror" id="inputCode" name="code" type="text" value="{{ isset($relation) ? $relation->code : old('code', '') }}" required="" />
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>`
                        <div class="col-md-6">
                            <label class="form-label" for="inputName">Nama Relasi</label>
                            <input class="form-control @error('name') is-invalid @enderror" id="inputName" name="name" type="text" value="{{ isset($relation) ? $relation->name : old('name', '') }}" required="" />
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="text-end">
                        <a href="{{ route('relations.index') }}" class="btn btn-secondary">Batal</a>
                        <button class="btn btn-primary" type="submit">Simpan</button>
                    </div>
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
        
        // Add confirmation on submit
        document.querySelector('.needs-validation').addEventListener('submit', function(e) {
            if (this.checkValidity()) {
                if (!confirm('Apakah Anda yakin ingin menyimpan data ini?')) {
                    e.preventDefault();
                    e.stopPropagation();
                }
            }
        });
    </script>
@endpush