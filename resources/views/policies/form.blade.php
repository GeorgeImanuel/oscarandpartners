@extends('layouts.master')

@section('title', 'Polis')

@push('css')
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
            <a href="{{ route('policies.index') }}"
                class="btn btn-info btn-sm">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
            @isset($policy)
                <a href="{{ route('policies.show', $policy->id) }}"
                    class="btn btn-secondary btn-sm">Detail</a>
            @endisset
        </div>
    </div>

    <!-- Container-fluid starts-->
    <div class="container-fluid">
        
        {{-- ERROR DISPLAY - Show all validation errors --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><i class="fa fa-exclamation-triangle"></i> Ada kesalahan:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- SUCCESS MESSAGE --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong><i class="fa fa-check-circle"></i> Berhasil!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- ERROR MESSAGE from controller --}}
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><i class="fa fa-times-circle"></i> Gagal!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form id="policy-form" 
            class="needs-validation"
            novalidate=""
            method="POST"
            action="{{ isset($policy) ? route('policies.update', $policy->id) : route('policies.store') }}">
            @csrf
            @if (isset($policy))
                @method('PUT')
            @endif
            <div class="card mb-3 p-3 shadow-sm">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <h5 class="text-primary">Polis</h5>
                        <hr class="separator">
                    </div>
                    <input type="hidden"
                        name="existing_policy_id"
                        value="{{ $policy->id ?? '' }}">
                    <div class="col-md-12 mb-3">
                        <label class="form-label"
                            for="policy_number">Nomor Polis <span class="text-danger">*</span></label>
                        <input type="text"
                            id="policy_number"
                            name="policy_number"
                            class="form-control @error('policy_number') is-invalid @enderror"
                            value="{{ old('policy_number', $policy->policy_number ?? '') }}"
                            placeholder="Masukkan Nomor Polis"
                            required>
                        @error('policy_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- New Policy Input --}}
            <div id="policy-form-container" wire:ignore.self>
                @livewire('policy-form')
            </div>

            {{-- Submit and Cancel Buttons --}}
            <div class="d-flex justify-content-end gap-2 mt-3 mb-3">
                <a href="{{ route('policies.index') }}" class="btn btn-secondary">
                    <i class="fa fa-times"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save"></i> Simpan
                </button>
            </div>

        </form>
    </div>
    <!-- Container-fluid Ends-->
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2-custom.js') }}"></script>
    <script src="{{ asset('assets/js/form-validation.js') }}"></script>
    <script src="{{ asset('assets/js/format.js') }}"></script>
    <script src="{{ asset('assets/js/formula.js') }}"></script>
    <script src="{{ asset('assets/js/multiple-item.js') }}"></script>
    <script>
        $(document).ready(function() {
            $(document).on('input keyup change blur', '.check-negative', function() {
                window.toggleNegativeWarning(this);
            });

            $(document).on('input keyup change blur', 'input[name^="interest_value"]', function() {
                setTimeout(function() {
                    var $totalField = $('input[name="object_value_input"]');
                    if ($totalField.length) {
                        window.toggleNegativeWarning($totalField);
                    }
                }, 100);
            });

            setTimeout(function() {
                $('.check-negative').each(function() {
                    window.toggleNegativeWarning(this);
                });
            }, 500);
        });

        // Initialize form validation with custom validator
        initializeFormValidation({
            onSuccess: function(form) {
                console.log('Form validation passed - onSuccess called');
                // Sync object_value to hidden field for proper submission
                var numericValue = parseFloat($('#object_value').val()) || 0;
                $('#object_value_raw').val(numericValue);

                if (typeof window.syncTaxToHiddenInputs === 'function') {
                    window.syncTaxToHiddenInputs();
                }
            },
            confirmTitle: 'Anda Yakin?',
            confirmMessage: 'Pastikan semua data sudah benar!',
            showConfirmDialog: true,
            onSubmit: function(form) {
                console.log('=== onSubmit called ===');
                
                // ============================================================
                // PREVENT DOUBLE SUBMIT
                // ============================================================
                var $submitBtn = $(form).find('button[type="submit"]');
                if ($submitBtn.prop('disabled')) {
                    console.warn('⚠️ Form already submitting, ignoring duplicate submit');
                    return false;
                }
                
                // Disable submit button and show loading
                $submitBtn.prop('disabled', true)
                    .html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');
                
                // Set flag bahwa form sedang submit
                window.formIsSubmitting = true;
                
                // Prevent form dari di-reset
                $(form).on('reset', function(e) {
                    if (window.formIsSubmitting) {
                        console.warn('Form reset prevented during submission');
                        e.preventDefault();
                        e.stopImmediatePropagation();
                        return false;
                    }
                });
                
                // ============================================================
                // REINDEX ARRAY INPUTS - Remove gaps in array indices
                // ============================================================
                console.log('🔄 Reindexing array inputs...');
                
                // Reindex object_id[] - only from visible, enabled items with valid container
                var objectIds = [];
                $('#object-item-container .item-object').not('.item-template, .no-print').each(function() {
                    var $container = $(this);
                    
                    // Skip if container is hidden
                    if ($container.css('display') === 'none') {
                        console.log('⏭️ Skipping hidden object container');
                        return;
                    }
                    
                    var $select = $container.find('select[name="object_id[]"]').first();
                    if ($select.length && !$select.is(':disabled')) {
                        var val = $select.val();
                        console.log('🔍 Object select found, value:', val, 'disabled:', $select.is(':disabled'));
                        if (val && val !== '' && val !== null) {
                            objectIds.push(val);
                        }
                    }
                });
                
                console.log('✅ Found', objectIds.length, 'valid object_id values:', objectIds);
                
                // Remove all old object_id inputs
                $(form).find('input[name="object_id[]"], select[name="object_id[]"]').remove();
                
                // Add reindexed as hidden inputs
                objectIds.forEach(function(val, index) {
                    console.log('  Adding object_id[' + index + ']:', val);
                    $('<input>').attr({
                        type: 'hidden',
                        name: 'object_id[]',
                        value: val
                    }).appendTo(form);
                });

                // Reindex insurance_id[] with percentage and is_main mapping
                var insuranceData = [];
                var duplicatesFound = [];
                $(form).find('.item.item-1').not('.no-print').filter(':visible').each(function() {
                    var $item = $(this);
                    var insuranceId = $item.find('select[name="insurance_id[]"]').val();
                    var percentage = $item.find('input[name="percentage[]"]').val();
                    var isMain = $item.find('input[name="is_main[]"]').val();
                    
                    if (insuranceId) {
                        // Check if this insurance already exists
                        var existingIndex = insuranceData.findIndex(function(item) {
                            return item.id === insuranceId;
                        });
                        
                        if (existingIndex >= 0) {
                            // Duplicate found - sum the percentages
                            console.warn('⚠️ Duplicate insurance_id: ' + insuranceId + ', summing percentages');
                            duplicatesFound.push(insuranceId);
                            insuranceData[existingIndex].percentage = (
                                parseFloat(insuranceData[existingIndex].percentage || 0) + 
                                parseFloat(percentage || 0)
                            ).toString();
                            // Keep is_main if any of them is main
                            if (isMain === '1') {
                                insuranceData[existingIndex].isMain = '1';
                            }
                        } else {
                            insuranceData.push({
                                id: insuranceId,
                                percentage: percentage || '0',
                                isMain: isMain || '0'
                            });
                        }
                    }
                });
                
                // Warn user if duplicates were found
                if (duplicatesFound.length > 0) {
                    console.warn('⚠️ Found duplicate insurances:', duplicatesFound);
                    console.warn('Percentages have been automatically merged.');
                }
                
                // Remove all old insurance inputs
                $(form).find('select[name="insurance_id[]"], input[name="insurance_id[]"]').remove();
                $(form).find('input[name="percentage[]"]').remove();
                $(form).find('input[name="is_main[]"], input[name="is_main_check[]"]').remove();
                
                // Add reindexed unique data
                insuranceData.forEach(function(item, index) {
                    console.log('  Adding insurance[' + index + ']:', item.id, 'percentage:', item.percentage, 'is_main:', item.isMain);
                    $('<input>').attr({
                        type: 'hidden',
                        name: 'insurance_id[]',
                        value: item.id
                    }).appendTo(form);
                    $('<input>').attr({
                        type: 'hidden',
                        name: 'percentage[]',
                        value: item.percentage
                    }).appendTo(form);
                    $('<input>').attr({
                        type: 'hidden',
                        name: 'is_main[]',
                        value: item.isMain
                    }).appendTo(form);
                });
                
                console.log('✅ Insurance data (unique, total: ' + insuranceData.length + '):', insuranceData.map(d => d.id));

                // Reindex coverage_code[] and coverage_percentage[] with duplicate handling
                var coverageData = [];
                $(form).find('.item.item-coverage').not('.no-print').filter(':visible').each(function() {
                    var $item = $(this);
                    var coverageCode = $item.find('select[name="coverage_code[]"]').val();
                    var percentage = $item.find('input[name="coverage_percentage[]"]').val();
                    
                    if (coverageCode) {
                        // Check if this coverage already exists
                        var existingIndex = coverageData.findIndex(function(item) {
                            return item.code === coverageCode;
                        });
                        
                        if (existingIndex >= 0) {
                            // Duplicate found - sum the percentages
                            console.warn('⚠️ Duplicate coverage_code: ' + coverageCode + ', summing percentages');
                            coverageData[existingIndex].percentage = (
                                parseFloat(coverageData[existingIndex].percentage || 0) + 
                                parseFloat(percentage || 0)
                            ).toString();
                        } else {
                            coverageData.push({
                                code: coverageCode,
                                percentage: percentage || '0'
                            });
                        }
                    }
                });
                
                // Remove all old coverage inputs
                $(form).find('select[name="coverage_code[]"], input[name="coverage_code[]"]').remove();
                $(form).find('input[name="coverage_percentage[]"]').remove();
                
                // Add reindexed unique data
                coverageData.forEach(function(item) {
                    $('<input>').attr({
                        type: 'hidden',
                        name: 'coverage_code[]',
                        value: item.code
                    }).appendTo(form);
                    $('<input>').attr({
                        type: 'hidden',
                        name: 'coverage_percentage[]',
                        value: item.percentage
                    }).appendTo(form);
                });
                
                console.log('✅ Coverage data (unique):', coverageData);

                console.log('✅ Reindexed summary:', {
                    object_id: objectIds.length,
                    insurances: insuranceData.length,
                    coverages: coverageData.length
                });
                
                // Collect form data untuk debug
                const formData = new FormData(form);
                console.log('=== Form Data ===');
                let dataCount = 0;
                for (let [key, value] of formData.entries()) {
                    console.log(key + ':', value);
                    dataCount++;
                }
                console.log('Total fields:', dataCount);
                
                // Clone form data ke hidden inputs sebagai backup
                console.log('Creating backup hidden inputs...');
                const backupDiv = document.createElement('div');
                backupDiv.id = 'form-backup-data';
                backupDiv.style.display = 'none';
                for (let [key, value] of formData.entries()) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key;
                    input.value = value;
                    backupDiv.appendChild(input);
                }
                form.appendChild(backupDiv);
                
                // Submit form naturally (like Insurance/Coverage form)
                console.log('🚀 Submitting form to:', form.action);
                console.log('📤 Method:', form.method);
                
                // Call form.submit() to trigger native submission
                form.submit();
            },
            customValidator: function(form, fields) {
                console.log('🔍 ============================================');
                console.log('🔍 CUSTOM VALIDATOR STARTED');
                console.log('🔍 ============================================');
                var errors = [];

                // ============================================================
                // 1. VALIDATE SELECT2 FIELDS (Visual feedback only)
                // Mark invalid fields but don't block submission
                // Server-side validation will handle actual validation
                // ============================================================
                console.log('📌 Step 1: Validating Select2 fields...');
                var select2Count = 0;
                $(form).find('select.create-select2.required').each(function() {
                    var $select = $(this);

                    // Skip if inside item-template or no-print (cloning templates)
                    if ($select.closest('.item-template, .no-print').length) {
                        return;
                    }

                    // Skip if inside hidden container
                    if ($select.closest('.object-forms-container').length && $select.closest('.object-forms-container').is(':hidden')) {
                        return;
                    }

                    // Skip disabled fields
                    if ($select.is(':disabled')) {
                        return;
                    }

                    // Skip fields that are not visible
                    var $parent = $select.closest('.item');
                    if ($parent.length && ($parent.css('display') === 'none' || !$parent.is(':visible'))) {
                        return;
                    }

                    select2Count++;

                    // Get value from select2
                    var value = $select.val();
                    if ($select.hasClass('select2-hidden-accessible')) {
                        value = $select.select2('val');
                    }

                    // Just visual feedback - don't add to errors
                    if (!value || value === '' || value === null || (Array.isArray(value) && value.length === 0)) {
                        $select.addClass('is-invalid');
                    } else {
                        $select.removeClass('is-invalid');
                    }
                });
                console.log('   ✓ Validated', select2Count, 'Select2 fields (visual feedback only)');

                // ============================================================
                // 2. VALIDATE INSURANCE PERCENTAGE
                // Total insurance percentage must equal 100% if there are filled values
                // ============================================================
                console.log('📌 Step 2: Validating Insurance Percentage...');
                var totalInsurancePercentage = 0;
                var hasFilledInsurance = false;
                var insuranceCount = 0;

                $(form).find('input[name="percentage[]"]').each(function() {
                    // Only count visible and enabled insurance percentage inputs
                    if ($(this).is(':visible') && !$(this).is(':disabled')) {
                        var val = parseFloat($(this).val()) || 0;
                        console.log('   - Insurance', (insuranceCount + 1) + ':', val + '%');
                        if (val > 0) {
                            hasFilledInsurance = true;
                        }
                        totalInsurancePercentage += val;
                        insuranceCount++;
                    }
                });

                console.log('   Total Insurance Percentage:', totalInsurancePercentage.toFixed(2) + '%');
                console.log('   Has Filled Insurance:', hasFilledInsurance);

                // Validate total equals 100% (only if user has started filling insurance percentages)
                if (hasFilledInsurance && totalInsurancePercentage !== 100) {
                    console.error('   ✗ VALIDATION FAILED: Insurance percentage must be exactly 100%');
                    alertWarning('Total persentase asuransi harus 100%. Saat ini: ' + totalInsurancePercentage.toFixed(2) + '%', '');
                    errors.push({
                        field: '.insurance-percentage',
                        message: 'Total persentase asuransi harus 100%'
                    });
                } else {
                    console.log('   ✓ Insurance percentage validation passed');
                }

                // ============================================================
                // 3. VALIDATE COVERAGE PERCENTAGE
                // Total coverage percentage must be between 0-100%
                // ============================================================
                console.log('📌 Step 3: Validating Coverage Percentage...');
                var totalCoveragePercentage = 0;
                var hasVisibleCoverage = false;
                var coverageCount = 0;

                $(form).find('input[name="coverage_percentage[]"]').each(function() {
                    // Only count visible and enabled coverage percentage inputs
                    if ($(this).is(':visible') && !$(this).is(':disabled')) {
                        var val = parseFloat($(this).val()) || 0;
                        console.log('   - Coverage', (coverageCount + 1) + ':', val + '%');
                        totalCoveragePercentage += val;
                        hasVisibleCoverage = true;
                        coverageCount++;
                    }
                });

                console.log('   Total Coverage Percentage:', totalCoveragePercentage.toFixed(5) + '%');
                console.log('   Has Visible Coverage:', hasVisibleCoverage);

                // Validate total is within valid range (only if new policy form has coverages)
                if ($('#new-policy').is(':visible') && hasVisibleCoverage && (totalCoveragePercentage < 0 || totalCoveragePercentage > 100)) {
                    console.error('   ✗ VALIDATION FAILED: Coverage percentage must be between 0-100%');
                    alertWarning('Total persentase coverage harus antara 0-100%. Saat ini: ' + totalCoveragePercentage.toFixed(5) + '%', '');
                    errors.push({
                        field: '.coverage-percentage',
                        message: 'Total persentase coverage harus antara 0-100%'
                    });
                } else {
                    console.log('   ✓ Coverage percentage validation passed');
                }

                // ============================================================
                // 4. VALIDATE MAIN INSURANCE CHECKBOX (OPTIONAL)
                // Main insurance checkbox is optional, no validation needed
                // ============================================================
                console.log('📌 Step 4: Main insurance checkbox validation (SKIPPED - Optional)');

                // ============================================================
                // 5. VALIDATE DATE FIELDS
                // Ensure ended_date and deadline_at are not before started_date
                // ============================================================
                console.log('📌 Step 5: Validating Date Fields...');
                var startedDateStr = null;
                var endedDateStr = $('#ended_date').val();

                // Get started_date from dropdown (existing policy) or input (new policy)
                if ($('#policy_id').val()) {
                    var selectedOption = $('#policy_id').find('option:selected');
                    startedDateStr = selectedOption.data('started-date');
                }
                if (!startedDateStr && $('#started_date').val()) {
                    startedDateStr = $('#started_date').val();
                }

                console.log('   Started Date:', startedDateStr);
                console.log('   Ended Date:', endedDateStr);

                // Validate ended_date is not before started_date
                if (startedDateStr && endedDateStr) {
                    var startDate = new Date(startedDateStr);
                    var endDate = new Date(endedDateStr);

                    if (endDate < startDate) {
                        console.error('   ✗ VALIDATION FAILED: End date cannot be before start date');
                        var endedDateField = document.getElementById('ended_date');
                        if (endedDateField) {
                            showFieldError(endedDateField, 'Periode Akhir Pertanggungan tidak boleh lebih awal dari Periode Awal Pertanggungan');
                            $(endedDateField).addClass('is-invalid');
                        }
                        errors.push({
                            field: '#ended_date',
                            message: 'Periode Akhir Pertanggungan tidak boleh lebih awal dari Periode Awal Pertanggungan'
                        });
                    } else {
                        console.log('   ✓ Date validation passed');
                        $('#ended_date').removeClass('is-invalid');
                    }
                } else {
                    console.log('   ⚠ Date validation skipped (missing dates)');
                }

                // ============================================================
                // VALIDATION SUMMARY
                // ============================================================
                console.log('🔍 ============================================');
                console.log('🔍 VALIDATION SUMMARY');
                console.log('🔍 ============================================');
                console.log('Total Errors:', errors.length);
                if (errors.length > 0) {
                    console.error('❌ VALIDATION FAILED - Errors:');
                    errors.forEach(function(error, index) {
                        console.error('   ' + (index + 1) + '.', error.field + ':', error.message);
                    });
                } else {
                    console.log('✅ ALL VALIDATIONS PASSED');
                }
                console.log('🔍 ============================================\n');

                // Return validation result
                return {
                    isValid: errors.length === 0,
                    errors: errors
                };
            },
            debug: true
        });

        // Validate percentage inputs to be between 0-100
        $(document).on('input keyup change blur', '.validate-percentage', function(e) {
            validateValues('.validate-percentage', null, 0, 100);
        });

        // Listen for policyLoaded event from Livewire (outside jQuery scope)
        document.addEventListener('livewire:init', () => {
            Livewire.on('policyLoaded', (event) => {
                const objectId = event.objectId || event[0]?.objectId || null;
                const coveragePercentage = event.coverage_percentage || event[0]?.coverage_percentage || 0;

                // Reinitialize select2 after policy data loaded
                setTimeout(function() {
                    if (typeof initCustomSelect2 === 'function') {
                        $('.create-select2').each(function() {
                            if ($(this).hasClass('select2-hidden-accessible')) {
                                $(this).select2('destroy');
                            }
                        });
                        initCustomSelect2(".create-select2");
                    }

                    // Trigger change on all coverage_percentage[] inputs
                    $('input[name="coverage_percentage[]"]').each(function() {
                        $(this).trigger('change');
                    });

                    // Trigger change on all insurance_percentage[] inputs
                    $('input[name="percentage[]"]').each(function() {
                        $(this).trigger('change');
                    });

                    // Add required on .required inputs inside policy-form-container
                    $('#policy-detail-form .required').each(function() {
                        $(this).prop("required", true).prop("disabled", false);
                    });

                }, 300);

            });
        });

        // Listen for changes and sum/validate to be between 0-100
        $(document).on('input change keyup blur', 'input[name^="coverage_percentage"]', function() {
            validateValues('input[name^="coverage_percentage"]', '.coverage_percentage_total', 0, 100, 5);
        });
        $(document).on('input change keyup blur', 'input[name^="percentage"]', function() {
            validateValues('input[name^="percentage"]', '#insurance_percentage_total', 0, 100, 2);
        });
        $(document).on('input change keyup blur', 'input[name^="tjh"]', function() {
            // Sum TJH hanya dari policy-form-container, bukan dari seluruh halaman
            sumInputValues($('#policy-form-container input[name^="tjh"]'), '#tjh_value');
        });
        $(document).on('input change keyup blur', 'input[name^="interest_value"]', function() {
            var $itemObject = $(this).closest('.item-object');
            var $objectValueInput = $itemObject.find('input[name="object_value_input"]');

            // Sum all interest values in this item
            sumInputValues($itemObject.find('input[name^="interest_value"]'), $objectValueInput);
        });

        // Real-time validation: Periode Akhir tidak lebih awal dari Periode Awal
        $(document).on('change', '#ended_date', function() {
            var startedDateStr = $('#started_date').val();
            var endedDateStr = $(this).val();

            if (startedDateStr && endedDateStr) {
                var startDate = new Date(startedDateStr);
                var endDate = new Date(endedDateStr);

                if (endDate < startDate) {
                    $(this).addClass('is-invalid');

                    // Tampilkan pesan error jika belum ada
                    if (!$(this).next('.invalid-feedback').length) {
                        $(this).after('<div class="invalid-feedback d-block">Periode Akhir tidak boleh lebih awal dari Periode Awal</div>');
                    }
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).next('.invalid-feedback').remove();
                }
            }
        });
    </script>
@endpush
