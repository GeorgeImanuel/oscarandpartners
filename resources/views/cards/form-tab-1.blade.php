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
                    data-new-name="client_name"
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
        $(document).on('change', '#client_id', function() {
            var clientId = $(this).val();

            // Find the Livewire component and update clientId
            // var container = document.querySelector('#policy-details-container [wire\\:id]');
            // if (container && typeof window.Livewire !== 'undefined') {
            //     var livewireComponent = window.Livewire.find(container.getAttribute('wire:id'));
            //     if (livewireComponent) {
            //         livewireComponent.set('clientId', clientId);
            //     }
            // }
        });
    </script>
@endpush
