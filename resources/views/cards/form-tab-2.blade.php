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
                <select id="policy_id"
                    class="form-control create-select2 @error('policy_id') is-invalid @enderror"
                    {{-- data-existing-name="policy_id"
                    data-new-name="policy_number" --}}
                    data-placeholder="Pilih Polis"
                    name="policy_id"
                    wire:ignore
                    required>
                    <option value=""></option>
                    @foreach ($policies as $policy)
                        <option value="{{ $policy->id }}"
                            data-currency="{{ $policy->currency_code }}"
                            data-started-date="{{ $policy->started_date ? $policy->started_date->format('Y-m-d') : '' }}"
                            {{ isset($card) ? ($card->policy_id == $policy->id ? 'selected' : '') : (old('policy_id') == $policy->id ? 'selected' : '') }}>
                            {{ $policy->policy_number }} - {{ $policy->object->name }}
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
    <div id="new-policy-container"
        style="display:none;">
        @livewire('policy-form')
    </div>
</div>

@push('scripts')
    <script>
        // Initialize new-policy-container visibility on page load
        $(document).ready(function() {
            var policyId = $('#policy_id').val();
            if (policyId) {
                $('#new-policy-container').slideDown();
            } else {
                $('#new-policy-container').slideUp();
            }
        });

        // Listen to policy_id dropdown change and update Livewire component
        $(document).on('change', '#policy_id', function() {
            var policyId = $(this).val();

            // Toggle new-policy-container visibility
            if (policyId) {
                $('#new-policy-container').slideDown();

                // Reinitialize select2 after container is shown
                setTimeout(function() {
                    if (typeof initCustomSelect2 === 'function') {
                        initCustomSelect2(".create-select2");
                    }
                }, 300);
            } else {
                $('#new-policy-container').slideUp();
            }

            var selectedOption = $(this).find('option:selected');
            var currencyCode = selectedOption.data('currency');
            updateCurrencyLabel(currencyCode);
            update_payment();

            // Find the Livewire component and update policyId
            var container = document.querySelector('#policy-details-container [wire\\:id]');
            if (container && typeof window.Livewire !== 'undefined') {
                var livewireComponent = window.Livewire.find(container.getAttribute('wire:id'));
                if (livewireComponent) {
                    livewireComponent.set('policyId', policyId);
                }
            }
        });

        // Listen to object_id dropdown change and update Livewire component
        $(document).on('change', 'select[name=object_id]', function() {
            var attrName = $(this).attr('name');
            var objectId = $(this).val();

            var objectId = $(this).val();
            console.log('Object ID changed:', objectId);
            // Find the Livewire component and update objectId
            var container = $('#object-details-container [wire\\:id]')[0];
            if (container && typeof window.Livewire !== 'undefined') {
                console.log('Found Livewire container:', container);
                var livewireComponent = window.Livewire.find(container.getAttribute('wire:id'));
                if (livewireComponent) {
                    livewireComponent.set('objectId', objectId);
                }
                $('#object_ready').val(0);
                var $nextObjectContainer = $(this).closest('.item').find('.object-details-container');
                $nextObjectContainer.html('<div class="alert alert-info" role="alert"> <div class="d-flex align-items-center"> <div class="spinner-border spinner-border-sm me-2" role="status"> <span class="visually-hidden">Loading...</span> </div> <span>Memuat detail objek...</span> </div> </div>');
                var checkReady = setInterval(function() {
                    if ($('#object_ready').val() == 1) {
                        clearInterval(checkReady);
                        var $sourceContainer = $('#object-details-container');
                        if ($nextObjectContainer.length && $sourceContainer.length) {
                            $nextObjectContainer.html($sourceContainer.html());
                        }
                    }
                }, 100);
            }
        });

        // Listen to object_id dropdown change and update Livewire component
        // $(document).on('change', '.object-select', function() {
        //     var row = $(this).closest('.object-row');
        //     var selectedOption = $(this).find(':selected');
        //     var value = parseFloat(selectedOption.data('value')) || 0;

        //     // Update tampilan nilai per baris
        //     row.find('.object-value-display').val(formatRupiah(value));

        //     // Hitung total semua objek
        //     calculateTotalObjectValue();
        // });

        // Listen to policy_id dropdown change and refresh Livewire policy-form component
        $(document).on('change', '#policy_id', function() {
            var policyId = $(this).val();
            if (policyId) {
                Livewire.dispatch('refreshPolicyForm', {
                    policyId: policyId
                });
            } else {
                Livewire.dispatch('refreshPolicyForm', {
                    policyId: null
                });
            }
        });



        // function calculateTotalObjectValue() {
        //     var totalValue = 0;

        //     // Loop semua dropdown objek yang dipilih
        //     $('.object-select').each(function() {
        //         var selectedOption = $(this).find(':selected');
        //         var value = parseFloat(selectedOption.data('value')) || 0;
        //         totalValue += value;
        //     });

        //     // Update Tampilan Total (Text)
        //     $('#total-object-value').text(formatRupiah(totalValue));

        //     // Update Input Hidden/Readonly yang dipakai update_payment()
        //     // Pastikan di HTML ada input dengan id="object_value"
        //     $('#object_value').val(totalValue);

        //     // Trigger hitung ulang payment
        //     update_payment();

        //     if (totalValue > 0) $('#object-value-info').show();
        //     else $('#object-value-info').hide();
        // }

        // Listen for policyLoaded and objectLoaded events from Livewire (outside jQuery scope)
        document.addEventListener('livewire:init', () => {
            Livewire.on('policyLoaded', (event) => {
                const objectId           = event.objectId || event[0]?.objectId || null;
                const coveragePercentage = event.coverage_percentage || event[0]?.coverage_percentage || 0;

                console.log('Policy loaded:', event);
                // Update field Harga Pertanggungan
                // $('#object_value').val(totalObjectValue);

                // // Update display (format Rupiah)
                // $('#total-object-value').text(formatRupiah(totalObjectValue));

                // // Show/hide info based on value
                // if (totalObjectValue > 0) {
                //     $('#object-value-info').show();
                // } else {
                //     $('#object-value-info').hide();
                // }

                var rate = parseFloat(coveragePercentage) * 100;
                $('#premi_percentages').val(rate).trigger('change');

                update_payment();

                // Reinitialize select2 after policy objects are loaded
                setTimeout(function() {
                    if (typeof initCustomSelect2 === 'function') {
                        console.log('Reinitializing select2 for .create-select2');
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
                }, 300);

            });


            Livewire.on('objectLoaded', (event) => {
                const object = event.object || event[0]?.object || 0;

                if (object) {
                    $('#object_value').val(object.object_value);

                    var tjh = object.tjh || 0;
                    $('#tjh_value').val(tjh).trigger('change');
                }
                $('#object_ready').val(1);
                // console.log('Object received:', object.object_value);
                sumInputValues('input[name="object_value_input"]', '#object_value');

                update_payment(); // Trigger calculation when policy is loaded

            });

            Livewire.on('insurancesLoaded', (event) => {
                const insurances = event.insurances || event[0]?.insurances || [];
                const container = $('#insurance-list-container');
                container.empty();

                if (insurances.length > 0) {
                    insurances.forEach((insurance, index) => {
                        const html = `
                                <div class="row g-2 align-items-center mb-3 insurance-item">
                                    <div class="col-md-5">
                                        <label class="small text-muted mb-0">Asuransi</label>
                                        <input type="text" class="form-control form-control-sm bg-white" 
                                            value="${insurance.insurance_name}" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="small text-muted mb-0">Share</label>
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control text-center bg-white" 
                                                value="${(insurance.percentage * 100).toFixed(2)}" readonly>
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="small text-muted mb-0 fw-bold">Pajak (Input)</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text currency-label">Rp</span>
                                            <input type="number" 
                                                name="insurance_tax[${insurance.insurance_id}]"
                                                class="form-control form-control-sm insurance-tax-per-item" 
                                                data-insurance-id="${insurance.insurance_id}" 
                                                placeholder="0"
                                                value="${insurance.tax || 0}">
                                        </div>
                                    </div>
                                </div>
                                ${index < insurances.length - 1 ? '<hr class="my-2 text-muted">' : ''}
                            `;
                        container.append(html);
                    });
                } else {
                    container.html('<div class="text-center text-muted small" id="no-insurance-msg">Tidak ada data asuransi terhubung.</div>');
                }
                var selectedOption = $('#policy_id').find('option:selected');
                var currencyCode = selectedOption.data('currency');

                if (typeof updateCurrencyLabel === "function") {
                    updateCurrencyLabel(currencyCode);
                }
            });

            Livewire.on('coveragesLoaded', (event) => {
                const coverages = event.coverages || event[0]?.coverages || [];
                // console.log('Coverages loaded:', coverages);

                const container = $('#coverage-list-container');
                container.empty();

                if (coverages.length > 0) {
                    coverages.forEach((coverage, index) => {
                        const html = `
                                <div class="row g-2 align-items-center mb-3 coverage-item">
                                    <div class="col-md-8">
                                        <label class="small text-muted mb-0">Coverage</label>
                                        <input type="text" class="form-control form-control-sm bg-white" 
                                            value="${coverage.coverage_name}" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="small text-muted mb-0">Persentase</label>
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control text-center bg-white" 
                                                value="${(coverage.percentage * 100).toFixed(5)}" readonly>
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </div>
                                ${index < coverages.length - 1 ? '<hr class="my-2 text-muted">' : ''}
                            `;
                        container.append(html);
                    });
                } else {
                    container.html('<div class="text-center text-muted small" id="no-coverage-msg">Tidak ada data coverage terhubung.</div>');
                }
            });
        });

        // Function to sum all object_value_input and update object_value
        // function sumObjectValues() {
        //     var total = 0;
        //     var count = 0;

        //     $('input[name="object_value_input"]').each(function() {
        //         var value = parseFloat($(this).val()) || 0;
        //         total += value;
        //         count++;
        //     });

        //     console.log('Total object values:', total, 'Count:', count);

        //     // Update the object_value field in form-tab-3
        //     $('#object_value').val(total.toFixed(0));
        //     // Call update_payment if it exists
        //     update_payment();
        // }

        // Listen for changes and sum/validate to be between 0-100
        $(document).on('input change keyup blur', 'input[name="coverage_percentage[]"]', function() {
            validateValues('input[name="coverage_percentage[]"]', '#coverage_percentage_total', 0, 100);
        });
        $(document).on('input change keyup blur', 'input[name="insurance_percentage[]"]', function() {
            validateValues('input[name="insurance_percentage[]"]', '#insurance_percentage_total', 0, 100);
        });
        $(document).on('input change keyup blur', 'input[name="interest_value[]"]', function() {
            var $itemObject = $(this).closest('.item-object');
            var $objectValueInput = $itemObject.find('input[name="object_value_input"]');
            
            // Sum all interest values in this item
            sumInputValues($itemObject.find('input[name="interest_value[]"]'), $objectValueInput);
            
            // After updating object_value_input, sum all object_value_inputs
            sumInputValues('input[name="object_value_input"]', '#object_value');
        });
    </script>
@endpush
