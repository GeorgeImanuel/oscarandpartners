@extends('layouts.master')

@section('title')
    Create Kartu Produksi
@endsection

@push('css')
    <link rel="stylesheet"
        type="text/css"
        href="{{ asset('assets/css/date-picker.css') }}">
    <style>
        /* CSS Khusus Tabel Preview Payment */
        .table-manual {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            color: #000;
        }

        .table-manual th,
        .table-manual td {
            border: 1px solid #dee2e6;
            padding: 8px 10px;
            vertical-align: middle;
        }

        .table-manual thead th {
            background-color: #f8f9fa;
            font-weight: bold;
            text-align: center;
        }
    </style>
@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <h3>{{ isset($card) ? 'Ubah' : 'Tambah' }} Kartu Produksi</h3>
        @endslot
        <li class="breadcrumb-item"><a href="{{ route('cards.index') }}">Kartu Produksi</a></li>
        <li class="breadcrumb-item active">{{ isset($card) ? 'Ubah' : 'Tambah' }}</li>
    @endcomponent

    <div class="container-fluid">
        <form class="needs-validation"
            action="{{ isset($card) ? route('cards.update', $card->id) : route('cards.store') }}"
            method="POST"
            novalidate>
            @csrf
            @if (isset($card))
                @method('PUT')
            @endif

            {{-- === CARD 1: KHUSUS WIZARD (NAVIGASI ATAS) === --}}
            <div class="card mb-2 shadow-sm">
                <div class="card-body mt-4 pb-0">
                    <div class="stepwizard">
                        <div class="stepwizard-row setup-panel">
                            <div class="stepwizard-step">
                                <a class="btn btn-primary"
                                    href="#step-1">1</a>
                                <p>Detail Kartu Produksi</p>
                            </div>
                            <div class="stepwizard-step">
                                <a class="btn btn-light"
                                    href="#step-2">2</a>
                                <p>Detail Polis & Objek</p>
                            </div>
                            <div class="stepwizard-step">
                                <a class="btn btn-light"
                                    href="#step-3">3</a>
                                <p>Detail Premi & Komisi</p>
                            </div>
                            <div class="stepwizard-step">
                                <a class="btn btn-light"
                                    href="#step-4">4</a>
                                <p>Payment</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- START STEP 1: CARD INFO --}}
            @include('cards.form-tab-1')
            {{-- END STEP 1 --}}

            {{-- START STEP 2: POLIS & OBJEK --}}
            @include('cards.form-tab-2')
            {{-- END STEP 2 --}}

            {{-- START STEP 3: PARAMETER & PERHITUNGAN --}}
            @include('cards.form-tab-3')
            {{-- END STEP 3 --}}

            {{-- START STEP 4: PAYMENT CONFIGURATION --}}
            @include('cards.form-tab-4')
            {{-- END STEP 4 --}}

            <div class="card mb-3 p-3 shadow-sm">
                <div class="row no-gutters mt-2">
                    <div class="col-md">
                        <a class="btn btn-light"
                            href="{{ route('cards.index') }}">Kembali</a>
                    </div>
                    <div class="col-md-auto">
                        <span class="btn btn-outline-primary ms-3 mb-2 stepwizard-prev">Sebelumnya</span>
                        <span class="btn btn-primary ms-3 mb-2 stepwizard-next">Selanjutnya</span>
                        <button class="btn btn-primary ms-3 mb-2 stepwizard-submit"
                            type="submit"
                            style="display: none;">Simpan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('css')
        <link rel="stylesheet"
            type="text/css"
            href="{{ asset('assets/css/select2.css') }}">
    @endpush

    @push('scripts')
        <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
        <script src="{{ asset('assets/js/select2/select2-custom.js') }}"></script>
        <script src="{{ asset('assets/js/form-validation.js') }}"></script>
        <script src="{{ asset('assets/js/format.js') }}"></script>
        <script src="{{ asset('assets/js/formula.js') }}"></script>
        <script src="{{ asset('assets/js/multiple-item.js') }}"></script>
        <script src="{{ asset('assets/js/form-wizard/form-wizard-two.js') }}"></script>

        <script>
            window.toggleNegativeWarning = function(element) {
                var val = parseNumber($(element).val()) || 0;
                
                // --- LOGIC PENCARIAN ELEMEN WARNING YANG LEBIH KUAT ---
                // Coba cari warning tepat di sebelah input (jika tidak ada input-group)
                var $warningMsg = $(element).next('.negative-warning');
                
                // Jika tidak ketemu, coba cari di sebelah parentnya (jika ada input-group)
                if ($warningMsg.length === 0) {
                    $warningMsg = $(element).parent().next('.negative-warning');
                }
                
                // Jika masih tidak ketemu, cari di dalam container kolom (col-md-...)
                if ($warningMsg.length === 0) {
                    $warningMsg = $(element).closest('div[class^="col-"]').find('.negative-warning');
                }
                // -------------------------------------------------------

                if (val < 0) {
                    // Jika Negatif: Tampilkan & beri warna
                    $warningMsg.stop().slideDown(200);
                    $(element).addClass('border-warning text-warning fw-bold'); 
                } else {
                    // Jika Aman: Sembunyikan
                    $warningMsg.stop().slideUp(200);
                    $(element).removeClass('border-warning text-warning fw-bold');
                }
            };

            $(document).ready(function() {
                // 2. Listener Otomatis untuk Input User (Ketik Manual)
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

            // Initialize create-select2 (complex selects with "create new" functionality)
            initCustomSelect2(".create-select2");
            
            // Initialize form validation with custom validator
            initializeFormValidation({
                onSuccess: function(form) {
                    // Sync object_value to hidden field for proper submission
                    var numericValue = parseNumber($('#object_value').val()) || 0;
                    $('#object_value_raw').val(numericValue);
                    
                    if (typeof window.syncTaxToHiddenInputs === 'function') {
                        window.syncTaxToHiddenInputs();
                    }
                },
                customValidator: function(form, fields) {
                    var errors = [];

                    var isTravelPro = false;
                    $(form).find('select[name^="object_occupation_id"] option:selected').each(function() {
                        if ($(this).text().trim() === 'TravelPro Ins') {
                            isTravelPro = true;
                        }
                    });

                    // ============================================================
                    // 1. VALIDATE SELECT2 FIELDS
                    // Ensure all required select2 dropdowns have values selected
                    // ============================================================
                    $(form).find('select.create-select2.required').each(function() {
                        var $select = $(this);
                        
                        // Skip disabled or hidden fields
                        if ($select.is(':disabled') || !$select.is(':visible') || $select[0].offsetParent === null) {
                            return;
                        }
                        
                        // Skip if inside hidden container (e.g., object forms)
                        if ($select.closest('.object-forms-container').length && $select.closest('.object-forms-container').is(':hidden')) {
                            return;
                        }

                        if (isTravelPro) {
                            if (fieldName === 'coverage_code[]' || fieldName === 'interest_id[]') {
                                $select.removeClass('is-invalid');
                                return; // Lewati pengecekan "harus diisi" di bawah
                            }
                        }
                        
                        // Get value from select2 or regular select
                        var value = $select.val();
                        if ($select.hasClass('select2-hidden-accessible')) {
                            value = $select.select2('val');
                        }
                        
                        // Validate if value is empty
                        if (!value || value === '' || value === null) {
                            var label = $select.closest('.col-md-6, .col-md-5, .col-md-4, .col-md-3, [class*="col-"]')
                                .find('label').first().text().replace('*', '').trim();
                            
                            $select.addClass('is-invalid');
                            console.warn('Field validation failed:', $select.attr('name'), '- Label:', label);
                            
                            errors.push({
                                field: $select.attr('name'),
                                message: (label || 'Field') + ' harus diisi'
                            });
                        } else {
                            $select.removeClass('is-invalid');
                        }
                    });

                    // ============================================================
                    // 2. VALIDATE INSURANCE PERCENTAGE
                    // Total insurance percentage must equal 100% if new policy form is visible
                    // ============================================================
                    var totalInsurancePercentage = 0;
                    var hasVisibleInsurance = false;
                    
                    $(form).find('input[name="insurance_percentage[]"], input[name="percentage[]"]').each(function() {
                        // Only count visible and enabled insurance percentage inputs
                        if ($(this).is(':visible') && !$(this).is(':disabled')) {
                            var val = parseNumber($(this).val()) || 0;
                            totalInsurancePercentage += val;
                            hasVisibleInsurance = true;
                        }
                    });

                    // Validate total equals 100% (only if new policy form has insurance items)
                    if ($('#new-policy').is(':visible') && hasVisibleInsurance && totalInsurancePercentage !== 100) {
                        alertWarning('Total persentase asuransi harus 100%. Saat ini: ' + totalInsurancePercentage.toFixed(2) + '%', '');
                        console.warn('Insurance percentage validation failed. Total:', totalInsurancePercentage);
                        errors.push({
                            field: '.insurance-percentage',
                            message: 'Total persentase asuransi harus 100%'
                        });
                    }

                    // ============================================================
                    // 3. VALIDATE COVERAGE PERCENTAGE
                    // Total coverage percentage must be between 0-100%
                    // ============================================================
                    var totalCoveragePercentage = 0;
                    var hasVisibleCoverage = false;
                    var hasObjectCoverage = false;
                    
                    $(form).find('input[name="coverage_percentage[]"]').each(function() {
                        if ($(this).is(':visible') && !$(this).is(':disabled')) {
                            var val = parseNumber($(this).val()) || 0;
                            totalCoveragePercentage += val;
                            hasVisibleCoverage = true;
                        }
                    });

                    $(form).find('input[name^="object_coverage_percentage"]').each(function() {
                        if ($(this).is(':visible') && !$(this).is(':disabled')) {
                            var val = parseNumber($(this).val()) || 0;
                            if (val > 0) {
                                hasObjectCoverage = true;
                            }
                        }
                    });

                    // === PERBAIKAN LOGIKA KHUSUS NON-TRAVELPRO ===
                    // Jika BUKAN TravelPro dan form input polis baru sedang terbuka
                    if (!isTravelPro && $('#new-policy').is(':visible')) {
                        
                        // Skenario 1: User menghapus semua baris coverage (kosong sama sekali)
                        if (!hasVisibleCoverage && !hasObjectCoverage) {
                            alertWarning('Minimal harus ada 1 Rincian Coverage (di polis atau per obyek)!', '');
                            console.warn('Coverage validation failed: No coverage added.');
                            errors.push({
                                field: '.coverage-item',
                                message: 'Minimal harus ada 1 Rincian Coverage di polis atau per obyek'
                            });
                        } 
                        // Skenario 2: Baris coverage ada, valid jika total 0-100
                        else if (hasVisibleCoverage && (totalCoveragePercentage < 0 || totalCoveragePercentage > 100)) {
                            alertWarning('Total persentase coverage harus di antara 0% sampai 100%. Saat ini: ' + totalCoveragePercentage.toFixed(5) + '%', '');
                            console.warn('Coverage percentage validation failed. Total:', totalCoveragePercentage);
                            errors.push({
                                field: '.coverage-percentage',
                                message: 'Total persentase coverage tidak valid'
                            });
                        }
                    }

                    // ============================================================
                    // 4. VALIDATE MAIN INSURANCE CHECKBOX
                    // At least one insurance must be marked as main insurance
                    // ============================================================
                    var hasMainInsurance = false;
                    var visibleInsuranceCount = 0;

                    $('#new-policy .is-main-checkbox').each(function() {
                        if ($(this).closest('.item').is(':visible')) {
                            visibleInsuranceCount++;
                            if ($(this).is(':checked')) {
                                hasMainInsurance = true;
                            }
                        }
                    });

                    // Wajib leader hanya jika asuransi lebih dari satu.
                    if ($('#new-policy').is(':visible') && visibleInsuranceCount > 1 && !hasMainInsurance) {
                        alertWarning('Minimal satu asuransi harus dipilih sebagai asuransi utama', '');
                        console.warn('Main insurance validation failed');
                        errors.push({
                            field: '.is-main-checkbox',
                            message: 'Minimal satu asuransi harus dipilih sebagai asuransi utama'
                        });
                    }

                    // ============================================================
                    // 5. VALIDATE DATE FIELDS
                    // Ensure ended_date and deadline_at are not before started_date
                    // ============================================================
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

                    // Validate ended_date is not before started_date
                    if (startedDateStr && endedDateStr) {
                        var startDate = new Date(startedDateStr);
                        var endDate = new Date(endedDateStr);

                        if (endDate < startDate) {
                            var endedDateField = document.getElementById('ended_date');
                            if (endedDateField) {
                                showFieldError(endedDateField, 'Periode Akhir Pertanggungan tidak boleh lebih awal dari Periode Awal Pertanggungan');
                                $(endedDateField).addClass('is-invalid');
                            }
                            console.warn('End date validation failed');
                            errors.push({
                                field: '#ended_date',
                                message: 'Periode Akhir Pertanggungan tidak boleh lebih awal dari Periode Awal Pertanggungan'
                            });
                        } else {
                            $('#ended_date').removeClass('is-invalid');
                        }
                    }

                    // Validate deadline_at is not before started_date
                    var deadlineStr = $('#deadline_at').val();
                    if (startedDateStr && deadlineStr) {
                        var startDate = new Date(startedDateStr);
                        var deadlineDate = new Date(deadlineStr);

                        if (deadlineDate < startDate) {
                            var deadlineField = document.getElementById('deadline_at');
                            if (deadlineField) {
                                showFieldError(deadlineField, 'Tanggal Deadline tidak boleh lebih awal dari Periode Awal Pertanggungan');
                                $(deadlineField).addClass('is-invalid');
                            }
                            console.warn('Deadline validation failed');
                            errors.push({
                                field: '#deadline_at',
                                message: 'Tanggal Deadline tidak boleh lebih awal dari Periode Awal Pertanggungan'
                            });
                        } else {
                            $('#deadline_at').removeClass('is-invalid');
                        }
                    }
                    
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
        </script>
    @endpush
@endsection
