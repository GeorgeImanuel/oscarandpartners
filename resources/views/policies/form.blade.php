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
        <form class="needs-validation"
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
                            for="policy_id">Nomor Polis</label>
                        <!-- Note: value contains policy_number (string), not numeric policy ID -->
                        <input type="text"
                            name="policy_number"
                            class="form-control @error('policy_number') is-invalid @enderror"
                            value="{{ old('policy_number', $policy->policy_number ?? '') }}"
                            placeholder="Masukkan Nomor Polis"
                            required>
                        <select id="policy_id"
                            class="form-control create-select2 @error('policy_id') is-invalid @enderror"
                            data-placeholder="Pilih Polis"
                            name="policy_id"
                            autocomplete="off"
                            required>
                            <option value=""></option>
                            @foreach ($policies as $policy)
                                <option data-currency="{{ $policy->currency_code }}"
                                    data-started-date="{{ $policy->started_date ? $policy->started_date->format('Y-m-d') : '' }}"
                                    data-policy-id="{{ $policy->id }}"
                                    value="{{ $policy->policy_number }}"
                                    {{ isset($card) ? ($card->policy->policy_number == $policy->policy_number ? 'selected' : '') : (old('policy_id') == $policy->policy_number ? 'selected' : '') }}>
                                    {{ $policy->policy_number }} - {{ $policy->policyObjects->first()?->object?->name ?? 'No Object' }} - {{ $policy->policyObjects->first()?->object?->keterangan ?? '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('policy_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- New Policy Input --}}
            <div id="policy-form-container">
                @livewire('policy-form')
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

        // // Initialize form validation with custom validator
        initializeFormValidation({
            onSuccess: function(form) {
                // Sync object_value to hidden field for proper submission
                var numericValue = parseFloat($('#object_value').val()) || 0;
                $('#object_value_raw').val(numericValue);

                if (typeof window.syncTaxToHiddenInputs === 'function') {
                    window.syncTaxToHiddenInputs();
                }
            },
            customValidator: function(form, fields) {
                var errors = [];

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

                $(form).find('input[name="insurance_percentage[]"]').each(function() {
                    // Only count visible and enabled insurance percentage inputs
                    if ($(this).is(':visible') && !$(this).is(':disabled')) {
                        var val = parseFloat($(this).val()) || 0;
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

                $(form).find('input[name="coverage_percentage[]"]').each(function() {
                    // Only count visible and enabled coverage percentage inputs
                    if ($(this).is(':visible') && !$(this).is(':disabled')) {
                        var val = parseFloat($(this).val()) || 0;
                        totalCoveragePercentage += val;
                        hasVisibleCoverage = true;
                    }
                });

                // Validate total is within valid range (only if new policy form has coverages)
                if ($('#new-policy').is(':visible') && hasVisibleCoverage && (totalCoveragePercentage < 0 || totalCoveragePercentage > 100)) {
                    alertWarning('Total persentase coverage harus antara 0-100%. Saat ini: ' + totalCoveragePercentage.toFixed(5) + '%', '');
                    console.warn('Coverage percentage validation failed. Total:', totalCoveragePercentage);
                    errors.push({
                        field: '.coverage-percentage',
                        message: 'Total persentase coverage harus antara 0-100%'
                    });
                }

                // ============================================================
                // 4. VALIDATE MAIN INSURANCE CHECKBOX
                // At least one insurance must be marked as main insurance
                // ============================================================
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

                // Validate main insurance is selected (only if new policy form has insurances)
                if ($('#new-policy').is(':visible') && hasInsurances && !hasMainInsurance) {
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

                // Return validation result
                return {
                    isValid: errors.length === 0,
                    errors: errors
                };
            },
            debug: true
        });

        // // Validate percentage inputs to be between 0-100
        // $(document).on('input keyup change blur', '.validate-percentage', function(e) {
        //     validateValues('.validate-percentage', null, 0, 100);
        // });

        // // Listen to policy_id dropdown change and update Livewire component
        // $(document).on('change', '#policy_id', function() {
        //     // Force as string - this is policy_number, NOT numeric policy ID
        //     var policyValue = String($(this).val() || '');
        //     var selectedOption = $(this).find('option:selected');

        //     // Check if this is a new tag (user typed a new policy number)
        //     var isNewTag = selectedOption.data('select2-tag') === true;

        //     // Get actual policy ID from data attribute (for existing policies only)
        //     var actualPolicyId = selectedOption.data('policy-id');

        //     // Check if this is existing policy (has data-policy-id attribute)
        //     var isExistingPolicy = policyValue && actualPolicyId && !isNewTag;

        //     // Toggle policy-form-container visibility
        //     if (policyValue) {
        //         $('#policy-form-container').slideDown();

        //         // Reinitialize select2 after container is shown
        //         setTimeout(function() {
        //             if (typeof initCustomSelect2 === 'function') {
        //                 $('.create-select2').not('#policy_id').each(function() {
        //                     if ($(this).hasClass('select2-hidden-accessible')) {
        //                         $(this).select2('destroy');
        //                     }
        //                 });
        //                 initCustomSelect2(".create-select2");
        //             }
        //         }, 300);
        //     } else {
        //         $('#policy-form-container').slideUp();
        //     }

        //     var currencyCode = selectedOption.data('currency');
        //     updateCurrencyLabel(currencyCode);

        //     // Find the Livewire component and update policyId
        //     var container = document.querySelector('#policy-details-container [wire\\:id]');
        //     if (container && typeof window.Livewire !== 'undefined') {
        //         var livewireComponent = window.Livewire.find(container.getAttribute('wire:id'));
        //         if (livewireComponent) {
        //             livewireComponent.set('policyId', isExistingPolicy ? actualPolicyId : null);
        //         }
        //     }

        //     // Only load existing policy data, not for new tags
        //     if (isExistingPolicy) {
        //         Livewire.dispatch('refreshPolicyForm', {
        //             policyId: actualPolicyId
        //         });
        //     } else {
        //         // Clear form for new policy
        //         Livewire.dispatch('refreshPolicyForm', {
        //             policyId: null
        //         });

        //         // Clear all form fields in policy-form-container
        //         $('#policy-form-container input[type="date"]').val('');
        //         $('#policy-form-container input[type="number"]').val('');
        //         $('#policy-form-container select').val('').trigger('change');

        //         // Reset totals
        //         $('.coverage_percentage_total').text('0.00000');
        //         $('#insurance_percentage_total').text('0.00');
        //     }
        // });

        // // Listen for policyLoaded and objectLoaded events from Livewire (outside jQuery scope)
        // document.addEventListener('livewire:init', () => {
        //     Livewire.on('policyLoaded', (event) => {
        //         const objectId = event.objectId || event[0]?.objectId || null;
        //         const coveragePercentage = event.coverage_percentage || event[0]?.coverage_percentage || 0;

        //         // console.log('Policy loaded:', event);

        //         var rate = parseFloat(coveragePercentage) * 100;
        //         $('#premi_percentages').val(rate).trigger('change');


        //         // Reinitialize select2 after policy objects are loaded
        //         setTimeout(function() {
        //             if (typeof initCustomSelect2 === 'function') {
        //                 $('.create-select2').not('#policy_id').each(function() {
        //                     if ($(this).hasClass('select2-hidden-accessible')) {
        //                         $(this).select2('destroy');
        //                     }
        //                 });
        //                 initCustomSelect2(".create-select2");
        //             }

        //             // Trigger change on all coverage_percentage[] inputs
        //             $('input[name="coverage_percentage[]"]').each(function() {
        //                 $(this).trigger('change');
        //             });

        //             // Trigger change on all insurance_percentage[] inputs
        //             $('input[name="insurance_percentage[]"]').each(function() {
        //                 $(this).trigger('change');
        //             });

        //             // Add required on .required inputs inside policy-form-container
        //             $('#policy-detail-form .required').each(function() {
        //                 $(this).prop("required", true).prop("disabled", false);
        //             });

        //         }, 300);

        //     });
        // });

        // // Listen for changes and sum/validate to be between 0-100
        // $(document).on('input change keyup blur', 'input[name^="coverage_percentage"]', function() {
        //     validateValues('input[name^="coverage_percentage"]', '.coverage_percentage_total', 0, 100, 5);
        // });
        // $(document).on('input change keyup blur', 'input[name^="insurance_percentage"]', function() {
        //     validateValues('input[name^="insurance_percentage"]', '#insurance_percentage_total', 0, 100, 2);
        // });
        // $(document).on('input change keyup blur', 'input[name^="tjh"]', function() {
        //     // Sum TJH hanya dari policy-form-container, bukan dari seluruh halaman
        //     sumInputValues($('#policy-form-container input[name^="tjh"]'), '#tjh_value');
        // });
        // $(document).on('input change keyup blur', 'input[name^="interest_value"]', function() {
        //     var $itemObject = $(this).closest('.item-object');
        //     var $objectValueInput = $itemObject.find('input[name="object_value_input"]');

        //     // Sum all interest values in this item
        //     sumInputValues($itemObject.find('input[name^="interest_value"]'), $objectValueInput);
        // });

        // // Real-time validation: Periode Akhir tidak lebih awal dari Periode Awal
        // $(document).on('change', '#ended_date', function() {
        //     var startedDateStr = $('#started_date').val();
        //     var endedDateStr = $(this).val();

        //     if (startedDateStr && endedDateStr) {
        //         var startDate = new Date(startedDateStr);
        //         var endDate = new Date(endedDateStr);

        //         if (endDate < startDate) {
        //             $(this).addClass('is-invalid');

        //             // Tampilkan pesan error jika belum ada
        //             if (!$(this).next('.invalid-feedback').length) {
        //                 $(this).after('<div class="invalid-feedback d-block">Periode Akhir tidak boleh lebih awal dari Periode Awal</div>');
        //             }
        //         } else {
        //             $(this).removeClass('is-invalid');
        //             $(this).next('.invalid-feedback').remove();
        //         }
        //     }
        // });
    </script>
@endpush
