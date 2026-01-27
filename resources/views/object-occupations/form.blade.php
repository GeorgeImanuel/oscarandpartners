@extends('layouts.master')

@section('title', 'Objek Occupation')

@section('content')
    <div class="d-flex justify-content-between align-items-center">
        @component('components.breadcrumb')
            @slot('breadcrumb_title')
                <h3>{{ isset($objectOccupation) ? 'Edit' : 'Tambah'  }} Objek Occupation</h3>
            @endslot
            <li class="breadcrumb-item"><a href="{{ route('object-occupations.index') }}">Objek Occupation</a></li>
            <li class="breadcrumb-item active">{{ isset($objectOccupation) ? 'Edit' : 'Tambah'  }}</li>
        @endcomponent
        <div class="d-flex gap-2">
            <a href="{{ route('object-occupations.index') }}" class="btn btn-secondary btn-sm">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Container-fluid starts-->
    <div class="container-fluid">
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error Validasi!</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form class="needs-validation" novalidate="" method="POST" action="{{ isset($objectOccupation) ? route('object-occupations.update', $objectOccupation->id) : route('object-occupations.store') }}">
            @csrf
            @if(isset($objectOccupation))
                @method('PUT')
            @endif

            <div class="card">
                <div class="card-header">
                    <h5>{{ isset($objectOccupation) ? 'Edit' : 'Tambah'  }} Objek Occupation</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="name">Nama Objek Occupation <span class="text-danger">*</span></label>
                            <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" type="text" value="{{ isset($objectOccupation) ? $objectOccupation->name : old('name', '') }}" placeholder="Contoh: Kendaraan, Bangunan, Elektronik" required />
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="text-end">   
                        <a href="{{ route('object-occupations.index') }}" class="btn btn-secondary">
                            <i class="fa fa-times"></i> Batal
                        </a>
                        <button class="btn btn-primary" type="submit">
                            <i class="fa fa-save"></i> Simpan
                        </button>
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
        $(document).ready(function() {
            // Initialize form validation
            initializeFormValidation({
                formSelector: '.needs-validation'
            });
        });
    </script>
@endpush
