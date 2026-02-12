<div id="new-policy">
    <div class="card mb-3 p-3 shadow-sm">
        <div class="row">

            <div class="col-md-6 mb-3">
                <label class="form-label"
                    for="started_date">Periode Awal Pertanggungan <span class="text-danger">*</span></label>
                <input id="started_date"
                    class="form-control required @error('started_date') is-invalid @enderror"
                    name="started_date"
                    type="date"
                    value="{{ isset($policy) ? $policy->started_date->format('Y-m-d') : old('started_date', '') }}">
                @error('started_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label"
                    for="ended_date">Periode Akhir Pertanggungan <span class="text-danger">*</span></label>
                <input id="ended_date"
                    class="form-control required @error('ended_date') is-invalid @enderror"
                    name="ended_date"
                    type="date"
                    value="{{ isset($policy) ? $policy->ended_date->format('Y-m-d') : old('ended_date', '') }}">
                @error('ended_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-12 mb-3">
                <label class="form-label"
                    for="currency_code">Mata Uang<span class="text-danger">*</span></label>

                <select id="currency_code"
                    class="form-select create-select2 required @error('currency_code') is-invalid @enderror"
                    data-placeholder="Pilih Mata Uang"
                    data-selected-value="{{ $policy->currency_code ?? '' }}"
                    name="currency_code">
                    <option value=""></option>
                    @foreach ($currencies as $currency)
                        <option value="{{ $currency->code }}"
                            {{ isset($policy) && $policy->currency_code == $currency->code ? 'selected' : (old('currency_code') == $currency->code ? 'selected' : '') }}>
                            {{ $currency->code }} - {{ $currency->name }}
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
                        <div class="alert alert-danger py-2">
                            <strong>Total Persentase Coverage: <span class="coverage_percentage_total">0.00</span>%</strong><br>
                        </div>
                    </div>
                    <div class="col-md-auto mb-3">
                        <button class="btn btn-primary btn-sm add-item-btn float-end"
                            data-target="item-coverage"
                            type="button">
                            <i class="icon-plus me-2"></i> Tambah Coverage
                        </button>
                    </div>
                </div>
                <div class="item-list border rounded mb-2 px-3 py-2">
                    @forelse ((isset($policy) && $policy ? ($policy->policyCoverages ?? []) : []) as $index => $policyCoverage)
                        <div class="row align-items-end item item-coverage {{ $index != 0 ? 'border-top py-2' : ' pt-2' }}">
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Coverage <span class="item-count">{{ $index + 1 }}</span></label>
                                <select class="form-select create-select2 required"
                                    data-placeholder="Pilih Coverage"
                                    name="coverage_code[]"
                                    style="width: 100%;">
                                    <option value="">- Pilih Coverage -</option>
                                    @foreach ($coverages as $coverage)
                                        <option value="{{ $coverage->code }}"
                                            {{ $coverage->code == $policyCoverage->coverage_code ? 'selected' : '' }}>
                                            {{ $coverage->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md mb-2">
                                <label class="form-label">Persentase Coverage</label>
                                <div class="input-group">
                                    <input class="form-control"
                                        type="number"
                                        name="coverage_percentage[]"
                                        value="{{ $policyCoverage->percentage * 100 }}"
                                        placeholder="0"
                                        min="0"
                                        max="100"
                                        step="0.00001">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <div class="col-md-auto mb-2">
                                <button class="btn btn-danger btn-xs remove-item-btn"
                                    type="button">
                                    <i class="icon-trash me-2"></i> Hapus
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="row align-items-end item item-coverage mb-2">
                            <div class="col-md-6">
                                <label class="form-label">Coverage <span class="item-count">1</span></label>
                                <select class="form-select create-select2 required"
                                    data-placeholder="Pilih Coverage"
                                    name="coverage_code[]"
                                    style="width: 100%;">
                                    <option value="">- Pilih Coverage -</option>
                                    @foreach ($coverages as $coverage)
                                        <option value="{{ $coverage->code }}">
                                            {{ $coverage->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md">
                                <label class="form-label">Persentase Coverage</label>
                                <div class="input-group">
                                    <input class="form-control required coverage-percentage"
                                        type="number"
                                        name="coverage_percentage[]"
                                        value
                                        placeholder="0"
                                        min="0"
                                        max="100"
                                        step="0.00001">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <div class="col-md-auto my-2">
                                <button class="btn btn-danger btn-xs remove-item-btn"
                                    type="button"
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
                        <div class="alert alert-danger py-2">
                            <strong>Total Persentase Asuransi: <span id="insurance_percentage_total">0.00</span>%</strong><br>
                        </div>
                    </div>
                    <div class="col-md-auto mb-3">
                        <button class="btn btn-primary btn-sm add-item-btn float-end"
                            data-target="item-1"
                            type="button">
                            <i class="icon-plus me-2"></i> Tambah Asuransi
                        </button>
                    </div>
                </div>
                <div class="item-list border rounded mb-2 px-3 py-2">
                    @forelse ((isset($policy) && $policy ? ($policy->policyInsurances ?? []) : []) as $index => $policyInsurance)
                        <div class="row align-items-end item item-1  {{ $index != 0 ? 'border-top py-2' : ' pt-2' }}">
                            <div class="col-md-5 mb-2">
                                <label class="form-label">Asuransi <span class="item-count">1</span></label>
                                <select class="form-select create-select2 required"
                                    data-placeholder="Pilih Asuransi"
                                    name="insurance_id[]"
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
                                    <input class="form-control required insurance-percentage"
                                        type="number"
                                        name="insurance_percentage[]"
                                        value="{{ isset($policyInsurance) ? $policyInsurance->percentage * 100 : '' }}"
                                        placeholder="0"
                                        min="0"
                                        max="100"
                                        step="0.00001">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <div class="col-md-2 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input is-main-checkbox"
                                        type="checkbox"
                                        name="is_main[]"
                                        value="1"
                                        {{ isset($policyInsurance) && $policyInsurance->is_main ? 'checked' : '' }}>
                                    <label class="form-check-label">Asuransi Utama</label>
                                </div>
                            </div>
                            <div class="col-md-auto mb-2">
                                <button class="btn btn-danger btn-xs remove-item-btn"
                                    type="button">
                                    <i class="icon-trash me-2"></i> Hapus
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="row align-items-end item item-1 mb-2">
                            <div class="col-md-5 mb-2">
                                <label class="form-label">Asuransi <span class="item-count">1</span></label>
                                <select class="form-select create-select2 required"
                                    data-placeholder="Pilih Asuransi"
                                    name="insurance_id[]"
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
                                <div class="input-group">
                                    <input class="form-control required insurance-percentage"
                                        type="number"
                                        name="insurance_percentage[]"
                                        value
                                        placeholder="0"
                                        min="0"
                                        max="100"
                                        step="0.00001">
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
                                <button class="btn btn-danger btn-xs remove-item-btn"
                                    type="button"
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
        </div>
    </div>
    <div class="card mb-3 p-3 shadow-sm">
        <div class="row">
            {{-- OBJECT INFO START --}}
            <div class="col-md-12 mb-3">
                <div id="object-item-container"
                    class="row multiple-item-container">
                    <div class="col-md mt-2 mb-3">
                        <h5 class="text-primary">Objek Pertanggungan</h5>
                        <hr class="separator">
                    </div>
                    <div class="col-md-auto mt-2 mb-3">
                        <span class="add-item-btn btn btn-primary float-end"
                            data-target="item-object"><i class="ico icon-plus me-2"></i> Tambah Objek Pertanggungan</span>
                    </div>

                    <div class="col-md-12 mb-3 item-list">
                        {{-- Start Item Object Template --}}
                        <div class="row align-items-end item item-object item-template border border-primary py-3 mx-0 mb-3"
                            style="display: none">
                            <div class="col-md-6 mb-3">
                                <label class="form-label"
                                    for="object_id">Nama Objek (Tertanggung) <span class="item-count"></span><span class="text-danger">*</span></label>
                                <select class="form-control create-select2 object-select @error('object_id') is-invalid @enderror"
                                    name="object_id[]"
                                    data-placeholder="Pilih Objek">
                                    <option></option>
                                    @foreach ($objects ?? [] as $object)
                                        <option value="{{ $object->id }}">
                                            {{ $object->name }} - {{ $object->keterangan }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('object_id[]')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3 text-end">
                                <span class="remove-item-btn btn btn-danger btn-xs"
                                    style="display:none"><i class="ico icon-trash"></i> Hapus Objek</span>
                            </div>
                            <div id="object-form-livewire"
                                class="col-md-12 object-forms-container"
                                style="display:none;">
                                <input type="hidden"
                                    value=""name="class"
                                    disabled>
                                @livewire('object-form', ['objectId' => null, 'isDisabled' => true], key('object-form-template-' . uniqid()))
                            </div>
                        </div>
                        {{-- End Item Object Template --}}

                        {{-- Existing Objects --}}
                        @forelse ((isset($policy) && $policy ? ($policy->policyObjects ?? []) : []) as $index => $policyObject)
                            <div class="row align-items-end item item-object border border-primary py-3 mx-0 mb-3"
                                data-item-index="{{ $index + 1 }}">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"
                                        for="object_id">Nama Objek (Tertanggung) <span class="item-count">{{ $index + 1 }}</span><span class="text-danger">*</span></label>
                                    <select class="form-control create-select2 object-select @error('object_id') is-invalid @enderror"
                                        name="object_id[]"
                                        data-placeholder="Pilih Objek"
                                        required>
                                        <option value=""></option>
                                        @foreach ($objects ?? [] as $object)
                                            <option value="{{ $object->id }}"
                                                {{ $object->id == $policyObject->object_id ? 'selected' : '' }}>
                                                {{ $object->name }} - {{ $object->keterangan }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('object_id[]')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3 text-end order-xs-last">
                                    <span class="remove-item-btn btn btn-danger btn-xs">
                                        <i class="ico icon-trash"></i> Hapus Objek
                                    </span>
                                </div>

                                {{-- Object Form --}}
                                <div class="col-md-12 object-forms-container">
                                    @livewire('object-form', ['objectId' => $policyObject->object_id ?? null], key('object-form-existing-' . $index . '-' . ($policyObject->id ?? uniqid())))
                                </div>
                            </div>
                        @empty
                            <div class="row align-items-end item item-object border border-primary py-3 mx-0 mb-3"
                                data-item-index="1">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"
                                        for="object_id">Nama Objek (Tertanggung) <span class="item-count">1</span><span class="text-danger">*</span></label>
                                    <select class="form-control create-select2 object-select @error('object_id') is-invalid @enderror"
                                        name="object_id[]"
                                        data-placeholder="Pilih Objek"
                                        required>
                                        <option value=""></option>
                                        @foreach ($objects ?? [] as $object)
                                            <option value="{{ $object->id }}">
                                                {{ $object->name }} - {{ $object->keterangan }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('object_id[]')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3 text-end">
                                    <span class="remove-item-btn btn btn-danger btn-xs"
                                        style="display:none"><i class="ico icon-trash"></i> Hapus Objek
                                    </span>
                                </div>

                                {{-- Object Form --}}
                                <div class="col-md-12 object-forms-container"
                                    style="display:none;">
                                    @livewire('object-form', ['objectId' => null], key('object-form-empty-' . uniqid()))
                                </div>
                            </div>
                        @endforelse

                    </div>
                </div>
                <input id="object_ready"
                    type="hidden"
                    value="0"
                    disabled>
            </div>
        </div>
    </div>
</div>

@script
    <script>
        // Listen for objectLoaded event
        Livewire.on('objectLoaded', (event) => {
            var $template = $('#object-form-livewire');
            var className = $template.find('input[name="class"]').val();

            if (className) {
                var $cloneContent = $template.clone();
                // $cloneContent.find('.required').each(function() {
                //     $(this).attr('required', 'required').prop('disabled', true);
                // });
                $cloneContent.find('.required').each(function() {
                    $(this).prop("required", true).prop("disabled", false);
                });
                // Split the class string and find elements that have all these classes
                var classArray = className.split(' ').filter(c => c.trim() !== '');
                var selector = '.' + classArray.join('.');
                var $targetContainer = $('#object-item-container').find(selector).first();

                if ($targetContainer.length) {
                    // console.log('Target container found:', $targetContainer);
                    $targetContainer.find('.object-forms-container').html($cloneContent.html());
                    $targetContainer.find('.object-forms-container input, .object-forms-container textarea, .object-forms-container select, .object-forms-container button').prop('disabled', false);

                    // Initialize select2 for all add-select2 elements
                    $targetContainer.find('.add-select2').each(function() {
                        var $select = $(this);
                        if (!$select.hasClass('select2-hidden-accessible')) {
                            var placeholder = $select.data('placeholder') || 'Pilih Data';
                            $select.select2({
                                placeholder: placeholder,
                                allowClear: true,
                                width: '100%'
                            });
                        }
                    });
                } else {
                    // console.log('Target container not found for selector:', selector);
                }


                // Clear the class value
                $template.find('input[name="class"]').val('');


            } else {
                // console.log('No className found in input[name="class"]');
            }

            // Replace all input select and textarea name base on item-index
            var $itemObjects = $('#object-item-container').find('.item-object');
            renameInputNameByIndex($itemObjects);
            sumInputValues('input[name="object_value_input"]', '#object_value');
            update_payment(); // Trigger calculation when policy is loaded
        });

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
                    allowClear: true,
                    width: '100%'
                });
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
                    $('.create-select2').each(function() {
                        if (!$(this).hasClass('select2-hidden-accessible')) {
                            var placeholder = $(this).data('placeholder') || 'Pilih Data';
                            $(this).select2({
                                placeholder: placeholder,
                                allowClear: true,
                                width: '100%'
                            });
                        }
                    });
                }, 150);
            });
        });

        // Shared function to handle object selection
        function handleObjectSelection($select, selectedValue) {
            // console.log('Handling object selection for value:', selectedValue);
            var $itemContainer = $select.closest('.item-object');
            var $formContainer = $itemContainer.find('.object-forms-container');

            if (selectedValue) {
                $formContainer.show();

                // Find the livewire component inside this container
                var formComponent = $formContainer.find('[wire\\:id]').first();
                if (formComponent.length) {
                    var componentId = formComponent.attr('wire:id');
                    var component = Livewire.find(componentId);
                    if (component) {
                        component.set('objectId', selectedValue);
                    }
                }

                if ($itemContainer.hasClass('item-template')) {
                    $class = $itemContainer.attr('class');
                    // console.log('Setting class value:', $class);
                    $('#object-form-livewire').find('input[name="class"]').val($class);
                }

            } else {
                $formContainer.hide();
            }
        }

        // Handle object select for both Select2 and regular selects
        $(document).on('select2:select change', '.object-select', function(e) {
            var selectedValue = $(this).val();
            handleObjectSelection($(this), selectedValue);
        });

        $(document).on('select2:unselect', '.object-select', function(e) {
            handleObjectSelection($(this), null);
        });


        // Initialize on component load
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                if (!$('#currency_code').hasClass('select2-hidden-accessible')) {
                    $('#currency_code').select2({
                        placeholder: 'Pilih Mata Uang',
                        allowClear: true,
                        width: '100%'
                    });
                }

                // Initialize all .create-select2 elements
                $('.create-select2').each(function() {
                    if (!$(this).hasClass('select2-hidden-accessible')) {
                        var placeholder = $(this).data('placeholder') || 'Pilih Data';
                        $(this).select2({
                            placeholder: placeholder,
                            allowClear: true
                        });
                    }
                });

                // Initialize all .add-select2 elements (including interest fields)
                $('.add-select2').each(function() {
                    if (!$(this).hasClass('select2-hidden-accessible')) {
                        var placeholder = $(this).data('placeholder') || 'Pilih Data';
                        $(this).select2({
                            placeholder: placeholder,
                            allowClear: true,
                            width: '100%'
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

        $(document).on('input keyup change blur', 'input[name="interest_value[]"]', function() {

            if (typeof window.toggleNegativeWarning === 'function') {
                window.toggleNegativeWarning(this);
            }

            var $container = $(this).closest('#new-object');
            var $totalInput = $container.find('input[name="object_value_input"]');

            setTimeout(function() {
                if ($totalInput.length > 0 && typeof window.toggleNegativeWarning === 'function') {
                    $totalInput.each(function() {
                        window.toggleNegativeWarning(this);
                    });
                }
            }, 100);
        });

        $('.check-negative').each(function() {
            if (typeof window.toggleNegativeWarning === 'function') {
                window.toggleNegativeWarning(this);
            }
        });
    </script>
@endscript
