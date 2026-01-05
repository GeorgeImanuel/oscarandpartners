@extends('layouts.master')

@section('title', 'Polis')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.css') }}">
<style>
    .percentage-error {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
        display: none;
    }
    .percentage-error.show {
        display: block;
    }
    /* Memaksa warna hijau saat valid */
    .alert-success {
        background-color: #d4edda !important;
        border-color: #c3e6cb !important;
        color: #155724 !important;
    }
    /* Warna border input saat valid */
    .is-valid {
        border-color: #28a745 !important;
        background-image: none !important;
    }
</style>
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
                    <div>
                        <h5>Asuransi</h5>
                        <small class="text-muted">Total persentase harus 100%</small>
                    </div>
                    <button type="button" class="btn btn-primary btn-sm" id="add-insurance-btn">
                        <i class="fa fa-plus"></i> Tambah Asuransi
                    </button>
                </div>
                <div class="card-body">
                    <div class="alert alert-info d-flex justify-content-between align-items-center mb-3" role="alert" id="percentage-info" style="display: none;">
                        <span>Total Persentase: <strong id="total-percentage">0</strong>%</span>
                    </div>
                    <div class="alert alert-danger" role="alert" id="percentage-error" style="display: none;">
                        Total persentase asuransi harus tepat 100%
                    </div>
                    <div id="insurances-container">
                        @if(isset($policyInsurances) && $policyInsurances->count() > 0)
                            @foreach($policyInsurances as $index => $policyInsurance)
                                <div class="insurance-row mb-3 p-3 border rounded" data-index="{{ $index }}">
                                    <div class="row align-items-end">
                                        <div class="col-md-6 mb-2 mb-md-0">
                                            <label class="form-label">Asuransi {{ $index + 1 }} <span class="text-danger">*</span></label>
                                            <div class="input-group insurance-input-group">
                                                <div class="flex-grow-1 select-container">
                                                    <select class="form-select insurance-select" name="insurance_id[{{ $index }}]" style="width: 100%;">
                                                        <option value="">- Pilih Asuransi -</option>
                                                        @foreach($insurances as $insurance)
                                                            <option value="{{ $insurance->id }}" {{ $policyInsurance->insurance_id == $insurance->id ? 'selected' : '' }}>
                                                                {{ $insurance->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <input type="text" class="form-control insurance-new-name d-none" name="insurance_new_name[{{ $index }}]" placeholder="Nama Asuransi Baru">
                                                <button type="button" class="btn btn-outline-primary toggle-insurance-input" title="Tambah Baru">
                                                    <i class="fa fa-plus">Tambah</i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-2 mb-2 mb-md-0">
                                            <label class="form-label">Persentase (%)</label>
                                            <input type="number" class="form-control insurance-percentage" name="percentage[{{ $index }}]" value="{{ $policyInsurance->percentage }}" placeholder="0" min="0" max="100" step="0.01">
                                        </div>
                                        <div class="col-md-2 mb-2 mb-md-0">
                                            <label class="form-label">Asuransi Utama</label>
                                            <div class="form-check">
                                                <input class="form-check-input is-main-checkbox" type="checkbox" name="is_main[{{ $index }}]" value="1" id="is_main_{{ $index }}" {{ $policyInsurance->is_main ? 'checked' : '' }}>
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
                    <button class="btn btn-primary" type="submit" id="submit-btn">
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

        // Fungsi Menambah Baris Asuransi
        function addInsuranceRow() {
            const row = `
                <div class="insurance-row mb-3 p-3 border rounded" data-index="${insuranceIndex}">
                    <div class="row align-items-end">
                        <div class="col-md-6 mb-2 mb-md-0">
                            <label class="form-label">Asuransi ${insuranceIndex + 1} <span class="text-danger">*</span></label>
                            <div class="input-group insurance-input-group">
                                <div class="flex-grow-1 select-container">
                                    <select class="form-select insurance-select" name="insurance_id[${insuranceIndex}]" style="width: 100%;">
                                        <option value="">- Pilih Asuransi -</option>
                                        ${insurancesData.map(ins => `<option value="${ins.id}">${ins.name}</option>`).join('')}
                                    </select>
                                </div>
                                <input type="text" class="form-control insurance-new-name d-none" name="insurance_new_name[${insuranceIndex}]" placeholder="Nama Asuransi Baru">
                                <button type="button" class="btn btn-outline-primary toggle-insurance-input" title="Tambah Baru">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-2 mb-2 mb-md-0">
                            <label class="form-label">Persentase (%)</label>
                            <input type="number" class="form-control insurance-percentage" name="percentage[${insuranceIndex}]" placeholder="0" min="0" max="100" step="0.01" required>
                        </div>
                        <div class="col-md-2 mb-2 mb-md-0">
                            <label class="form-label">Asuransi Utama</label>
                            <div class="form-check">
                                <input class="form-check-input is-main-checkbox" type="checkbox" name="is_main[${insuranceIndex}]" value="1" id="is_main_${insuranceIndex}">
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
            
            // Re-inisialisasi event listeners
            initializeSelect2();
            initializeMainCheckboxes();
            initializePercentageValidation();
        }

        // Logic Checkbox "Asuransi Utama" (Hanya boleh 1)
        function initializeMainCheckboxes() {
            const mainCheckboxes = document.querySelectorAll('.is-main-checkbox');
            mainCheckboxes.forEach(checkbox => {
                checkbox.removeEventListener('change', mainCheckboxHandler);
                checkbox.addEventListener('change', mainCheckboxHandler);
            });
        }

        function mainCheckboxHandler() {
            if (this.checked) {
                const mainCheckboxes = document.querySelectorAll('.is-main-checkbox');
                mainCheckboxes.forEach(cb => {
                    if (cb !== this) cb.checked = false;
                });
            }
        }

        // Logic Validasi Persentase Real-time
        function initializePercentageValidation() {
            const percentageInputs = document.querySelectorAll('.insurance-percentage');
            percentageInputs.forEach(input => {
                input.removeEventListener('input', updatePercentageTotal);
                input.addEventListener('input', updatePercentageTotal);
                // Hapus warna merah saat user mengetik
                input.addEventListener('input', function() {
                    this.classList.remove('is-invalid');
                });
            });
        }

        // Menghitung Total dan Update UI (Warna Alert)
        function updatePercentageTotal() {
            const percentageInputs = document.querySelectorAll('.insurance-percentage');
            let total = 0;
            
            percentageInputs.forEach(input => {
                const value = parseFloat(input.value) || 0;
                total += value;
            });

            // Pembulatan 2 desimal untuk menghindari floating point error (e.g. 99.99999)
            total = Math.round(total * 100) / 100;

            const infoDiv = document.getElementById('percentage-info');
            const totalPercentageSpan = document.getElementById('total-percentage');
            const errorDiv = document.getElementById('percentage-error'); // Ambil div error

            if (percentageInputs.length > 0) {
                infoDiv.style.display = 'flex';
                totalPercentageSpan.textContent = total;
                
                // Reset Class
                infoDiv.className = 'alert d-flex justify-content-between align-items-center mb-3';
                
                if (total !== 100) {
                    // Jika TIDAK 100%, beri warna warning/danger
                    infoDiv.classList.add(total > 100 ? 'alert-danger' : 'alert-warning');
                    // Sembunyikan error text saat sedang mengedit (opsional, agar tidak mengganggu)
                    // errorDiv.style.display = 'none'; 
                } else {
                    // Jika TEPAT 100%, beri warna hijau
                    infoDiv.classList.add('alert-success');
                    errorDiv.style.display = 'none'; // Sembunyikan error jika sudah benar
                    
                    // Hapus invalid class dari semua input
                    percentageInputs.forEach(input => input.classList.remove('is-invalid'));
                }
            } else {
                infoDiv.style.display = 'none';
                errorDiv.style.display = 'none';
            }
            
            return total;
        }

        // --- VALIDASI SAAT SUBMIT ---
        function validatePercentageBeforeSubmit(e) {
            const percentageInputs = document.querySelectorAll('.insurance-percentage');
            
            // Jika tidak ada asuransi, biarkan lewat (atau return false jika asuransi wajib)
            if (percentageInputs.length === 0) {
                return true; 
            }

            // Validasi Asuransi Utama
            const mainCheckboxes = document.querySelectorAll('.is-main-checkbox:checked');
            if (mainCheckboxes.length === 0) {
                e.preventDefault();
                e.stopPropagation();
                
                const errorDiv = document.getElementById('percentage-error');
                errorDiv.textContent = 'Gagal Simpan: Harap pilih salah satu sebagai Asuransi Utama.';
                errorDiv.style.display = 'block';
                
                document.getElementById('percentage-info').scrollIntoView({ behavior: 'smooth', block: 'center' });
                return false;
            }

            const total = updatePercentageTotal(); // Ambil total terbaru

            if (total !== 100) {
                // STOP PROSES SUBMIT
                e.preventDefault();
                e.stopPropagation();
                
                let errorMessage = '';
                if (total > 100) {
                    errorMessage = `Gagal Simpan: Total persentase (${total}%) melebihi 100%.`;
                } else {
                    errorMessage = `Gagal Simpan: Total persentase (${total}%) kurang dari 100%.`;
                }
                
                // Tampilkan Error
                const errorDiv = document.getElementById('percentage-error');
                errorDiv.textContent = errorMessage;
                errorDiv.style.display = 'block';

                // Beri highlight merah pada semua input persentase
                percentageInputs.forEach(input => {
                    input.classList.add('is-invalid');
                });

                // Scroll ke atas agar user melihat errornya
                document.getElementById('percentage-info').scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                return false;
            } else {
                // Jika Valid
                document.getElementById('percentage-error').style.display = 'none';
                return true;
            }
        }

        // Event Listeners Tombol
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
                    document.getElementById('percentage-info').style.display = 'none';
                    document.getElementById('percentage-error').style.display = 'none';
                }
                updatePercentageTotal();
            }
        });

        // Logic Toggle Input Asuransi (Select vs Text)
        document.addEventListener('click', function(e) {
            const toggleBtn = e.target.closest('.toggle-insurance-input');
            if (toggleBtn) {
                e.preventDefault();
                const group = toggleBtn.closest('.insurance-input-group');
                const selectContainer = group.querySelector('.select-container');
                const select = group.querySelector('.insurance-select');
                const input = group.querySelector('.insurance-new-name');
                const icon = toggleBtn.querySelector('i');

                if (input.classList.contains('d-none')) {
                    // Switch to Text Input
                    selectContainer.classList.add('d-none');
                    input.classList.remove('d-none');
                    $(select).val(null).trigger('change'); // Reset select
                    icon.className = 'fa fa-list';
                    toggleBtn.title = 'Pilih dari daftar';
                } else {
                    // Switch to Select
                    selectContainer.classList.remove('d-none');
                    input.classList.add('d-none');
                    input.value = ''; // Reset input
                    icon.className = 'fa fa-plus';
                    toggleBtn.title = 'Tambah Baru';
                }
            }
        });

        function initializeSelect2() {
            $('.insurance-select').select2({
                placeholder: '- Pilih Asuransi -',
                allowClear: true
            });
        }

        // Inisialisasi saat Load
        document.addEventListener('DOMContentLoaded', function() {
            initializeSelect2();
            initializeMainCheckboxes();
            initializePercentageValidation();
            updatePercentageTotal();
            
            // Attach Validasi ke Form Submit
            const form = document.querySelector('.needs-validation');
            if(form) {
                form.addEventListener('submit', validatePercentageBeforeSubmit);
            }
        });
    </script>
@endpush