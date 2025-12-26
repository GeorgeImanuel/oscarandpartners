@extends('layouts.master')

@section('title', 'Relasi')

@push('breadcrumb')
    <li class="breadcrumb-item">Relasi</li>
    <li class="breadcrumb-item active">{{ isset($relation) ? 'Ubah' : 'Tambah'  }} Relasi</li>
@endpush

@section('content')
    @yield('breadcrumb-list')
    <!-- Container-fluid starts-->
    <div class="container-fluid dashboard-default-sec">
        <form class="needs-validation" novalidate="" method="POST" action="{{ isset($relation) ? route('relations.update', $relation->id) : route('relations.store') }}">
            <div class="card">
                <div class="card-header">
                    <h5 style="display: inline">Tambah Relasi</h5>
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

                </div>
                <div class="card-footer">
                    <a href="{{ route('relations.index') }}" class="btn btn-light">Kembali</a>
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