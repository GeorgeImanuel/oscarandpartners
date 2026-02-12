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

            <div id="endors_type_container"
                class="col-md-12 mb-3"
                style="display:none;">
                <label class="form-label"
                    for="endors_type">Tipe Endors <small class="text-muted">(hanya untuk perhitungan)</small></label>
                <select id="endors_type"
                    class="form-control basic-select2">
                    <option value="">Pilih Tipe Endors</option>
                    <option value="kendaraan">Kendaraan</option>
                    <option value="properti">Properti</option>
                </select>
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
                            data-policy-id="{{ $parentCard->policy_id }}"
                            data-object-value="{{ $parentCard->policy->objects->sum('total_value') ?? 0 }}">
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
                    {{-- data-existing-name="client_id" --}}
                    data-new-id="#client-form-container"
                    {{-- data-new-name="client_name" --}}
                    data-placeholder="Pilih Nama Client"
                    {{-- --}}
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
            {{-- New client Input --}}
            <div id="client-form-container"
                class="col-md-12" style="display: none">
                @livewire('client-form')
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            // Check card type on page load (for edit mode)
            toggleParentCardField();
        });

        // Handle card type change
        $(document).on('change', '#card_type_id', function() {
            toggleParentCardField();
        });

        // Handle endors type change
        $(document).on('change', '#endors_type', function() {
            var endorsType = $(this).val();
            
            // Store endors type globally for access in other scripts
            window.endorsType = endorsType;
            
            // Toggle parent card field based on endors type
            if (endorsType === 'properti') {
                $('#parent_card_container').slideDown();
                $('#parent_card_id').attr('required', true);
            } else if (endorsType === 'kendaraan') {
                $('#parent_card_container').slideUp();
                $('#parent_card_id').attr('required', false);
                $('#parent_card_id').val('').trigger('change');
                // Clear parent object value for kendaraan type
                if (typeof window.parentObjectValue !== 'undefined') {
                    window.parentObjectValue = 0;
                    $('#parent_object_value').val(0);
                }
            }
            
            console.log('Endors Type Changed:', endorsType);
            
            // Trigger recalculation if on step 3
            if ($('#step-3').is(':visible')) {
                // Recalculate object value based on new endors type
                setTimeout(function() {
                    // Re-sum object values
                    if (typeof sumInputValues === 'function') {
                        sumInputValues($('#policy-form-container input[name="object_value_input"]'), '#object_value');
                    }
                    
                    // Trigger payment recalculation
                    if (typeof update_payment === 'function') {
                        update_payment();
                    }
                }, 100);
            }
        });

        function toggleParentCardField() {
            var cardTypeId = $('#card_type_id').val();
            var endorsId = {{ config('constants.card_type.endors') }};
            
            // Show endors type dropdown for Endors card type
            if (cardTypeId == endorsId) {
                $('#endors_type_container').slideDown();
                
                // Check if endors type is already selected
                var endorsType = $('#endors_type').val();
                if (endorsType === 'properti') {
                    $('#parent_card_container').slideDown();
                    $('#parent_card_id').attr('required', true);
                } else {
                    $('#parent_card_container').slideUp();
                    $('#parent_card_id').attr('required', false);
                }
            } else {
                // Hide both endors type and parent card fields for non-Endors
                $('#endors_type_container').slideUp();
                $('#endors_type').val('').trigger('change');
                
                $('#parent_card_container').slideUp();
                $('#parent_card_id').attr('required', false);
                $('#parent_card_id').val('').trigger('change');
                
                // Clear parent object value when switching away from Endors
                if (typeof window.parentObjectValue !== 'undefined') {
                    window.parentObjectValue = 0;
                    $('#parent_object_value').val(0);
                }
                
                // Clear endors type
                window.endorsType = null;
            }
        }

        // Handle parent card selection - store parent object value
        $(document).on('change', '#parent_card_id', function() {
            var selectedOption = $(this).find('option:selected');
            var parentObjectValue = parseFloat(selectedOption.data('object-value')) || 0;
            
            // Store in global variable and hidden field
            window.parentObjectValue = parentObjectValue;
            $('#parent_object_value').val(parentObjectValue);
            
            console.log('Parent Object Value:', parentObjectValue);
        });

        $(document).on('change', '#client_id', function() {
            var clientId = $(this).val();
            if(clientId) {
                // Show existing client form
                $('#client-form-container').slideUp();
            } else {
                // Show new client form
                $('#client-form-container').slideDown();
            }
        });
    </script>
@endpush
