@extends('layouts.master')

@section('title')
    Create Kartu Produksi
@endsection

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/date-picker.css') }}">
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
        <form class="needs-validation" action="{{ isset($card) ? route('cards.update', $card->id) : route('cards.store') }}" method="POST" novalidate>
            <div class="card">
                @csrf
                @if (isset($card))
                    @method('PUT')
                @endif
                <div class="card-body">
                    <div class="stepwizard mt-3">
                        <div class="stepwizard-row setup-panel">
                            <div class="stepwizard-step"><a class="btn btn-primary" href="#step-1">1</a>
                                <p>Detail Kartu Produksi</p>
                            </div>
                            <div class="stepwizard-step"><a class="btn btn-light" href="#step-2">2</a>
                                <p>Detail Polis</p>
                            </div>
                            <div class="stepwizard-step"><a class="btn btn-light" href="#step-3">3</a>
                                <p>Detail Objek</p>
                            </div>
                            <div class="stepwizard-step"><a class="btn btn-light" href="#step-4">4</a>
                                <p>Detail Premi & Komisi</p>
                            </div>
                        </div>
                    </div>

                    {{-- CARD INFO START --}}
                    <div class="setup-content" id="step-1">
                        <div class="row g-3">
                            <div class="col-md-12 mt-3">
                                <h5 class="text-primary">Detail Kartu Produksi</h5>
                                <hr class="separator">
                            </div>
                            <div class="col-md-12 mt-3">
                                <label class="form-label" for="card_status_id">Tipe Kartu</label>
                                <select id="card_status_id" class="form-control select2-basic-single @error('card_status_id') is-invalid @enderror" name="card_status_id" required>
                                    <option value=""></option>
                                    @foreach ($cardStatuses as $status)
                                        <option value="{{ $status->id }}" {{ isset($card) ? ($card->card_status_id == $status->id ? 'selected' : '') : (old('card_status_id') == $status->id ? 'selected' : '') }}>
                                            {{ $status->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('card_status_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div id="parent_card_container" class="col-md-12" style="display:none;">
                                <label class="form-label" for="parent_card_id">Kartu Produksi Sebelumnya</label>
                                <select id="parent_card_id" class="form-control select2-basic-single @error('parent_card_id') is-invalid @enderror" name="parent_card_id">
                                    <option value=""></option>
                                    @foreach ($parentCards as $parentCard)
                                        <option value="{{ $parentCard->id }}" {{ isset($card) ? ($card->parent_card_id == $parentCard->id ? 'selected' : '') : (old('parent_card_id') == $parentCard->id ? 'selected' : '') }}>
                                            {{ $parentCard->policy->policy_number }} -
                                            {{ $parentCard->policy->object->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('parent_card_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label" for="client_id">Client</label>
                                <select id="client_id" class="form-control select2-basic-single @error('client_id') is-invalid @enderror" name="client_id" required>
                                    <option value=""></option>
                                    @foreach ($clients as $client)
                                        <option value="{{ $client->id }}" {{ isset($card) ? ($card->client_id == $client->id ? 'selected' : '') : (old('client_id') == $client->id ? 'selected' : '') }}>
                                            {{ $client->id_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('client_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    {{-- CARD INFO END --}}

                    {{-- POLIS INFO START --}}
                    <div class="setup-content" id="step-2">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <h5 class="text-primary">Detail Polis</h5>
                                <hr class="separator">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" for="policy_id">Nomor Polis</label>
                                <select id="policy_id" class="form-control policy-number-select2 @error('policy_id') is-invalid @enderror" name="policy_id" required>
                                    <option value=""></option>
                                    @foreach ($policies as $policy)
                                        <option value="{{ $policy->id }}" {{ isset($card) ? ($card->policy_id == $policy->id ? 'selected' : '') : (old('policy_id') == $policy->id ? 'selected' : '') }}>
                                            {{ $policy->policy_number }} - {{ $policy->object->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('policy_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Exist Policy Detail --}}
                            <div class="col-md-12">
                                @livewire('policy-details', ['policyId' => isset($card) ? $card->policy_id : old('policy_id')])
                            </div>

                            {{-- New Policy Input --}}
                            <div class="col-md-12">
                                @livewire('policy-form')
                            </div>

                        </div>
                    </div>
                    {{-- POLIS INFO END --}}

                    {{-- OBJECT INFO START --}}
                    <div class="setup-content" id="step-3">
                        <div class="row g-3">
                            <div class="col-md-12 mt-3">
                                <h5 class="text-primary">Detail Objek</h5>
                                <hr class="separator">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label" for="object_id">Nama Objek <span class="text-danger">*</span></label>
                                <select class="object-number-select2 @error('object_id') is-invalid @enderror" name="object_id" id="object_id" required style="width: 100%;">
                                    <option value=""></option>
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

                            {{-- Exist Policy Detail --}}
                            <div class="col-md-12" id="object-details-container">
                                @livewire('object-details', ['objectId' => isset($card) ? $card->object_id : old('object_id')], key('object-details'))
                            </div>

                            {{-- New Policy Input --}}
                            <div class="col-md-12">
                                @livewire('object-form')
                            </div>
                        </div>
                    </div>
                    {{-- OBJECT INFO END --}}

                    {{-- PREMI PARAMETER START --}}
                    <div class="setup-content" id="step-4">
                        <div class="row g-3">
                            <div class="col-md-12 mt-5">
                                <h5 class="text-primary">Parameter Premi</h5>
                                <hr class="separator">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="premi_percentages">Presentase Premi (%)</label>
                                <input id="premi_percentages" class="form-control @error('premi_percentages') is-invalid @enderror" name="premi_percentages" type="number" step="0.001" min="0" max="1" value="{{ isset($card) ? $card->premi_percentages : old('premi_percentages', 0) }}" required>
                                <small class="text-muted">Contoh: 0.03 untuk 0.03%</small>
                                @error('premi_percentages')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="tax_percentages">Persentase Pajak (%)</label>
                                <input id="tax_percentages" class="form-control @error('tax_percentages') is-invalid @enderror" name="tax_percentages" type="number" step="0.001" min="0" max="1" value="{{ isset($card) ? $card->tax_percentages : old('tax_percentages', 0) }}" required>
                                <small class="text-muted">Contoh: 0.03 untuk 0.03%</small>
                                @error('tax_percentages')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="commision_percentages">Presentasi Komisi (%)</label>
                                <input id="commision_percentages" class="form-control @error('commision_percentages') is-invalid @enderror" name="commision_percentages" type="number" step="0.01" min="0" max="1" value="{{ isset($card) ? $card->commision_percentages : old('commision_percentages', 0) }}" required>
                                <small class="text-muted">Contoh: 0.03 untuk 0.03%</small>
                                @error('commision_percentages')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="discount">Diskon (Rp)</label>
                                <input id="discount" class="form-control @error('discount') is-invalid @enderror" name="discount" type="number" step="0.01" value="{{ isset($card) ? $card->discount : old('discount', 0) }}">
                                <small class="text-muted">Masukkan nilai diskon dalam rupiah</small>
                                @error('discount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        {{-- PREMI PARAMETER END --}}

                        {{-- COMMISION CALCULATION START --}}
                        <div class="row g-3 input-group-solid mt-3">
                            <div class="col-md-12">
                                <h5 class="text-primary">Perhitungan Komisi</h5>
                                <hr class="separator">
                            </div>
                            {{-- SECTION: HARGA PERTANGGUNGAN --}}
                            <div id="policy-prices" class="col-md-12 input-group-solid">
                                <label class="form-label" for="object_value">Harga Pertanggungan (Rp)</label>
                                <input id="object_value" class="form-control @error('object_value') is-invalid @enderror" name="object_value" type="number" step="0.01" value="{{ isset($card) ? $card->object_value : old('object_value', 0) }}" required readonly>
                                @error('object_value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="production_value">Nilai Produksi (Rp)</label>
                                <input id="production_value" class="form-control @error('production_value') is-invalid @enderror" name="production_value" type="number" step="0.01" value="{{ isset($card) ? $card->production_value : old('production_value', 0) }}" readonly>
                                @error('production_value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="premi">Total Premi (Rp)</label>
                                <input id="premi" class="form-control @error('premi') is-invalid @enderror" name="premi" type="number" step="0.01" value="{{ isset($card) ? $card->premi : old('premi', 0) }}" readonly>
                                @error('premi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="commision_bruto">Komisi Awal (Rp)</label>
                                <input id="commision_bruto" class="form-control @error('commision_bruto') is-invalid @enderror" name="commision_bruto" type="number" step="0.01" value="{{ isset($card) ? $card->commision_bruto : old('commision_bruto', 0) }}" readonly>
                                @error('commision_bruto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="commision_netto">Komisi Bersih (Rp)</label>
                                <input id="commision_netto" class="form-control @error('commision_netto') is-invalid @enderror" name="commision_netto" type="number" step="0.01" value="{{ isset($card) ? $card->commision_netto : old('commision_netto', 0) }}" readonly>
                                @error('commision_netto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="tax">Nilai Pajak (Rp)</label>
                                <input id="tax" class="form-control @error('tax_value') is-invalid @enderror" name="tax" type="number" step="0.01" value="{{ isset($card) ? $card->tax : old('tax_value', 0) }}" readonly>
                                @error('tax_value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="insurance_pay">Bayar Asuransi (Rp)</label>
                                <input id="insurance_pay" class="form-control @error('insurance_pay') is-invalid @enderror" name="insurance_pay" type="number" step="0.01" value="{{ isset($card) ? $card->insurance_pay : old('insurance_pay', 0) }}" readonly>
                                @error('insurance_pay')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        {{-- COMMISION CALCULATION END --}}
                    </div>
                </div>
                <div class="card-footer	">
                    <a class="btn btn-light" href="{{ route('cards.index') }}">Kembali</a>
                    <button class="btn btn-primary float-end" type="submit">Simpan</button>
                </div>
            </div>
        </form>
    </div>

    @push('css')
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.css') }}">
    @endpush

    @push('scripts')
        <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
        <script src="{{ asset('assets/js/select2/select2-custom.js') }}"></script>
        <script src="{{ asset('assets/js/form-validation.js') }}"></script>
        <script src="{{ asset('assets/js/formula.js') }}"></script>
        <script src="{{ asset('assets/js/multiple-item.js') }}"></script>
        <script src="{{ asset('assets/js/form-wizard/form-wizard-two.js') }}"></script>

        <script>
            initializeFormValidation({
                customValidator: function(form, fields) {
                    var errors = [];
                    // Example: Check if every location has at least 1 BoQ entry
                    var insurance_percentages = $(form).find('.insurance-percentage');
                    var totalPercentage = 0;
                    insurance_percentages.each(function() {
                        if ($(this).closest('.item').is(':visible')) {
                            var value = parseFloat($(this).val()) || 0;
                            totalPercentage += value;
                        }
                    });

                    if (totalPercentage < 0 || totalPercentage > 100) {
                        alertWarning('Total persentase asuransi harus antara 0-100%. Saat ini: ' + totalPercentage.toFixed(2) + '%', '');
                        errors.push({
                            field: '.insurance-percentage',
                            message: 'Total persentase asuransi harus antara 0-100%'
                        });
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

            // Initialize Policy Select2
            initCustomSelect2("#policy_id", {
                placeholder: "Pilih Polis",
                newFormId: '#new-policy',
                existingDetailsId: '#existing-policy',
                newNameAttr: 'policy_number',
                existingNameAttr: 'policy_id'
            });

            // Initialize Object Select2
            initCustomSelect2("#object_id", {
                placeholder: "Pilih Objek",
                newFormId: '#new-object',
                existingDetailsId: '#existing-object',
                newNameAttr: 'object_name',
                existingNameAttr: 'object_id'
            });
            // Initialize Object Select2
            initCustomSelect2("#object_type_id", {
                placeholder: "Pilih Tipe Objek",
                newNameAttr: 'object_type_name',
                existingNameAttr: 'object_type_id'
            });

            // Initialize Object Select2
            initCustomSelect2(".add-select2");

            // Toggle parent_card_id based on card_status_id
            $('#card_status_id').on('change', function() {
                var cardStatusId = $('#card_status_id').val();
                var parentContainer = $('#parent_card_container');
                var parentSelect = $('#parent_card_id');

                if (cardStatusId == '2') {
                    parentContainer.show();
                    parentSelect.attr('required', true);
                } else {
                    parentContainer.hide();
                    parentSelect.removeAttr('required');
                    parentSelect.val('').trigger('change'); // Clear selection
                }
            });

            // Listen to policy_id dropdown change and update Livewire component
            $('#policy_id').on('change', function() {
                var policyId = $(this).val();
                // Find the Livewire component and update policyId
                var livewireComponent = Livewire.find(document.querySelector('[wire\\:id]').getAttribute(
                    'wire:id'));
                if (livewireComponent) {
                    livewireComponent.set('policyId', policyId);
                }
            });

            $('#object_id').on('change', function() {
                var objectId = $(this).val();
                console.log('Object ID changed:', objectId);

                if (!objectId) {
                    console.log('No object ID selected');
                    return;
                }

                // Find the object-details component
                try {
                    var container = document.querySelector('#object-details-container [wire\\:id]');
                    if (container) {
                        var wireId = container.getAttribute('wire:id');
                        var component = Livewire.find(wireId);
                        if (component) {
                            console.log('Setting objectId on component:', objectId);
                            component.set('objectId', objectId);
                        } else {
                            console.warn('Component not found with wire:id:', wireId);
                        }
                    } else {
                        console.warn('No Livewire component found in object-details-container');
                    }
                } catch (e) {
                    console.error('Error updating object component:', e);
                }
            });


            // Listen for policyLoaded and objectLoaded events from Livewire (outside jQuery scope)
            document.addEventListener('livewire:init', () => {
                Livewire.on('policyLoaded', (event) => {
                    const objectValue = event.objectValue || event[0]?.objectValue || 0;
                    const objectId = event.objectId || event[0]?.objectId || null;
                    console.log('Policy - Object value received:', objectValue);
                    if (objectValue > 0) {
                        $('#object_value').val(objectValue);
                    }
                    if (objectId) {
                        // Set the object_id select2 value
                        $('#object_id').val(objectId).trigger('change').prop('disabled', true);
                    }
                    update_payment(); // Trigger calculation when policy is loaded
                });

                Livewire.on('objectLoaded', (event) => {
                    const object = event.object || event[0]?.object || 0;
                    console.log('Object received:', object);
                });
            });

            function update_payment() {
                var object_value = parseFloat($('#object_value').val()) || 0;
                var premi_percentages = parseFloat($('#premi_percentages').val()) || 0;
                var policy_fee = 0; // No policy_fee field in the form, defaulting to 0
                var commision_percentages = parseFloat($('#commision_percentages').val()) || 0;
                var tax_percentages = parseFloat($('#tax_percentages').val()) || 0;
                var discount = parseFloat($('#discount').val()) || 0;

                const productionValue = production_value(object_value, premi_percentages);
                const premiValue = premi_value(productionValue, policy_fee);
                const commisionBruto = commision_bruto(productionValue, commision_percentages);
                const taxValue = tax_value(commisionBruto, tax_percentages);
                const commisionNetto = commision_netto(commisionBruto, taxValue, discount);
                const clientPay = client_pay(premiValue, discount);
                const insurancePay = insurance_pay(premiValue, taxValue, discount);

                $('#production_value').val(productionValue.toFixed(2));
                $('#premi').val(premiValue.toFixed(2));
                $('#commision_bruto').val(commisionBruto.toFixed(2));
                $('#commision_netto').val(commisionNetto.toFixed(2));
                $('#tax').val(taxValue.toFixed(2));
                $('#insurance_pay').val(insurancePay.toFixed(2));
            }

            // Add event listeners to trigger calculation when parameters change
            $(document).ready(function() {
                $('#object_value, #premi_percentages, #tax_percentages, #commision_percentages, #discount').on('input change', function() {
                    update_payment();
                });

                // Initial calculation on page load if values exist
                if (parseFloat($('#object_value').val()) > 0) {
                    update_payment();
                }

                // Validate insurance percentage sum before form submission
                $('.needs-validation').on('submit', function(e) {
                    var totalPercentage = 0;
                    var hasInsurances = false;

                    // Calculate sum of all visible insurance percentages
                    $('#new-policy .insurance-percentage').each(function() {
                        if ($(this).closest('.item').is(':visible')) {
                            hasInsurances = true;
                            var value = parseFloat($(this).val()) || 0;
                            totalPercentage += value;
                        }
                    });

                    // Check if new policy form is visible and has insurances
                    if ($('#new-policy').is(':visible') && hasInsurances) {
                        if (totalPercentage < 0 || totalPercentage > 100) {
                            e.preventDefault();
                            e.stopPropagation();

                            Swal.fire({
                                title: 'Validasi Gagal',
                                text: 'Total persentase asuransi harus antara 0-100%. Saat ini: ' + totalPercentage.toFixed(2) + '%',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                            return false;
                        }
                    }
                });

                // Update total percentage display in real-time
                $(document).on('input change', '.insurance-percentage', function() {
                    var totalPercentage = 0;
                    $('.insurance-percentage').each(function() {
                        if ($(this).closest('.item').is(':visible')) {
                            var value = parseFloat($(this).val()) || 0;
                            totalPercentage += value;
                        }
                    });
                    $('#total-percentage').text(totalPercentage.toFixed(2));
                    var infoDiv = $('#percentage-info');
                    if (totalPercentage > 100 || totalPercentage < 0) {
                        infoDiv.attr('class', 'alert alert-danger');
                    } else {
                        infoDiv.attr('class', 'alert alert-success');
                    }
                });

                $(document).on('change', '.is-main-checkbox', function() {
                    if (this.checked) {
                        $('.is-main-checkbox').not(this).prop('checked', false);
                    }
                });
            });
        </script>
    @endpush
@endsection
