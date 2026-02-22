@extends('layouts.master')

@section('title', 'Polis')

@push('css')
{{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.css') }}"> --}}
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
    /* Custom warna orange untuk alert */
    .alert-orange {
        background-color: #ffe0b2 !important;
        border-color: #ffcc80 !important;
        color: #e65100 !important;
    }
    /* Warna border input saat valid */
    .is-valid {
        border-color: #28a745 !important;
        background-image: none !important;
    }
    /* Memastikan kursor default pada container baris asuransi */
    .insurance-row {
        cursor: default !important; /* Memaksa kursor menjadi panah biasa */
    }

    .insurance-row button, .insurance-row .form-check-input, .insurance-row .select2-selection, .form-select {
        cursor: pointer !important;
    }
</style>
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center">
        @component('components.breadcrumb')
            @slot('breadcrumb_title')
            <h3>{{ isset($policy) ? 'Ubah' : 'Tambah' }} Polis</h3>
            @endslot
            <li class="breadcrumb-item"><a href="{{ route('policies.index') }}">Polis</a></li>
            <li class="breadcrumb-item active">{{ isset($policy) ? 'Ubah' : 'Tambah' }}</li>
        @endcomponent
        <div class="d-flex gap-2"> 
            <a href="{{ route('policies.index') }}" class="btn btn-info btn-sm">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
            @isset($policy)<a href="{{ route('policies.show', $policy->id) }}" class="btn btn-secondary btn-sm">Detail</a>@endisset
        </div>
    </div>

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
                        <label class="form-label" for="currency_code">Mata Uang</label>
                        <select class="form-select @error('currency_code') is-invalid @enderror" name="currency_code" id="currency_code" style="width: 100%;">
                            <option value="">- Pilih Mata Uang -</option>
                            @foreach ($currencies ?? [] as $currency)
                                <option value="{{ $currency->code }}" {{ isset($policy) && $currency->code == $policy->currency_code ? 'selected' : (old('currency_code') == $currency->code ? 'selected' : '') }}>
                                    {{ $currency->code }} - {{ $currency->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('currency_code')
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

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Obyek Pertanggungan</h5>
                        <small class="text-muted">Pilih satu atau lebih obyek</small>
                    </div>
                    <button type="button" class="btn btn-primary btn-sm" id="add-object-btn">
                        <i class="icon-plus me-2"></i>Tambah Obyek
                    </button>
                </div>
                <div class="card-body">
                    <div class="alert alert-primary d-flex justify-content-between align-items-center mb-3" role="alert" id="object-value-info" style="display: none;">
                        <span>Total Nilai Obyek: <strong><span id="total-object-value">0</span></strong></span>
                    </div>

                    <div id="objects-container">
                        {{-- Loop untuk Objek yang sudah tersimpan (Saat Edit) --}}
                        @if(isset($policy) && $policy->objects->count() > 0)
                            @foreach($policy->objects as $index => $obj)
                                <div class="object-row mb-3 p-3 border rounded" data-index="{{ $index }}">
                                    <div class="row align-items-end">
                                        <div class="col-md-6 mb-2 mb-md-0">
                                            <label class="form-label">Obyek {{ $index + 1 }} <span class="text-danger">*</span></label>
                                            <select class="form-select object-select" name="object_id[]" style="width: 100%;" required>
                                                <option value="">- Pilih Obyek -</option>
                                                @foreach($objects as $item)
                                                    {{-- PERBAIKAN 1: Ambil sum value dari objectInterests --}}
                                                    <option value="{{ $item->id }}" 
                                                        data-value="{{ $item->objectInterests->sum('value') }}" 
                                                        {{ $obj->id == $item->id ? 'selected' : '' }}>
                                                        {{ $item->name }} - {{ $item->keterangan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-2 mb-md-0">
                                            <label class="form-label">Nilai Obyek (Estimasi)</label>
                                            {{-- PERBAIKAN 2: Tampilkan sum value dari objectInterests --}}
                                            <input type="text" class="form-control object-value-display" 
                                                value="{{ number_format($obj->objectInterests->sum('value'), 0, ',', '.') }}" 
                                                readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger w-100 remove-object-btn">
                                                <i class="fa fa-trash"></i> Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    
                    <div id="object-empty-state" class="text-center py-4 text-muted" style="{{ (isset($policy) && $policy->objects->count() > 0) ? 'display: none;' : '' }}">
                        <i class="fa fa-cubes fa-2x mb-2 d-block"></i>
                        <p class="mb-0">Klik tombol <strong class="text-primary">"Tambah Objek"</strong> untuk menambahkan objek</p>
                    </div>
                </div>
            </div>

            <!-- Coverages Section -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Coverage</h5>
                    </div>
                    <button type="button" class="btn btn-primary btn-sm" id="add-coverage-btn">
                        <i class="icon-plus me-2"></i>Tambah Coverage
                    </button>
                </div>
                <div class="card-body">
                    <div class="alert alert-info d-flex justify-content-between align-items-center mb-3" role="alert" id="coverage-percentage-info" style="display: none;">
                        <span>Total Persentase: <strong id="coverage-total-percentage">0</strong>%</span>
                    </div>
                    <div id="coverages-container">
                        <!-- Debug: Coverage count = {{ isset($policyCoverages) ? $policyCoverages->count() : 'N/A' }} -->
                        @if(isset($policyCoverages) && $policyCoverages->count() > 0)
                            @foreach($policyCoverages as $index => $policyCoverage)
                                <div class="coverage-row mb-3 p-3 border rounded" data-index="{{ $index }}">
                                    <div class="row align-items-end">
                                        <div class="col-md-8 mb-2 mb-md-0">
                                            <label class="form-label">Coverage {{ $index + 1 }} <span class="text-danger">*</span></label>
                                            <select class="form-select coverage-select" name="coverage_code[{{ $index }}]" style="width: 100%;">
                                                <option value="">- Pilih Coverage -</option>
                                                @foreach($coverages as $cov)
                                                    <option value="{{ $cov->code }}" {{ $policyCoverage->coverage_code == $cov->code ? 'selected' : '' }}>
                                                        {{ $cov->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2 mb-2 mb-md-0">
                                            <label class="form-label">Persentase (%)</label>
                                            <input type="number" class="form-control coverage-percentage" name="coverage_percentage[{{ $index }}]" value="{{ $policyCoverage->percentage * 100 }}" placeholder="0" min="0" max="100" step="0.0000001" required>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger w-100 remove-coverage-btn">
                                                <i class="fa fa-trash"></i> Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    
                    <div id="coverage-empty-state" class="text-center py-4 text-muted" style="{{ (isset($policyCoverages) && $policyCoverages->count() > 0) ? 'display: none;' : '' }}">
                        <i class="fa fa-info-circle fa-2x mb-2 d-block"></i>
                        <p class="mb-0">Klik tombol <strong class="text-primary">"Tambah Coverage"</strong> untuk menambahkan coverage</p>
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
                        <i class="icon-plus me-2"></i>Tambah Asuransi
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
                            @foreach($policyInsurances->sortByDesc('percentage') as $index => $policyInsurance)
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
                                                    <i class="fa fa-plus"></i> Baru
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-2 mb-2 mb-md-0">
                                            <label class="form-label">Persentase (%)</label>
                                            <input type="number" class="form-control insurance-percentage" name="percentage[{{ $index }}]" value="{{ $policyInsurance->percentage * 100}}" placeholder="0" min="0" max="100" step="0.00001">
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
    <script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
    <script>
        // ==================== GLOBAL DATA ====================
        const objectsData = {!! json_encode($objects->map(function($obj) {
            return [
                'id' => $obj->id,
                'name' => $obj->name,
                'keterangan' => $obj->keterangan,
                'total_value' => $obj->objectInterests->sum('value') // Hitung sum disini
            ];
        })) !!};
        const insurancesData = {!! json_encode($insurances) !!};
        const coveragesData = {!! json_encode($coverages) !!};


        // ==================== 1. OBJECT FUNCTIONS (BARU) ====================
        let objectIndex = {{ isset($policy) ? $policy->objects->count() : 0 }};

        function addObjectRow() {
            const row = `
                <div class="object-row mb-3 p-3 border rounded" data-index="${objectIndex}">
                    <div class="row align-items-end">
                        <div class="col-md-6 mb-2 mb-md-0">
                            <label class="form-label">Objek ${objectIndex + 1} <span class="text-danger">*</span></label>
                            <select class="form-select object-select" name="object_id[]" style="width: 100%;" required>
                                <option value="">- Pilih Objek -</option>
                                // PERBAIKAN 4: Gunakan property 'total_value' yang sudah kita siapkan diatas
                                ${objectsData.map(obj => `<option value="${obj.id}" data-value="${obj.total_value}">${obj.name} - ${obj.keterangan}</option>`).join('')}
                            </select>
                        </div>
                        <div class="col-md-4 mb-2 mb-md-0">
                            <label class="form-label">Nilai Objek (Estimasi)</label>
                            <input type="text" class="form-control object-value-display" value="0" readonly>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger w-100 remove-object-btn">
                                <i class="fa fa-trash"></i> Hapus
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.getElementById('objects-container').insertAdjacentHTML('beforeend', row);
            document.getElementById('object-empty-state').style.display = 'none';
            
            objectIndex++;
            updateObjectLabels();
        }

        function updateObjectLabels() {
            $('.object-row').each(function(idx) {
                $(this).find('.form-label').first().html(`Objek ${idx + 1} <span class="text-danger">*</span>`);
            });
            objectIndex = $('.object-row').length;
        }

        // Hitung nilai saat select berubah
        $(document).on('change', '.object-select', function() {
            const selectedOption = $(this).find(':selected');
            const value = parseFloat(selectedOption.data('value')) || 0;
            const row = $(this).closest('.object-row');
            
            // Format Rupiah sederhana
            const formattedValue = new Intl.NumberFormat('id-ID').format(value);
            row.find('.object-value-display').val(formattedValue);

            calculateTotalObjectValue();
        });

        function calculateTotalObjectValue() {
            let total = 0;
            $('.object-select').each(function() {
                const val = parseFloat($(this).find(':selected').data('value')) || 0;
                total += val;
            });
            
            $('#total-object-value').text(new Intl.NumberFormat('id-ID').format(total));
            
            if(total > 0) $('#object-value-info').show();
            else $('#object-value-info').hide();
        }

        // Event Listener Object Buttons
        $('#add-object-btn').click(function(e) {
            e.preventDefault();
            addObjectRow();
        });

        $(document).on('click', '.remove-object-btn', function() {
            $(this).closest('.object-row').remove();
            if ($('.object-row').length === 0) {
                $('#object-empty-state').show();
                $('#object-value-info').hide();
            }
            updateObjectLabels();
            calculateTotalObjectValue();
        });
        // ==================== END OBJECT FUNCTIONS ====================


        // ==================== 2. INSURANCE FUNCTIONS (TIDAK DIUBAH) ====================
        let insuranceIndex = {{ isset($policyInsurances) ? $policyInsurances->count() : 0 }};

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
                                    <i class="fa fa-plus"></i> Baru
                                </button>
                            </div>
                        </div>
                        <div class="col-md-2 mb-2 mb-md-0">
                            <label class="form-label">Persentase (%)</label>
                            <input type="number" class="form-control insurance-percentage" name="percentage[${insuranceIndex}]" placeholder="0" min="0" max="100" step="0.00001" required>
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
            updateInsuranceNumbers();
            
            // Re-inisialisasi event listeners
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
                let val = parseFloat(input.value);
                if (isNaN(val)) val = 0;
                total += val;
            });

            total = Math.round(total * 10000) / 10000;

            const infoDiv = document.getElementById('percentage-info');
            const totalPercentageSpan = document.getElementById('total-percentage');
            const errorDiv = document.getElementById('percentage-error');

            if (percentageInputs.length > 0) {
                infoDiv.style.display = 'flex';
                totalPercentageSpan.textContent = total;
                
                infoDiv.className = 'alert d-flex justify-content-between align-items-center mb-3';
                
                if (total !== 100) {
                    infoDiv.classList.add(total > 100 ? 'alert-danger' : 'alert-orange');
                } else {
                    infoDiv.classList.add('alert-success');
                    if(errorDiv) errorDiv.style.display = 'none'; 
                    percentageInputs.forEach(input => input.classList.remove('is-invalid'));
                }
            } else {
                infoDiv.style.display = 'none';
                if(errorDiv) errorDiv.style.display = 'none';
            }
            
            return total;
        }

        // Update Penomoran Asuransi (Label & Index Input)
        function updateInsuranceNumbers() {
            const rows = document.querySelectorAll('.insurance-row');
            rows.forEach((row, index) => {
                const label = row.querySelector('.form-label');
                if (label) {
                    label.innerHTML = `Asuransi ${index + 1} <span class="text-danger">*</span>`;
                }

                const inputs = row.querySelectorAll('[name*="["]');
                inputs.forEach(input => {
                    const name = input.getAttribute('name');
                    if (name) {
                        input.setAttribute('name', name.replace(/\[\d+\]/, `[${index}]`));
                    }
                    if (input.id && input.id.match(/_\d+$/)) {
                        input.id = input.id.replace(/_\d+$/, `_${index}`);
                    }
                });

                const labels = row.querySelectorAll('label[for]');
                labels.forEach(lbl => {
                    const forAttr = lbl.getAttribute('for');
                    if (forAttr && forAttr.match(/_\d+$/)) {
                        lbl.setAttribute('for', forAttr.replace(/_\d+$/, `_${index}`));
                    }
                });

                row.setAttribute('data-index', index);
            });
            
            insuranceIndex = rows.length;
        }

        // --- VALIDASI SAAT SUBMIT ---
        function validatePercentageBeforeSubmit(e) {
            const percentageInputs = document.querySelectorAll('.insurance-percentage');
            
            if (percentageInputs.length === 0) {
                e.preventDefault(); e.stopPropagation();
                Swal.fire('Validasi Gagal', 'Harap tambahkan minimal satu asuransi.', 'error');
                return false; 
            }

            const total = updatePercentageTotal(); 

            if (total !== 100) {
                e.preventDefault(); e.stopPropagation();
                
                let errorMessage = total > 100 
                    ? `Gagal Simpan: Total persentase asuransi (${total}%) melebihi 100%.`
                    : `Gagal Simpan: Total persentase asuransi (${total}%) kurang dari 100%.`;
                
                const errorDiv = document.getElementById('percentage-error');
                errorDiv.textContent = errorMessage;
                errorDiv.style.display = 'block';

                percentageInputs.forEach(input => input.classList.add('is-invalid'));
                document.getElementById('percentage-info').scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                return false;
            } 
            
            return true;
        }

        document.getElementById('add-insurance-btn').addEventListener('click', function(e) {
            e.preventDefault();
            addInsuranceRow();
        });

        document.addEventListener('click', function(e) {
            const removeBtn = e.target.closest('.remove-insurance-btn');
            if (removeBtn) {
                const row = removeBtn.closest('.insurance-row');
                
                Swal.fire({
                    title: 'Hapus Asuransi?',
                    text: "Asuransi ini akan dihapus dari daftar.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        row.remove();
                        const rows = document.querySelectorAll('.insurance-row');
                        if (rows.length === 0) {
                            document.getElementById('empty-state').style.display = 'block';
                            document.getElementById('percentage-info').style.display = 'none';
                            document.getElementById('percentage-error').style.display = 'none';
                        }
                        updateInsuranceNumbers();
                        updatePercentageTotal();
                    }
                });
            }
        });

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
                    selectContainer.classList.add('d-none');
                    input.classList.remove('d-none');
                    select.value = '';
                    icon.className = 'fa fa-list';
                    toggleBtn.title = 'Pilih dari daftar';
                } else {
                    selectContainer.classList.remove('d-none');
                    input.classList.add('d-none');
                    input.value = '';
                    icon.className = 'fa fa-plus';
                    toggleBtn.title = 'Tambah Baru';
                }
            }
        });

        function initializeSelect2() {
            // Select2 disabled - using native select elements
        }


        // ==================== 3. COVERAGE FUNCTIONS (TIDAK DIUBAH) ====================
        let coverageIndex = {{ isset($policyCoverages) ? $policyCoverages->count() : 0 }};

        function addCoverageRow() {
            const row = `
                <div class="coverage-row mb-3 p-3 border rounded" data-index="${coverageIndex}">
                    <div class="row align-items-end">
                        <div class="col-md-8 mb-2 mb-md-0">
                            <label class="form-label">Coverage ${coverageIndex + 1} <span class="text-danger">*</span></label>
                            <select class="form-select coverage-select" name="coverage_code[${coverageIndex}]" style="width: 100%;">
                                <option value="">- Pilih Coverage -</option>
                                ${coveragesData.map(cov => `<option value="${cov.code}">${cov.name}</option>`).join('')}
                            </select>
                        </div>
                        <div class="col-md-2 mb-2 mb-md-0">
                            <label class="form-label">Persentase (%)</label>
                            <input type="number" class="form-control coverage-percentage" name="coverage_percentage[${coverageIndex}]" placeholder="0" min="0" step="0.0000001" required>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger w-100 remove-coverage-btn">
                                <i class="fa fa-trash"></i> Hapus
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.getElementById('coverages-container').insertAdjacentHTML('beforeend', row);
            document.getElementById('coverage-empty-state').style.display = 'none';
            
            coverageIndex++;
            updateCoveragePercentageTotal();
        }

        function updateCoveragePercentageTotal() {
            const percentageInputs = document.querySelectorAll('.coverage-percentage');
            let total = 0;

            percentageInputs.forEach(input => {
                let val = parseFloat(input.value);
                if (isNaN(val)) val = 0;
                total += val;
            });

            total = Math.round(total * 10000) / 10000;

            const infoDiv = document.getElementById('coverage-percentage-info');
            const totalPercentageSpan = document.getElementById('coverage-total-percentage');

            if (percentageInputs.length > 0) {
                infoDiv.style.display = 'flex';
                totalPercentageSpan.textContent = total;
                infoDiv.className = 'alert alert-info d-flex justify-content-between align-items-center mb-3';
            } else {
                infoDiv.style.display = 'none';
            }
            
            return total;
        }

        function validateCoverageBeforeSubmit(e) {
            const percentageInputs = document.querySelectorAll('.coverage-percentage');
            
            if (percentageInputs.length === 0) {
                e.preventDefault(); e.stopPropagation();
                const errorDiv = document.getElementById('coverage-percentage-error');
                if(errorDiv) {
                    errorDiv.textContent = 'Gagal Simpan: Harap tambahkan minimal satu coverage.';
                    errorDiv.style.display = 'block';
                } else {
                    Swal.fire('Validasi Gagal', 'Harap tambahkan minimal satu coverage.', 'error');
                }
                return false;
            }

            let isValid = true;
            percentageInputs.forEach(input => {
                let val = parseFloat(input.value);
                if (isNaN(val) || val < 0) {
                    input.classList.add('is-invalid');
                    isValid = false;
                } else {
                    input.classList.remove('is-invalid');
                }
            });

            if (!isValid) {
                e.preventDefault(); e.stopPropagation();
                Swal.fire('Validasi Gagal', 'Nilai persentase coverage tidak valid (harus angka positif).', 'error');
                return false;
            }
            return true;
        }

        document.getElementById('add-coverage-btn').addEventListener('click', function(e) {
            e.preventDefault();
            addCoverageRow();
        });

        document.addEventListener('click', function(e) {
            const removeBtn = e.target.closest('.remove-coverage-btn');
            if (removeBtn) {
                const row = removeBtn.closest('.coverage-row');
                
                Swal.fire({
                    title: 'Hapus Coverage?',
                    text: "Coverage ini akan dihapus dari daftar.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        row.remove();
                        const rows = document.querySelectorAll('.coverage-row');
                        if (rows.length === 0) {
                            document.getElementById('coverage-empty-state').style.display = 'block';
                            document.getElementById('coverage-percentage-info').style.display = 'none';
                            document.getElementById('coverage-percentage-error').style.display = 'none';
                        }
                        updateCoveragePercentageTotal();
                    }
                });
            }
        });

        function initializeCoveragePercentageValidation() {
            document.addEventListener('input', function(e) {
                if (e.target.classList.contains('coverage-percentage')) {
                    updateCoveragePercentageTotal();
                }
            });
        }

        // ==================== 4. INIT ====================
        document.addEventListener('DOMContentLoaded', function() {
            initializeSelect2();
            initializeMainCheckboxes();
            initializePercentageValidation();
            initializeCoveragePercentageValidation();
            updatePercentageTotal();
            updateCoveragePercentageTotal();
            
            // Auto add row if empty (Object)
            if ($('.object-row').length === 0) {
                addObjectRow();
                calculateTotalObjectValue();
            } else {
                calculateTotalObjectValue(); // Recalculate if editing existing
            }

            // Auto add row if empty (Coverage)
            const coverageContainer = document.getElementById('coverages-container');
            const coverageRows = coverageContainer ? coverageContainer.querySelectorAll('.coverage-row') : [];
            if (coverageContainer && coverageRows.length === 0) {
                addCoverageRow();
                updateCoveragePercentageTotal();
            }
            
            // Auto add row if empty (Insurance)
            const insuranceContainer = document.getElementById('insurances-container');
            const insuranceRows = insuranceContainer ? insuranceContainer.querySelectorAll('.insurance-row') : [];
            if (insuranceContainer && insuranceRows.length === 0) {
                addInsuranceRow();
                updatePercentageTotal();
            }
            
            // Form Submit Listener
            const form = document.querySelector('.needs-validation');
            if(form) {
                form.addEventListener('submit', function(e) {
                    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                    
                    const insuranceValid = validatePercentageBeforeSubmit(e);
                    const coverageValid = validateCoverageBeforeSubmit(e);
                    
                    if (!insuranceValid || !coverageValid) {
                        e.preventDefault();
                        e.stopPropagation();
                        console.log("Form submit prevented due to validation errors.");
                    }
                });
            }
        });
    </script>
@endpush