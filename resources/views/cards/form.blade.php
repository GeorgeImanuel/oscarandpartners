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
            // Initialize create-select2 (complex selects with "create new" functionality)
            initCustomSelect2(".create-select2");
            
            // Initialize form validation with custom validator
            initializeFormValidation({
                customValidator: function(form, fields) {
                    var errors = [];

                    // Calculate total insurance percentage
                    var totalInsurancePercentage = 0;
                    $(form).find('input[name="insurance_percentage[]"]').each(function() {
                        var val = parseFloat($(this).val()) || 0;
                        totalInsurancePercentage += val;
                    });

                    // Calculate total coverage percentage
                    var totalCoveragePercentage = 0;
                    $(form).find('input[name="coverage_percentage[]"]').each(function() {
                        var val = parseFloat($(this).val()) || 0;
                        totalCoveragePercentage += val;
                    });

                    // Validate insurance percentage total
                    if (totalInsurancePercentage < 0 || totalInsurancePercentage > 100) {
                        alertWarning('Total persentase asuransi harus antara 0-100%. Saat ini: ' + totalInsurancePercentage.toFixed(2) + '%', '');
                        errors.push({
                            field: '.insurance-percentage',
                            message: 'Total persentase asuransi harus antara 0-100%'
                        });
                    };

                    if (totalInsurancePercentage < 0 || totalInsurancePercentage > 100) {
                        alertWarning('Total persentase asuransi harus antara 0-100%. Saat ini: ' + totalPercentage.toFixed(2) + '%', '');
                    };

                    if (totalCoveragePercentage < 0 || totalCoveragePercentage > 100) {
                        alertWarning('Total persentase coverage harus antara 0-100%. Saat ini: ' + totalPercentage.toFixed(2) + '%', '');
                    };

                    // Check if at least one is-main-checkbox is checked
                    var hasMainInsurance = false;
                    var hasInsurances = false;

                    $('#new-policy .is-main-checkbox').each(function() {
                        if ($(this).closest('.item').is(':visible')) {
                            hasInsurances = true;
                            if ($(this).is(':checked')) {
                                hasMainInsurance = true;
                            }
                        }
                    });

                    // Only validate if new policy form is visible and has insurance items
                    if ($('#new-policy').is(':visible') && hasInsurances && !hasMainInsurance) {
                        alertWarning('Minimal satu asuransi harus dipilih sebagai asuransi utama', '');
                        errors.push({
                            field: '.is-main-checkbox',
                            message: 'Minimal satu asuransi harus dipilih sebagai asuransi utama'
                        });
                    }

                    return {
                        isValid: errors.length === 0,
                        errors: errors
                    };
                },
            });

            // Validate percentage inputs to be between 0-100
            $(document).on('input keyup change blur', '.validate-percentage', function(e) {
                validateValues('.validate-percentage', null, 0, 100);
            });
        </script>
    @endpush
@endsection
