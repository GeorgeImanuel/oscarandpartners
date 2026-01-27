<div id="step-2"
    class="setup-content">
    <div class="row">
        <div class="col-md-12 mb-3">
            <h5 class="text-primary">Polis</h5>
            <hr class="separator">
        </div>
        <div id="policy-details-container"
            class="col-md-12 mb-3">
            <label class="form-label"
                for="policy_id">Nomor Polis</label>
            <select id="policy_id"
                class="form-control create-select2 @error('policy_id') is-invalid @enderror"
                data-existing-id="#existing-policy"
                data-existing-name="policy_id"
                data-new-id="#new-policy"
                data-new-name="policy_number"
                data-placeholder="Pilih Polis"
                name="policy_id"
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

        {{-- Exist Policy Detail --}}
        <div id="policy-details-container"
            class="col-md-12">
            @livewire('policy-details', ['policyId' => isset($card) ? $card->policy_id : old('policy_id')])
        </div>

        {{-- New Policy Input --}}
        <div class="col-md-12 mb-3">
            @livewire('policy-form')
        </div>

        {{-- OBJECT INFO START --}}
        <div class="col-md-12 mb-3">
            <div class="row multiple-item-container">
                <div class="col-md mt-2 mb-3">
                    <h5 class="text-primary">Objek Pertanggungan</h5>
                    <hr class="separator">
                </div>
                <div class="col-md-auto mt-2 mb-3">
                    <span class="add-item-btn btn btn-primary float-end"
                        data-target="item-object"><i class="ico icon-plus me-2"></i> Tambah Objek Pertanggungan</span>
                </div>

                <div class="col-md-12 mb-3 item-list">
                    <div class="row align-items-end item item-object border border-primary py-3 mx-0 mb-3">
                        <div class="col-md-10 mb-3">
                            <label class="form-label"
                                for="object_id">Nama Objek <span class="text-danger">*</span></label>
                            <select class="form-control create-select2 object-select @error('object_id') is-invalid @enderror"
                                data-existing-id=".object-details-container"
                                data-existing-name="object_id"
                                data-new-id=".object-forms-container"
                                data-new-name="object_name"
                                data-placeholder="Pilih Objek"
                                name="object_id"
                                required>
                                <option value=""></option>
                                @foreach ($objects ?? [] as $object)
                                    <option value="{{ $object->id }}">
                                        {{ $object->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('object_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-2 mb-3">
                            <span class="remove-item-btn btn btn-danger btn-xs"
                                style="display:none"><i class="ico icon-trash"></i> Hapus Objek</span>
                        </div>

                        {{-- Exist Object Detail --}}
                        <div class="col-md-12 object-details-container">
                            @livewire('object-details', ['objectId' => isset($card) ? $card->object_id : old('object_id')], key('object-details-' . uniqid()))
                        </div>

                        {{-- New Object Input --}}
                        <div class="col-md-12 object-forms-container"
                            style="display:none;">
                            @livewire('object-form', [], key('object-form-' . uniqid()))
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden"
                id="object_ready"
                value="0"
                disabled />
            <div class="col-md-12"
                id="object-details-container"
                style="display:none;">
                @livewire('object-details', ['objectId' => isset($card) ? $card->object_id : old('object_id')], key('object-details-' . uniqid()))
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        // Listen to policy_id dropdown change and update Livewire component
        $('#policy_id').on('change', function() {
            var policyId = $(this).val();
            // $('#object_id').val('').trigger('change').prop('disabled', false);
            // $('#new-object').hide();
            // $('#existing-object').show();
            // console.log('Policy ID changed:', policyId);

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

        // Reset object details when object_id changes
        $(document).on('change', 'select[name=object_name]', function() {
            $nextObjectContainer = $(this).closest('.item').find('.object-details-container');
            $nextObjectContainer.html('<div wire:loading.remove="" class="alert alert-light dark" role="alert"> Pilih nomor polis untuk melihat detail!</div>');
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
        $(document).on('change', '.object-select', function() {
            var row = $(this).closest('.object-row');
            var selectedOption = $(this).find(':selected');
            var value = parseFloat(selectedOption.data('value')) || 0;

            // Update tampilan nilai per baris
            row.find('.object-value-display').val(formatRupiah(value));

            // Hitung total semua objek
            calculateTotalObjectValue();
        });

        function calculateTotalObjectValue() {
            var totalValue = 0;

            // Loop semua dropdown objek yang dipilih
            $('.object-select').each(function() {
                var selectedOption = $(this).find(':selected');
                var value = parseFloat(selectedOption.data('value')) || 0;
                totalValue += value;
            });

            // Update Tampilan Total (Text)
            $('#total-object-value').text(formatRupiah(totalValue));

            // Update Input Hidden/Readonly yang dipakai update_payment()
            // Pastikan di HTML ada input dengan id="object_value"
            $('#object_value').val(totalValue);

            // Trigger hitung ulang payment
            update_payment();

            if (totalValue > 0) $('#object-value-info').show();
            else $('#object-value-info').hide();
        }

        // Listen for policyLoaded and objectLoaded events from Livewire (outside jQuery scope)
        document.addEventListener('livewire:init', () => {
            Livewire.on('policyLoaded', (event) => {
                const objectId = event.objectId || event[0]?.objectId || null;
                const coveragePercentage = event.coverage_percentage || event[0]?.coverage_percentage || 0;
                const totalObjectValue = event.totalObjectValue || event[0]?.totalObjectValue || 0;
                const objectsCount = event.objectsCount || event[0]?.objectsCount || 0;

                // Update field Harga Pertanggungan
                $('#object_value').val(totalObjectValue);

                // Update display (format Rupiah)
                $('#total-object-value').text(formatRupiah(totalObjectValue));

                // Show/hide info based on value
                if (totalObjectValue > 0) {
                    $('#object-value-info').show();
                } else {
                    $('#object-value-info').hide();
                }

                var rate = parseFloat(coveragePercentage) * 100;
                $('#premi_percentages').val(rate).trigger('change');

                update_payment();
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
                update_payment(); // Trigger calculation when policy is loaded

            });

            Livewire.on('insurancesLoaded', (event) => {
                const insurances = event.insurances || event[0]?.insurances || [];
                const container = $('#insurance-list-container');
                container.empty();

                if (insurances.length > 0) {
                    insurances.forEach((insurance, index) => {
                        const html = `
                                <div class="row g-2 align-items-center mb-2 insurance-item">
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
                                <div class="row g-2 align-items-center mb-2 coverage-item">
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
    </script>
@endpush
