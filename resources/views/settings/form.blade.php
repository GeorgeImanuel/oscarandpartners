@extends('layouts.master')

@section('title', 'Setting')

@push('breadcrumb')
    <li class="breadcrumb-item">Setting</li>
@endpush

@section('content')
    @yield('breadcrumb-list')
    <div class="container-fluid dashboard-default-sec">
        {{-- Logika Action: Jika $setting ada, pakai route update. Jika tidak, pakai store --}}
        <form class="needs-validation" novalidate="" method="POST" 
              action="{{ isset($setting) && $setting->id ? route('settings.update', $setting->id) : route('settings.store') }}">
            
            <div class="card">
                <div class="card-header">
                    <h5 style="display: inline">Setting</h5>
                </div>
                <div class="card-body">
                    @csrf
                    {{-- Method PUT wajib ada untuk proses Update di Laravel --}}
                    @if(isset($setting) && $setting->id)
                        @method('PUT')
                    @endif

                    <div class="row">
                        {{-- Commission Percentage --}}
                        <div class="col-md-6">
                            <label class="form-label" for="inputcommision_percentage">Commission Percentage (%)</label>
                            <input class="form-control @error('commision_percentage') is-invalid @enderror" 
                                   id="inputcommision_percentage" 
                                   name="commision_percentage" 
                                   type="number" 
                                   step="0.01"
                                   value="{{ old('commision_percentage', $setting->commision_percentage ?? '') }}" 
                                   required="" />
                            @error('commision_percentage')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tax Percentage --}}
                        <div class="col-md-6">
                            <label class="form-label" for="inputTaxPercentage">Tax Percentage (%)</label>
                            <input class="form-control @error('tax_percentage') is-invalid @enderror" 
                                   id="inputTaxPercentage" 
                                   name="tax_percentage" 
                                   type="number" 
                                   step="0.01"
                                   value="{{ old('tax_percentage', $setting->tax_percentage ?? '') }}" 
                                   required="" />
                            @error('tax_percentage')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary float-end" type="submit">
                        <i class="fa fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/form-validation.js') }}"></script>
    <script>
        initializeFormValidation({
            formSelector: '.needs-validation'
        });
    </script>
@endpush