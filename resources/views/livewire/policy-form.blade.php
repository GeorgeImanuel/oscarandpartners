<div id="new-policy">
    <div class="card mb-3 p-3 shadow-sm">
        <div class="row">

            <div class="col-md-6 mb-3">
                <label class="form-label"
                    for="started_date">Periode Awal Pertanggungan <span class="text-danger">*</span></label>
                <input class="form-control required @error('started_date') is-invalid @enderror"
                    id="started_date"
                    name="started_date"
                    type="date"
                    value="{{ isset($policy) ? $policy->started_date->format('Y-m-d') : old('started_date', '') }}" />
                @error('started_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label"
                    for="ended_date">Periode Akhir Pertanggungan <span class="text-danger">*</span></label>
                <input class="form-control required @error('ended_date') is-invalid @enderror"
                    id="ended_date"
                    name="ended_date"
                    type="date"
                    value="{{ isset($policy) ? $policy->ended_date->format('Y-m-d') : old('ended_date', '') }}" />
                @error('ended_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-12 mb-3"
                wire:ignore>
                <label class="form-label"
                    for="currency_code">Mata Uang <span class="text-danger">*</span></label>
                <select class="form-select add-select2 required @error('currency_code') is-invalid @enderror"
                    name="currency_code"
                    id="currency_code"
                    data-placeholder="Pilih Mata Uang"
                    data-selected-value="{{ $policy->currency_code ?? '' }}">
                    <option value=""></option>
                    @foreach ($currencies as $currency)
                        <option value="{{ $currency->code }}"
                            {{ isset($policy) && $policy && $currency->code == $policy->currency_code ? 'selected' : (old('currency_code') == $currency->code ? 'selected' : '') }}>
                            {{ $currency->name }}
                        </option>
                    @endforeach
                </select>
                @error('currency_code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-12 mb-3 multiple-item-container">
                <label>List Coverage</label>
                <div class="row align-items-end">
                    <div class="col-md">
                        <div id="percentage-info"
                            class="alert alert-danger py-2">
                            <strong>Total Persentase Coverage: <span id="total-percentage">0.00</span>%</strong><br>
                        </div>
                    </div>
                    <div class="col-md-auto mb-3">
                        <button type="button"
                            class="btn btn-primary btn-sm add-item-btn float-end"
                            class="add-item-btn"
                            data-target="item-coverage">
                            <i class="icon-plus me-2"></i> Tambah Coverage
                        </button>
                    </div>
                </div>
                <div class="item-list border rounded mb-2 px-3 py-2">
                    @forelse ((isset($policy) && $policy ? ($policy->policyCoverages ?? []) : []) as $index => $policyCoverage)
                        <div class="row align-items-end item item-coverage {{ $index != 0 ? 'border-top py-2' : ' pt-2' }}">
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Coverage <span class="item-count">{{ $index + 1 }}</span></label>
                                <select class="form-select add-select2 required"
                                    name="coverage_id[]"
                                    data-placeholder="Pilih Coverage"
                                    style="width: 100%;">
                                    <option value="">- Pilih Coverage -</option>
                                    @foreach ($coverages as $coverage)
                                        <option value="{{ $coverage->id }}"
                                            {{ $coverage->id == $policyCoverage->coverage_id ? 'selected' : '' }}>
                                            {{ $coverage->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md mb-2">
                                <label class="form-label">Persentase Coverage</label>
                                <div class="input-group">
                                    <input type="number"
                                        class="form-control insurance-percentage"
                                        name="percentage[]"
                                        value="{{ $policyCoverage->percentage * 100 }}"
                                        placeholder="0"
                                        min="0"
                                        max="100"
                                        step="0.01">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <div class="col-md-auto mb-2">
                                <button type="button"
                                    class="btn btn-danger btn-xs remove-item-btn">
                                    <i class="icon-trash me-2"></i> Hapus
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="row align-items-end item item-coverage mb-2">
                            <div class="col-md-6">
                                <label class="form-label">Coverage <span class="item-count">1</span></label>
                                <select class="form-select add-select2 required"
                                    name="coverage_id[]"
                                    data-placeholder="Pilih Coverage"
                                    style="width: 100%;">
                                    <option value="">- Pilih Coverage -</option>
                                    @foreach ($coverages as $coverage)
                                        <option value="{{ $coverage->id }}">
                                            {{ $coverage->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md">
                                <label class="form-label">Persentase Coverage</label>
                                <div class="input-group">
                                    <input type="number"
                                        class="form-control insurance-percentage"
                                        name="percentage[]"
                                        value=""
                                        placeholder="0"
                                        min="0"
                                        max="100"
                                        step="0.01">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <div class="col-md-auto my-2">
                                <button type="button"
                                    class="btn btn-danger btn-xs remove-item-btn"
                                    style="display:none">
                                    <i class="icon-trash me-2"></i> Hapus
                                </button>
                            </div>
                        </div>
                    @endforelse
                </div>

                <div id="empty-state"
                    class="text-center py-4 text-muted"
                    style="{{ isset($policyInsurances) && $policyInsurances->count() > 0 ? 'display: none;' : '' }}">
                    <i class="fa fa-info-circle fa-2x mb-2 d-block"></i>
                    <p class="mb-0">Klik tombol <strong class="text-primary">"Tambah Asuransi"</strong> untuk menambahkan asuransi</p>
                </div>
            </div>

            <div class="col-md-12 mb-3 multiple-item-container">
                <label>List Asuransi</label>
                <div class="row align-items-end">
                    <div class="col-md">
                        <div id="percentage-info"
                            class="alert alert-danger py-2">
                            <strong>Total Persentase Asuransi: <span id="total-percentage">0.00</span>%</strong><br>
                        </div>
                    </div>
                    <div class="col-md-auto mb-3">
                        <button type="button"
                            class="btn btn-primary btn-sm add-item-btn float-end"
                            class="add-item-btn"
                            data-target="item-1">
                            <i class="icon-plus me-2"></i> Tambah Asuransi
                        </button>
                    </div>
                </div>
                <div class="item-list border rounded mb-2 px-3 py-2">
                    @forelse ((isset($policy) && $policy ? ($policy->policyInsurances ?? []) : []) as $index => $policyInsurance)
                        <div class="row align-items-end item item-1  {{ $index != 0 ? 'border-top py-2' : ' pt-2' }}">
                            <div class="col-md-5 mb-2">
                                <label class="form-label">Asuransi <span class="item-count">1</span></label>
                                <select class="form-select add-select2 required"
                                    name="insurance_id[]"
                                    data-placeholder="Pilih Asuransi"
                                    style="width: 100%;">
                                    <option value="">- Pilih Asuransi -</option>
                                    @foreach ($insurances as $insurance)
                                        <option value="{{ $insurance->id }}"
                                            {{ isset($policyInsurance) && $policyInsurance->insurance_id == $insurance->id ? 'selected' : '' }}>
                                            {{ $insurance->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md mb-2">
                                <label class="form-label">Persentase Asuransi</label>
                                <div class="input-group">
                                    <input type="number"
                                        class="form-control insurance-percentage"
                                        name="percentage[]"
                                        value="{{ isset($policyInsurance) ? $policyInsurance->percentage * 100 : '' }}"
                                        placeholder="0"
                                        min="0"
                                        max="100"
                                        step="0.01">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <div class="col-md-2 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input is-main-checkbox"
                                        type="checkbox"
                                        name="is_main[]"
                                        value="1">
                                    <label class="form-check-label">Asuransi Utama</label>
                                </div>
                            </div>
                            <div class="col-md-auto mb-2">
                                <button type="button"
                                    class="btn btn-danger btn-xs remove-item-btn">
                                    <i class="icon-trash me-2"></i> Hapus
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="row align-items-end item item-1">
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Asuransi <span class="item-count">1</span></label>
                                    <select class="form-select add-select2 required"
                                        name="insurance_id[]"
                                        data-placeholder="Pilih Asuransi"
                                        style="width: 100%;">
                                        <option value="">- Pilih Asuransi -</option>
                                        @foreach ($insurances as $insurance)
                                            <option value="{{ $insurance->id }}">
                                                {{ $insurance->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md mb-2">
                                    <label class="form-label">Persentase Asuransi</label>
                                    <div class="input-group mb-3">
                                        <input type="number"
                                            class="form-control insurance-percentage"
                                            name="percentage[]"
                                            value=""
                                            placeholder="0"
                                            min="0"
                                            max="100"
                                            step="0.01">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input is-main-checkbox"
                                            type="checkbox"
                                            name="is_main[]"
                                            value="1">
                                        <label class="form-check-label">Asuransi Utama</label>
                                    </div>
                                </div>
                                <div class="col-md-auto mb-2">
                                    <button type="button"
                                        class="btn btn-danger btn-xs remove-item-btn"
                                        style="display:none">
                                        <i class="icon-trash me-2"></i> Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>

                <div id="empty-state"
                    class="text-center py-4 text-muted"
                    style="{{ isset($policyInsurances) && $policyInsurances->count() > 0 ? 'display: none;' : '' }}">
                    <i class="fa fa-info-circle fa-2x mb-2 d-block"></i>
                    <p class="mb-0">Klik tombol <strong class="text-primary">"Tambah Asuransi"</strong> untuk menambahkan asuransi</p>
                </div>
            </div>
        </div>
    </div>
    {{  json_encode($policy) }}
    <div class="card mb-3 p-3 shadow-sm">
        <div class="row">
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
                        @forelse ((isset($policy) && $policy ? ($policy->policyObjects ?? []) : []) as $index => $policyObject)
                            <div class="row align-items-end item item-object border border-primary py-3 mx-0 mb-3">
                                <div class="col-md-10 mb-3">
                                    <label class="form-label"
                                        for="object_id">Nama Objek <span class="text-danger">*</span></label>
                                    <select class="form-control create-select2 object-select @error('object_id') is-invalid @enderror"
                                        {{-- data-existing-id=".object-details-container" --}}
                                        data-existing-name="object_id"
                                        {{-- data-new-id=".object-forms-container" --}}
                                        data-new-name="object_name"
                                        data-placeholder="Pilih Objek"
                                        name="object_id"
                                        required>
                                        <option value=""></option>
                                        @foreach ($objects ?? [] as $object)
                                            <option value="{{ $object->id }}" {{ $object->id == $policyObject->object_id ? 'selected' : '' }}>
                                                {{ $object->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('object_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2 mb-3">
                                    <span class="remove-item-btn btn btn-danger btn-xs" ><i class="ico icon-trash"></i> Hapus Objek</span>
                                </div>

                                {{-- Object Form --}}
                                <div class="col-md-12 object-forms-container">
                                    @livewire('object-form', ['objectId' => $policyObject->object_id ?? null], key('object-form-' . $policyObject->id ?? uniqid()))
                                </div>
                            </div>
                        @empty
                            <div class="row align-items-end item item-object border border-primary py-3 mx-0 mb-3">
                                <div class="col-md-10 mb-3">
                                    <label class="form-label"
                                        for="object_id">Nama Objek <span class="text-danger">*</span></label>
                                    <select class="form-control create-select2 object-select @error('object_id') is-invalid @enderror"
                                        {{-- data-existing-id=".object-details-container" --}}
                                        data-existing-name="object_id"
                                        {{-- data-new-id=".object-forms-container" --}}
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

                                {{-- Object Form --}}
                                <div class="col-md-12 object-forms-container"
                                    style="display:none;">
                                    @livewire('object-form', ['objectId' => null], key('object-form-' . uniqid()))
                                </div>
                            </div>
                        @endforelse

                    </div>
                </div>
                <input type="hidden"
                    id="object_ready"
                    value="0"
                    disabled />
            </div>
        </div>
    </div>
</div>

@script
    <script>
        // Listen for currency code update event
        $wire.on('setCurrencyCode', (event) => {
            var currencyCode = event.currencyCode || (event[0] && event[0].currencyCode) || '';

            setTimeout(() => {
                if ($('#currency_code').length) {
                    $('#currency_code').val(currencyCode).trigger('change.select2');
                }
            }, 150);
        });

        // Reinitialize Select2 after Livewire updates
        $wire.on('refreshPolicyForm', (event) => {
            setTimeout(() => {
                // Destroy existing select2 first
                if ($('#currency_code').hasClass('select2-hidden-accessible')) {
                    $('#currency_code').select2('destroy');
                }

                // Reinitialize currency_code
                $('#currency_code').select2({
                    placeholder: 'Pilih Mata Uang',
                    allowClear: true
                });
                initCustomSelect2(".add-select2");
                initCustomSelect2(".create-select2");
            }, 100);
        });

        // Reinitialize select2 on any Livewire update
        Livewire.hook('commit', ({
            component,
            commit,
            respond,
            succeed,
            fail
        }) => {
            succeed(({
                snapshot,
                effect
            }) => {
                setTimeout(() => {
                    $('.add-select2').each(function() {
                        if (!$(this).hasClass('select2-hidden-accessible')) {
                            var placeholder = $(this).data('placeholder') || 'Pilih Data';
                            $(this).select2({
                                placeholder: placeholder,
                                allowClear: true
                            });
                        }
                    });
                }, 150);
            });
        });

        // Handle object select change
        $(document).on('change', '.object-select', function() {
            var selectedValue = $(this).val();
            var $itemContainer = $(this).closest('.item-object');
            var $formContainer = $itemContainer.find('.object-forms-container');
            
            if (selectedValue) {
                // Show form container
                $formContainer.show();
                
                // Load object data into form
                var formComponent = $formContainer.find('[wire\\:id]').first();
                if (formComponent.length) {
                    var componentId = formComponent.attr('wire:id');
                    var component = Livewire.find(componentId);
                    if (component) {
                        component.set('objectId', selectedValue);
                    }
                }
            } else {
                // Hide form when no selection
                $formContainer.hide();
            }
        });
        

        // Initialize on component load
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                if (!$('#currency_code').hasClass('select2-hidden-accessible')) {
                    $('#currency_code').select2({
                        placeholder: 'Pilih Mata Uang',
                        allowClear: true
                    });
                }

                // Initialize all .add-select2 elements
                $('.add-select2').each(function() {
                    if (!$(this).hasClass('select2-hidden-accessible')) {
                        var placeholder = $(this).data('placeholder') || 'Pilih Data';
                        $(this).select2({
                            placeholder: placeholder,
                            allowClear: true
                        });
                    }
                });

                // Set initial value from selected option
                var selectedOption = $('#currency_code option[selected]');
                if (selectedOption.length) {
                    $('#currency_code').val(selectedOption.val()).trigger('change.select2');
                }
            }, 200);
        });
    </script>
@endscript

{{-- <div class="col-md-12 mb-3">
        <label class="form-label" for="object_id">Objek <span class="text-danger">*</span></label>
        <select class="add-single-select2 required  @error('object_id') is-invalid @enderror" name="object_id" id="object_id"  style="width: 100%;">
            <option value="">- Pilih Objek -</option>
            @foreach ($objects ?? [] as $object)
                <option value="{{ $object->id }}" {{ isset($policy) && $policy->object_id == $object->id ? 'selected' : (old('object_id') == $object->id ? 'selected' : '') }}>
                    {{ $object->name }}
                </option>
            @endforeach
        </select>
        @error('object_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div> --}}
