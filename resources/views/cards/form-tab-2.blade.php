<div id="step-2"
    class="setup-content">
    <div class="card  mb-3 p-3 shadow-sm">
        <div class="row">
            <div class="col-md-12 mb-3">
                <h5 class="text-primary">Polis</h5>
                <hr class="separator">
            </div>
            <div class="col-md-12 mb-3">
                <label class="form-label"
                    for="policy_id">Nomor Polis</label>
                <!-- Note: value contains policy_number (string), not numeric policy ID -->
                <select id="policy_id"
                    class="form-control create-select2 @error('policy_id') is-invalid @enderror"
                    data-placeholder="Pilih Polis"
                    name="policy_id"
                    wire:ignore
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
                @error('policy_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    {{-- New Policy Input --}}
    <div id="policy-form-container"
        style="display:none;">
        @livewire('policy-form')
    </div>
</div>

@push('scripts')
    <script>
        // Initialize policy-form-container visibility on page load
        $(document).ready(function() {
            var policyValue = $('#policy_id').val();
            if (policyValue) {
                $('#policy-form-container').slideDown();
            } else {
                $('#policy-form-container').slideUp();
            }
        });

        // Listen to policy_id dropdown change and update Livewire component
        $(document).on('change', '#policy_id', function() {
            // Force as string - this is policy_number, NOT numeric policy ID
            var policyValue = String($(this).val() || '');
            var selectedOption = $(this).find('option:selected');
            
            // Check if this is a new tag (user typed a new policy number)
            var isNewTag = selectedOption.data('select2-tag') === true;
            
            // Get actual policy ID from data attribute (for existing policies only)
            var actualPolicyId = selectedOption.data('policy-id');
            
            // Check if this is existing policy (has data-policy-id attribute)
            var isExistingPolicy = policyValue && actualPolicyId && !isNewTag;

            // Toggle policy-form-container visibility
            if (policyValue) {
                $('#policy-form-container').slideDown();

                // Reinitialize select2 after container is shown
                setTimeout(function() {
                    if (typeof initCustomSelect2 === 'function') {
                        initCustomSelect2(".create-select2");
                    }
                }, 300);
            } else {
                $('#policy-form-container').slideUp();
            }

            var currencyCode = selectedOption.data('currency');
            updateCurrencyLabel(currencyCode);
            update_payment();

            // Find the Livewire component and update policyId
            var container = document.querySelector('#policy-details-container [wire\\:id]');
            if (container && typeof window.Livewire !== 'undefined') {
                var livewireComponent = window.Livewire.find(container.getAttribute('wire:id'));
                if (livewireComponent) {
                    livewireComponent.set('policyId', isExistingPolicy ? actualPolicyId : null);
                }
            }
            
            // Only load existing policy data, not for new tags
            if (isExistingPolicy) {
                Livewire.dispatch('refreshPolicyForm', {
                    policyId: actualPolicyId
                });
            } else {
                // Clear form for new policy
                Livewire.dispatch('refreshPolicyForm', {
                    policyId: null
                });
                
                // Clear all form fields in policy-form-container
                $('#policy-form-container input[type="date"]').val('');
                $('#policy-form-container input[type="number"]').val('');
                $('#policy-form-container select').val('').trigger('change');
                
                // Reset totals
                $('.coverage_percentage_total').text('0.00');
                $('#insurance_percentage_total').text('0.00');
            }
        });

        // Listen for policyLoaded and objectLoaded events from Livewire (outside jQuery scope)
        document.addEventListener('livewire:init', () => {
            Livewire.on('policyLoaded', (event) => {
                const objectId = event.objectId || event[0]?.objectId || null;
                const coveragePercentage = event.coverage_percentage || event[0]?.coverage_percentage || 0;

                // console.log('Policy loaded:', event);

                var rate = parseFloat(coveragePercentage) * 100;
                $('#premi_percentages').val(rate).trigger('change');

                update_payment();

                // Reinitialize select2 after policy objects are loaded
                setTimeout(function() {
                    if (typeof initCustomSelect2 === 'function') {
                        initCustomSelect2(".create-select2");
                    }

                    // Trigger change on all coverage_percentage[] inputs
                    $('input[name="coverage_percentage[]"]').each(function() {
                        $(this).trigger('change');
                    });

                    // Trigger change on all insurance_percentage[] inputs
                    $('input[name="insurance_percentage[]"]').each(function() {
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
            validateValues('input[name^="coverage_percentage"]', '.coverage_percentage_total', 0, 100);
        });
        $(document).on('input change keyup blur', 'input[name^="insurance_percentage"]', function() {
            validateValues('input[name^="insurance_percentage"]', '#insurance_percentage_total', 0, 100);
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

            // After updating object_value_input, sum all object_value_inputs HANYA dari policy-form-container
            sumInputValues($('#policy-form-container input[name="object_value_input"]'), '#object_value');

            // Trigger update_payment if it exists
            if (typeof update_payment === 'function') {
                update_payment();
            }
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
