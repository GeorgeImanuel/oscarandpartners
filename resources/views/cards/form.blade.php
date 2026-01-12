@extends('layouts.master')

@section('title')
    Create Kartu Produksi
@endsection

@push('css')
    <link rel="stylesheet"
        type="text/css"
        href="{{ asset('assets/css/date-picker.css') }}">
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
            <div class="card">
                @csrf
                @if (isset($card))
                    @method('PUT')
                @endif
                <div class="card-body">
                    <div class="stepwizard mt-3">
                        <div class="stepwizard-row setup-panel">
                            <div class="stepwizard-step"><a class="btn btn-primary"
                                    href="#step-1">1</a>
                                <p>Detail Kartu Produksi</p>
                            </div>
                            <div class="stepwizard-step"><a class="btn btn-light"
                                    href="#step-2">2</a>
                                <p>Detail Polis</p>
                            </div>
                            <div class="stepwizard-step"><a class="btn btn-light"
                                    href="#step-3">3</a>
                                <p>Detail Objek</p>
                            </div>
                            <div class="stepwizard-step"><a class="btn btn-light"
                                    href="#step-4">4</a>
                                <p>Detail Premi & Komisi</p>
                            </div>
                        </div>
                    </div>

                    {{-- CARD INFO START --}}
                    <div id="step-1"
                        class="setup-content">
                        <div class="row g-3">
                            <div class="col-md-12 mt-3">
                                <h5 class="text-primary">Detail Kartu Produksi</h5>
                                <hr class="separator">
                            </div>
                            <div class="col-md-12 mt-3">
                                <label class="form-label"
                                    for="card_type_id">Tipe Kartu</label>
                                <select id="card_type_id"
                                    class="form-control basic-select2 @error('card_type_id') is-invalid @enderror"
                                    name="card_type_id"
                                    required>
                                    <option value=""></option>
                                    @foreach ($cardTypes as $cardType)
                                        <option value="{{ $cardType->id }}"
                                            {{ isset($card) ? ($card->card_type_id == $cardType->id ? 'selected' : '') : (old('card_type_id') == $cardType->id ? 'selected' : '') }}>
                                            {{ $cardType->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('card_type_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div id="parent_card_container"
                                class="col-md-12"
                                style="display:none;">
                                <label class="form-label"
                                    for="parent_card_id">Kartu Produksi Sebelumnya</label>
                                <select id="parent_card_id"
                                    class="form-control select2-basic-single @error('parent_card_id') is-invalid @enderror"
                                    name="parent_card_id">
                                    <option value=""></option>
                                    @foreach ($parentCards as $parentCard)
                                        @if ($parentCard->id == (isset($card) ? $card->id : null))
                                            @continue
                                        @endif
                                        <option value="{{ $parentCard->id }}"
                                            {{ isset($card) ? ($card->parent_card_id == $parentCard->id ? 'selected' : '') : (old('parent_card_id') == $parentCard->id ? 'selected' : '') }}
                                            data-client-id="{{ $parentCard->client_id }}"
                                            data-policy-id="{{ $parentCard->policy_id }}">
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
                                <label class="form-label"
                                    for="client_id">Client</label>
                                <select id="client_id"
                                    class="form-control select2-basic-single @error('client_id') is-invalid @enderror"
                                    name="client_id"
                                    required>
                                    <option value=""></option>
                                    @foreach ($clients as $client)
                                        <option value="{{ $client->id }}"
                                            {{ isset($card) ? ($card->client_id == $client->id ? 'selected' : '') : (old('client_id') == $client->id ? 'selected' : '') }}>
                                            {{ $client->id_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('client_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label"
                                    for="client_id">Tipe Kolektor</label>
                                <select id="collector_type_id"
                                    class="form-control select2-basic-single @error('collector_type_id') is-invalid @enderror"
                                    name="collector_type_id"
                                    required>
                                    <option value=""></option>
                                    @foreach ($collectorTypes as $collector_type)
                                        <option value="{{ $collector_type->id }}"
                                            {{ isset($card) ? ($card->collector_type_id == $collector_type->id ? 'selected' : '') : (old('collector_type_id') == $collector_type->id ? 'selected' : '') }}>
                                            {{ $collector_type->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('collector_type_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label"
                                    for="deadline_at">Tanggal Deadline</label>
                                <input id="deadline_at"
                                    class="form-control @error('deadline_at') is-invalid @enderror"
                                    name="deadline_at"
                                    type="date"
                                    value="{{ isset($card) ? $card->deadline_at : old('deadline_at') }}"
                                    required />
                                @error('deadline_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    {{-- CARD INFO END --}}

                    {{-- POLIS INFO START --}}
                    <div id="step-2"
                        class="setup-content">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <h5 class="text-primary">Detail Polis</h5>
                                <hr class="separator">
                            </div>
                            <div id="policy-details-container"
                                class="col-md-12">
                                <label class="form-label"
                                    for="policy_id">Nomor Polis</label>
                                <select id="policy_id"
                                    class="form-control policy-select2 @error('policy_id') is-invalid @enderror"
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
                            <div class="col-md-12">
                                @livewire('policy-form')
                            </div>

                        </div>
                    </div>
                    {{-- POLIS INFO END --}}

                    {{-- OBJECT INFO START --}}
                    <div id="step-3"
                        class="setup-content">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <h5 class="text-primary">Detail Objek</h5>
                                <hr class="separator">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label"
                                    for="object_id">Nama Objek <span class="text-danger">*</span></label>
                                <select id="object_id"
                                    class="form-control object-select2 @error('object_id') is-invalid @enderror"
                                    data-existing-id="#existing-object"
                                    data-existing-name="object_id"
                                    data-new-id="#new-object"
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

                            {{-- Exist Policy Detail --}}
                            <div id="object-details-container"
                                class="col-md-12">
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
                    <div id="step-4"
                        class="setup-content">
                        <div class="row g-3">
                            <div class="col-md-12 mt-5">
                                <h5 class="text-primary">Parameter Premi</h5>
                                <hr class="separator">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"
                                    for="policy_fee">Biaya Polis</label>
                                <input id="policy_fee"
                                    class="form-control @error('policy_fee') is-invalid @enderror"
                                    name="policy_fee"
                                    type="number"
                                    value="{{ isset($card) ? $card->policy_fee : old('policy_fee', 0) }}"
                                    required>
                                <small class="text-muted">0% - 100%</small>
                                @error('premi_percentages')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"
                                    for="premi_percentages">Presentase Premi (%)</label>
                                <input id="premi_percentages"
                                    class="form-control @error('premi_percentages') is-invalid @enderror"
                                    name="premi_percentages"
                                    type="number"
                                    step="0.001"
                                    min="0"
                                    max="100"
                                    value="{{ isset($card) ? $card->premi_percentages : old('premi_percentages', 0) }}"
                                    required>
                                <small class="text-muted">0% - 100%</small>
                                @error('premi_percentages')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"
                                    for="tax_percentages">Persentase Pajak (%)</label>
                                <input id="tax_percentages"
                                    class="form-control @error('tax_percentages') is-invalid @enderror"
                                    name="tax_percentages"
                                    type="number"
                                    step="0.001"
                                    min="0"
                                    max="100"
                                    value="{{ isset($card) ? $card->tax_percentages : old('tax_percentages', 0) }}"
                                    required>
                                <small class="text-muted">0% - 100%</small>
                                @error('tax_percentages')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"
                                    for="commision_percentages">Presentasi Komisi Agent (%)</label>
                                <input id="commision_percentages"
                                    class="form-control @error('commision_percentages') is-invalid @enderror"
                                    name="commision_percentages"
                                    type="number"
                                    min="0"
                                    max="100"
                                    value="{{ isset($card) ? $card->commision_percentages : old('commision_percentages', 0) }}"
                                    required>
                                <small class="text-muted">0% - 100%</small>
                                @error('commision_percentages')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"
                                    for="discount">Diskon (Rp)</label>
                                <input id="discount"
                                    class="form-control @error('discount') is-invalid @enderror"
                                    name="discount"
                                    type="number"
                                    value="{{ isset($card) ? $card->discount : old('discount', 0) }}">
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
                            <div id="policy-prices"
                                class="col-md-6 input-group-solid">
                                <label class="form-label"
                                    for="object_value">Harga Pertanggungan (Rp)</label>
                                <input id="object_value"
                                    class="form-control @error('object_value') is-invalid @enderror"
                                    name="objects_value"
                                    type="number"
                                    value="{{ isset($card) ? $card->object_value : old('object_value', 0) }}"
                                    required
                                    readonly>
                                @error('object_value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"
                                    for="production_value">Nilai Produksi (Rp)</label>
                                <input id="production_value"
                                    class="form-control @error('production_value') is-invalid @enderror"
                                    name="production_value"
                                    type="number"
                                    value="{{ isset($card) ? $card->production_value : old('production_value', 0) }}"
                                    readonly>
                                @error('production_value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label"
                                    for="premi_value">Total Premi (Rp)</label>
                                <input id="premi_value"
                                    class="form-control @error('premi') is-invalid @enderror"
                                    name="premi_value"
                                    type="number"
                                    value="{{ isset($card) ? $card->premi : old('premi', 0) }}"
                                    readonly>
                                @error('premi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label"
                                    for="commision_bruto">Komisi Awal (Rp)</label>
                                <input id="commision_bruto"
                                    class="form-control @error('commision_bruto') is-invalid @enderror"
                                    name="commision_bruto"
                                    type="number"
                                    value="{{ isset($card) ? $card->commision_bruto : old('commision_bruto', 0) }}"
                                    readonly>
                                @error('commision_bruto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label"
                                    for="commision_netto">Komisi Bersih (Rp)</label>
                                <input id="commision_netto"
                                    class="form-control @error('commision_netto') is-invalid @enderror"
                                    name="commision_netto"
                                    type="number"
                                    value="{{ isset($card) ? $card->commision_netto : old('commision_netto', 0) }}"
                                    readonly>
                                @error('commision_netto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label"
                                    for="tax">Nilai Pajak (Rp)</label>
                                <input id="tax"
                                    class="form-control @error('tax_value') is-invalid @enderror"
                                    name="tax"
                                    type="number"
                                    value="{{ isset($card) ? $card->tax : old('tax_value', 0) }}"
                                    readonly>
                                @error('tax_value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"
                                    for="client_pay">Tagihan ke Client (Rp)</label>
                                <input id="client_pay"
                                    class="form-control @error('client_pay') is-invalid @enderror"
                                    name="client_pay"
                                    type="number"
                                    value="{{ isset($card) ? $card->client_pay : old('client_pay', 0) }}"
                                    readonly>
                                @error('client_pay')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"
                                    for="insurance_pay">Bayar Asuransi (Rp)</label>
                                <input id="insurance_pay"
                                    class="form-control @error('insurance_pay') is-invalid @enderror"
                                    name="insurance_pay"
                                    type="number"
                                    value="{{ isset($card) ? $card->insurance_pay : old('insurance_pay', 0) }}"
                                    readonly>
                                @error('insurance_pay')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        {{-- COMMISION CALCULATION END --}}
                    </div>
                </div>
                <div class="card-footer	">
                    <a class="btn btn-light"
                        href="{{ route('cards.index') }}">Kembali</a>
                    <button class="btn btn-primary float-end"
                        type="submit">Simpan</button>
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

            initCustomSelect2("#policy_id");
            initCustomSelect2("#object_id");
            initCustomSelect2(".add-select2");

            // Toggle parent_card_id based on card_status_id
            $('#card_type_id').on('change', function() {
                var cardTypeId = $('#card_type_id').val();
                var parentContainer = $('#parent_card_container');
                var parentSelect = $('#parent_card_id');

                if (cardTypeId == '{{ config('constants.card_type.renewal') }}') {
                    parentContainer.show();
                    parentSelect.attr('required', true);
                    
                    parentContainer.find('.required').attr('required', true);

                } else {
                    parentContainer.hide();
                    parentSelect.removeAttr('required');
                    parentSelect.val('').trigger('change'); // Clear selection

                    parentContainer.find('.required').removeAttr('required');
                }
            });

            $('#parent_card_id').on('change', function() {
                var selectedOption = $(this).find('option:selected');
                var clientId = selectedOption.data('client-id') || '';
                // var policyId = selectedOption.data('policy-id') || '';

                // Set the client_id select2 value
                $('#client_id').val(clientId).trigger('change');

                // Set the policy_id select2 value
                // $('#policy_id').val(policyId).trigger('change');
            });

            // Listen to policy_id dropdown change and update Livewire component
            $('#policy_id').on('change', function() {
                var policyId = $(this).val();
                $('#object_id').val('').trigger('change').prop('disabled', false);
                $('#new-object').hide();
                $('#existing-object').show();
                console.log('Policy ID changed:', policyId);

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
            $('#object_id').on('change', function() {
                var objectId = $(this).val();
                console.log('Object ID changed:', objectId);

                var container = document.querySelector('#object-details-container [wire\\:id]');
                if (container && typeof window.Livewire !== 'undefined') {
                    var livewireComponent = window.Livewire.find(container.getAttribute('wire:id'));
                    if (livewireComponent) {
                        livewireComponent.set('objectId', objectId);
                    }
                }
            });

            function updateObjectValue() {
                var totalValue = 0;
                $('input[name="value"]').each(function() {
                    var value = parseInt($(this).val(), 10) || 0;
                    totalValue += value;
                });
                $('#object_value').val(totalValue);
                $('#object_value_input').val(totalValue);
                update_payment();
            }

            // Listen for policyLoaded and objectLoaded events from Livewire (outside jQuery scope)
            document.addEventListener('livewire:init', () => {
                Livewire.on('policyLoaded', (event) => {
                    const objectId = event.objectId || event[0]?.objectId || null;

                    if (objectId) {
                        // Set value and disable the Select2 dropdown
                        $('#object_id').val(objectId).trigger('change');
                        $('#object_id').prop('disabled', true);

                        // Add hidden input to ensure value is submitted
                        if (!$('#object_id_hidden').length) {
                            $('<input>').attr({
                                type: 'hidden',
                                id: 'object_id_hidden',
                                name: 'object_id',
                                value: objectId
                            }).insertAfter('#object_id');
                        }
                    }
                });

                Livewire.on('objectLoaded', (event) => {
                    const object = event.object || event[0]?.object || 0;

                    if (object) {
                        $('#object_value').val(object.object_value);
                    }
                    console.log('Object received:', object.object_value);
                    update_payment(); // Trigger calculation when policy is loaded

                });
            });

            function update_payment() {
                var object_value = parseInt($('#object_value').val()) || 0;
                var premi_percentages = parseFloat($('#premi_percentages').val()) || 0;
                var policy_fee = parseInt($('#policy_fee').val()) || 0;
                var commision_percentages = parseFloat($('#commision_percentages').val()) || 0;
                var tax_percentages = parseFloat($('#tax_percentages').val()) || 0;
                var discount = parseInt($('#discount').val()) || 0;

                const productionValue = production_value(object_value, premi_percentages / 100);
                const premiValue = premi_value(productionValue, policy_fee);
                const commisionBruto = commision_bruto(productionValue, commision_percentages / 100);
                const taxValue = tax_value(commisionBruto, tax_percentages / 100);
                const commisionNetto = commision_netto(commisionBruto, taxValue, discount);
                const clientPay = client_pay(premiValue, discount);
                const insurancePay = insurance_pay(premiValue, taxValue, discount);

                console.log('Calculated values:', {
                    productionValue,
                    premiValue,
                    commisionBruto,
                    taxValue,
                    commisionNetto,
                    clientPay,
                    insurancePay
                });

                $('#production_value').val(productionValue.toFixed(2));
                $('#premi_value').val(premiValue.toFixed(2));
                $('#commision_bruto').val(commisionBruto.toFixed(2));
                $('#commision_netto').val(commisionNetto.toFixed(2));
                $('#tax').val(taxValue.toFixed(2));
                $('#client_pay').val(clientPay.toFixed(2));
                $('#insurance_pay').val(insurancePay.toFixed(2));
            }

            // Add event listeners to trigger calculation when parameters change
            $(document).ready(function() {
                $('#object_value, #policy_fee, #premi_percentages, #tax_percentages, #commision_percentages, #discount').on('input change', function() {
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
