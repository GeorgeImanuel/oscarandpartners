@extends('layouts.master')

@section('title', 'Polis')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.css') }}">
@endpush

@section('content')
    @component('components.breadcrumb')
    @slot('breadcrumb_title')
    <h3>{{ isset($policy) ? 'Ubah' : 'Tambah' }} Polis</h3>
    @endslot
    <li class="breadcrumb-item"><a href="{{ route('policies.index') }}">Polis</a></li>
    <li class="breadcrumb-item active">{{ isset($policy) ? 'Ubah' : 'Tambah' }}</li>
    @endcomponent

    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <form class="needs-validation" novalidate="" method="POST" action="{{ isset($policy) ? route('policies.update', $policy->id) : route('policies.store') }}">
            @csrf
            @if(isset($policy))
                @method('PUT')
            @endif

            <div class="card">
                <div class="card-header">
                    <h5>{{ isset($policy) ? 'Ubah' : 'Tambah' }} Polis</h5>
                </div>
                <div class="card-body row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="policy_number">Nomor Polis <span class="text-danger">*</span></label>
                        <input class="form-control @error('policy_number') is-invalid @enderror" id="policy_number" name="policy_number" type="text" value="{{ isset($policy) ? $policy->policy_number : old('policy_number', '') }}" placeholder="Contoh: POL-2024-001" required />
                        @error('policy_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="object_id">Objek <span class="text-danger">*</span></label>
                        <select class="form-select @error('object_id') is-invalid @enderror" name="object_id" id="object_id" required style="width: 100%;">
                            <option value="">- Pilih Objek -</option>
                            @foreach ($objects ?? [] as $object)
                                <option value="{{ $object->id }}" {{ isset($policy) && $policy->object_id == $object->id ? 'selected' : (old('object_id') == $object->id ? 'selected' : '') }}>
                                    {{ $object->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('object_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="coverage_code">Coverage <span class="text-danger">*</span></label>
                        <select class="form-select @error('coverage_code') is-invalid @enderror" name="coverage_code" id="coverage_code" required style="width: 100%;">
                            <option value="">- Pilih Coverage -</option>
                            @foreach ($coverages as $coverage)
                                <option value="{{ $coverage->code }}" {{ isset($policy) && $coverage->code == $policy->coverage_code ? 'selected' : (old('coverage_code') == $coverage->code ? 'selected' : '') }}>
                                    {{ $coverage->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('coverage_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="coverage_percentage">Persentase Coverage (%) <span class="text-danger">*</span></label>
                        <input class="form-control @error('coverage_percentage') is-invalid @enderror" id="coverage_percentage" name="coverage_percentage" type="number" step="0.01" min="0" max="100" value="{{ isset($policy) ? $policy->coverage_percentage : old('coverage_percentage', '') }}" placeholder="Contoh: 80.50" required />
                        @error('coverage_percentage')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="started_date">Tanggal Mulai <span class="text-danger">*</span></label>
                        <input class="form-control @error('started_date') is-invalid @enderror" id="started_date" name="started_date" type="date" value="{{ isset($policy) ? $policy->started_date->format('Y-m-d') : old('started_date', '') }}" required />
                        @error('started_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="ended_date">Tanggal Berakhir <span class="text-danger">*</span></label>
                        <input class="form-control @error('ended_date') is-invalid @enderror" id="ended_date" name="ended_date" type="date" value="{{ isset($policy) ? $policy->ended_date->format('Y-m-d') : old('ended_date', '') }}" required />
                        @error('ended_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Insurances Section -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Asuransi</h5>
                    <button type="button" class="btn btn-primary btn-sm" id="add-insurance-btn">
                        <i class="fa fa-plus"></i> Tambah Asuransi
                    </button>
                </div>
                <div class="card-body">
                    <div id="insurances-container">
                        @if(isset($policyInsurances) && $policyInsurances->count() > 0)
                            @foreach($policyInsurances as $index => $policyInsurance)
                                <div class="insurance-row mb-3 p-3 border rounded" data-index="{{ $index }}">
                                    <div class="row align-items-end">
                                        <div class="col-md-6 mb-2 mb-md-0">
                                            <label class="form-label">Asuransi {{ $index + 1 }}</label>
                                            <select class="form-select insurance-select" name="insurance_id[]" style="width: 100%;" required>
                                                <option value="">- Pilih Asuransi -</option>
                                                @foreach($insurances as $insurance)
                                                    <option value="{{ $insurance->id }}" {{ $policyInsurance->insurance_id == $insurance->id ? 'selected' : '' }}>
                                                        {{ $insurance->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2 mb-2 mb-md-0">
                                            <label class="form-label">Persentase (%)</label>
                                            <input type="number" class="form-control" name="percentage[]" value="{{ $policyInsurance->percentage }}" placeholder="0" min="0" max="100" step="0.01">
                                        </div>
                                        <div class="col-md-2 mb-2 mb-md-0">
                                            <label class="form-label">Asuransi Utama</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="is_main[]" value="1" id="is_main_{{ $index }}" {{ $policyInsurance->is_main ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_main_{{ $index }}">Ya</label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger w-100 remove-insurance-btn">
                                                <i class="fa fa-trash"></i> Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    
                    <div id="empty-state" class="text-center py-4 text-muted" style="{{ (isset($policyInsurances) && $policyInsurances->count() > 0) ? 'display: none;' : '' }}">
                        <i class="fa fa-info-circle fa-2x mb-2 d-block"></i>
                        <p class="mb-0">Klik tombol <strong class="text-primary">"Tambah Asuransi"</strong> untuk menambahkan asuransi</p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-footer text-end">
                    <a href="{{ route('policies.index') }}" class="btn btn-secondary">
                        <i class="fa fa-times"></i> Batal
                    </a>
                    <button class="btn btn-primary" type="submit">
                        <i class="fa fa-save"></i> Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
    <!-- Container-fluid Ends-->
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/form-validation.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2-custom.js') }}"></script>
    <script>
        let insuranceIndex = {{ isset($policyInsurances) ? $policyInsurances->count() : 0 }};
        const insurancesData = {!! json_encode($insurances) !!};

        function addInsuranceRow() {
            const row = `
                <div class="insurance-row mb-3 p-3 border rounded" data-index="${insuranceIndex}">
                    <div class="row align-items-end">
                        <div class="col-md-6 mb-2 mb-md-0">
                            <label class="form-label">Asuransi ${insuranceIndex + 1}</label>
                            <select class="form-select insurance-select" name="insurance_id[]" style="width: 100%;" required>
                                <option value="">- Pilih Asuransi -</option>
                                ${insurancesData.map(ins => `<option value="${ins.id}">${ins.name}</option>`).join('')}
                            </select>
                        </div>
                        <div class="col-md-2 mb-2 mb-md-0">
                            <label class="form-label">Persentase (%)</label>
                            <input type="number" class="form-control" name="percentage[]" placeholder="0" min="0" max="100" step="0.01">
                        </div>
                        <div class="col-md-2 mb-2 mb-md-0">
                            <label class="form-label">Asuransi Utama</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_main[]" value="1" id="is_main_${insuranceIndex}">
                                <label class="form-check-label" for="is_main_${insuranceIndex}">Ya</label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger w-100 remove-insurance-btn">
                                <i class="fa fa-trash"></i> Hapus
                            </button>
                        </div>
                    </div>
                </div>
            `;
            document.getElementById('insurances-container').insertAdjacentHTML('beforeend', row);
            document.getElementById('empty-state').style.display = 'none';
            insuranceIndex++;
            initializeSelect2();
        }

        document.getElementById('add-insurance-btn').addEventListener('click', function(e) {
            e.preventDefault();
            addInsuranceRow();
        });

        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-insurance-btn')) {
                e.target.closest('.insurance-row').remove();
                const rows = document.querySelectorAll('.insurance-row');
                if (rows.length === 0) {
                    document.getElementById('empty-state').style.display = 'block';
                }
            }
        });

        function initializeSelect2() {
            $('.insurance-select').select2({
                placeholder: '- Pilih Asuransi -',
                allowClear: true
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            initializeSelect2();
            initializeFormValidation({
                formSelector: '.needs-validation'
            });
        });
    </script>
@endpush