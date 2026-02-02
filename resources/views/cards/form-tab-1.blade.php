<div id="step-1"
    class="setup-content">
    <div class="card mb-4 p-3 shadow-sm">

        <div class="row">
            <div class="col-md-12 mb-3">
                <h5 class="text-primary">Detail Kartu Produksi</h5>
                <hr class="separator">
            </div>
            <div class="col-md-12 mb-3">
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
                class="col-md-12 mb-3"
                style="display:none;">
                <label class="form-label"
                    for="parent_card_id">Polis Sebelumnya</label>
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

            <div class="col-md-12 mb-3">
                <label class="form-label"
                    for="client_id">Nama Client</label>
                <select id="client_id"
                    class="form-control create-select2 @error('client_id') is-invalid @enderror"
                    data-existing-id="#existing-client"
                    data-existing-name="client_id"
                    data-new-id="#new-client"
                    data-new-name="client_number"
                    data-placeholder="Pilih Nama Client"
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
            {{-- Exist client Detail --}}
            <div id="client-details-container"
                class="col-md-12">
            </div>

            {{-- New client Input --}}
            <div class="col-md-12">
                @livewire('client-form')
            </div>
        </div>
    </div>
</div>
{{-- CARD INFO END --}}

@push('scripts')
    <script>
        // Toggle parent_card_id based on card_status_id
        // $('#card_type_id').on('change', function() {
        //     var cardTypeId = $('#card_type_id').val();
        //     var parentContainer = $('#parent_card_container');
        //     var parentSelect = $('#parent_card_id');

        //     if (cardTypeId == '{{ config('constants.card_type.new') }}') {
        //         parentContainer.hide();
        //         parentSelect.removeAttr('required');
        //         parentSelect.val('').trigger('change'); // Clear selection

        //         parentContainer.find('.required').removeAttr('required');
        //     } else {
        //         parentContainer.show();
        //         parentSelect.attr('required', true);

        //         parentContainer.find('.required').attr('required', true);   
        //     }
        // });

        // $('#parent_card_id').on('change', function() {
        //     var selectedOption = $(this).find('option:selected');
        //     var clientId = selectedOption.data('client-id') || '';
        //     var policyId = selectedOption.data('policy-id') || '';

        //     // Set the client_id select2 value
        //     $('#client_id').val(clientId).trigger('change');

        //     // Set the policy_id select2 value (with delay to ensure element is ready)
        //     setTimeout(function() {
        //         $('#policy_id').val(policyId).trigger('change.select2').trigger('change');
        //     }, 300);
        // });
    </script>
@endpush
