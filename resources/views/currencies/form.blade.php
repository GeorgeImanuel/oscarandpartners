@extends('layouts.master')

@section('title', 'Mata Uang')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.css') }}">
@endpush

@section('content')
    @component('components.breadcrumb')
    @slot('breadcrumb_title')
    <h3>{{ isset($currency) ? 'Ubah' : 'Tambah' }} Mata Uang</h3>
    @endslot
    <li class="breadcrumb-item"><a href="{{ route('policies.index') }}">Polis</a></li>
    <li class="breadcrumb-item"><a href="{{ route('currencies.index') }}">Mata Uang</a></li>
    <li class="breadcrumb-item active">{{ isset($currency) ? 'Ubah' : 'Tambah' }}</li>
    @endcomponent

    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <form class="needs-validation" novalidate="" method="POST" action="{{ isset($currency) ? route('currencies.update', $currency->code) : route('currencies.store') }}">
            @csrf
            @if(isset($currency))
                @method('PUT')
            @endif

            <div class="card">
                <div class="card-header">
                    <h5>{{ isset($currency) ? 'Ubah' : 'Tambah' }} Mata Uang</h5>
                </div>
                <div class="card-body row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label" for="code">Kode Mata Uang <span class="text-danger">*</span></label>
                        <input class="form-control @error('code') is-invalid @enderror" 
                               id="code" 
                               name="code" 
                               type="text" 
                               maxlength="3"
                               value="{{ isset($currency) ? $currency->code : old('code', '') }}" 
                               placeholder="Contoh: USD" 
                               required 
                               {{ isset($currency) ? 'readonly' : '' }} />
                        <small class="text-muted">3 karakter (contoh: USD, IDR, EUR)</small>
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label" for="name">Nama Mata Uang <span class="text-danger">*</span></label>
                        <input class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               type="text" 
                               value="{{ isset($currency) ? $currency->name : old('name', '') }}" 
                               placeholder="Contoh: US Dollar" 
                               required />
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary" type="submit">{{ isset($currency) ? 'Simpan' : 'Tambah' }}</button>
                    <a href="{{ route('currencies.index') }}" class="btn btn-light">Batal</a>
                </div>
            </div>
        </form>
    </div>
    <!-- Container-fluid Ends-->
@endsection

@push('scripts')
<script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
<script>
    (function() {
        'use strict'
        window.addEventListener('load', function() {
            var forms = document.getElementsByClassName('needs-validation')
            Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        }, false)
    })()
</script>
@endpush
