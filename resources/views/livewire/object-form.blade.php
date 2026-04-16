<div id="new-object"
    class="row gt-3">
    <div class="col-md-6 mb-3">
        <label class="form-label">Okupasi<span class="text-danger">*</span></label>
        <select class="form-select required create-select2 @error('object_occupation_id') is-invalid @enderror"
            name="object_occupation_id[]"
            data-placeholder="Pilih Okupasi"
            style="width: 100%;"
            {{ $isDisabled ? 'disabled' : '' }}>
            <option value="">- Pilih Okupasi -</option>
            @foreach ($objectOccupations ?? [] as $objectOccupation)
                <option value="{{ $objectOccupation->id }}"
                    {{ isset($object) && $object->object_occupation_id == $objectOccupation->id ? 'selected' : (old('object_occupation_id') == $objectOccupation->id ? 'selected' : '') }}>
                    {{ $objectOccupation->name }}
                </option>
            @endforeach
        </select>
        @error('object_occupation_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    
    <div class="col-md-6 mb-3">
        <label class="form-label"
            for="object_keterangan">Obyek Pertanggungan <span class="text-danger">*</span></label>
        <textarea class="form-control required @error('object_keterangan') is-invalid @enderror"
            id="object_keterangan"
            name="object_keterangan[]"
            rows="3"
            {{ $isDisabled ? 'disabled' : '' }}>{{ isset($object) ? $object->keterangan : (is_array(old('object_keterangan')) ? '' : old('object_keterangan', '')) }}</textarea>
        @error('object_keterangan')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="col-md-6 mb-3 tjh-input-group"
        style="{{ isset($object) && $object->object_occupation_id != 7 ? 'display:none;' : '' }}">
        <label class="form-label"
            for="object_name">Nilai TJH (Rp)</label>
        <div class="input-group mb-3">
            <span class="input-group-text">Rp</span>
            <input class="form-control required @error('tjh') is-invalid @enderror"
                name="tjh[]"
                type="text"
                value="{{ isset($object) ? $object->tjh : (is_array(old('tjh')) ? '' : old('tjh', '')) }}"
                {{ $isDisabled ? 'disabled' : '' }} />
            @error('tjh')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-12 mb-3 multiple-item-container">
        <label>Komponen Obyek (Interests)</label>
        <button type="button"
            class="btn btn-primary btn-sm float-end add-item-btn"
            data-target="object-interest-item"
            {{ $isDisabled ? 'disabled' : '' }}>
            <i class="icon-plus me-2"></i>Tambah Interest
        </button>
        <div class="item-list pt-2">
            @if (isset($object) && $object && $object->objectInterests && $object->objectInterests->count() > 0)
                @foreach ($object->objectInterests as $index => $objectInterest)
                    <div class="mt-2 px-3 py-3 border rounded item object-interest-item">
                        <div class="row align-items-end">
                            <div class="col-md-6">
                                <label class="form-label">Interest <span class="item-count">{{ $index + 1 }}</span></label>
                                <select class="form-select create-select2 required"
                                    name="interest_id[][]"
                                    data-placeholder="Pilih Interest"
                                    style="width: 100%;"
                                    {{ $isDisabled ? 'disabled' : '' }}>
                                    <option value=""></option>
                                    @foreach ($interests as $interest)
                                        <option value="{{ $interest->id }}"
                                            {{ $objectInterest->interest_id == $interest->id ? 'selected' : '' }}>
                                            {{ $interest->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label">Nilai Interest <span class="item-count">{{ $index + 1 }}</span></label>
                                <div class="input-group">
                                    
                                    <input type="number"
                                        class="form-control required check-negative"
                                        name="interest_value[][]"
                                        value="{{ $objectInterest->value ?? 0 }}"
                                        {{ $isDisabled ? 'disabled' : '' }}>
                                </div>
                                <div class="text-warning small negative-warning" style="display:none; font-weight: 500;">
                                    <i class="fa fa-exclamation-triangle me-1"></i> Nilai Negatif
                                </div>
                            </div>
                            <div class="col-md-1 pt-3">
                                <button type="button"
                                    class="btn btn-danger btn-xs remove-item-btn"
                                    style="{{ $index == 0 ? 'display:none;' : '' }}"
                                    {{ $isDisabled ? 'disabled' : '' }}>
                                    <i class="icon-trash me-2"></i> Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="mt-2 px-3 py-3 border rounded item object-interest-item">
                    <div class="row align-items-end">
                        <div class="col-md-6">
                            <label class="form-label">Interest <span class="item-count">1</span></label>
                            <select class="form-select create-select2 required"
                                name="interest_id[][]"
                                data-placeholder="Pilih Interest"
                                style="width: 100%;"
                                {{ $isDisabled ? 'disabled' : '' }}>
                                <option value=""></option>
                                @foreach ($interests as $interest)
                                    <option value="{{ $interest->id }}">
                                        {{ $interest->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Nilai Interest <span class="item-count">1</span></label>
                            <div class="input-group">
                                <input type="number"
                                    class="form-control check-negative"
                                    name="interest_value[][]"
                                    {{ $isDisabled ? 'disabled' : '' }}>
                            </div>
                            <div class="text-warning small negative-warning" style="display:none; font-weight: 500;">
                                <i class="fa fa-exclamation-triangle me-1"></i> Nilai Negatif
                            </div>
                        </div>
                        <div class="col-md-1 pt-3">
                            <button type="button"
                                class="btn btn-danger btn-xs remove-item-btn"
                                style="display:none;"
                                {{ $isDisabled ? 'disabled' : '' }}>
                                <i class="icon-trash me-2"></i> Hapus
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="col-md-12 mb-3 multiple-item-container object-coverage-section"
        style="{{ $policyCoverageExists ? 'display:none;' : '' }}">
        <label>Coverage Per Obyek</label>
        <button type="button"
            class="btn btn-primary btn-sm float-end add-item-btn"
            data-target="object-coverage-item"
            {{ $isDisabled ? 'disabled' : '' }}>
            <i class="icon-plus me-2"></i>Tambah Coverage
        </button>
        <div class="item-list pt-2">
            @if (isset($object) && $object && $object->objectCoverages && $object->objectCoverages->count() > 0)
                @foreach ($object->objectCoverages as $index => $objectCoverage)
                    <div class="mt-2 px-3 py-3 border rounded item object-coverage-item">
                        <div class="row align-items-end">
                            <div class="col-md-6">
                                <label class="form-label">Coverage <span class="item-count">{{ $index + 1 }}</span></label>
                                <select class="form-select create-select2"
                                    name="object_coverage_code[][]"
                                    data-placeholder="Pilih Coverage"
                                    style="width: 100%;"
                                    {{ $isDisabled ? 'disabled' : '' }}>
                                    <option value=""></option>
                                    @foreach ($coverages as $coverage)
                                        <option value="{{ $coverage->code }}"
                                            {{ $objectCoverage->coverage_code == $coverage->code ? 'selected' : '' }}>
                                            {{ $coverage->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label">Persentase Coverage <span class="item-count">{{ $index + 1 }}</span></label>
                                <div class="input-group">
                                    <input type="number"
                                        class="form-control object-coverage-percentage"
                                        name="object_coverage_percentage[][]"
                                        step="0.00001"
                                        min="0"
                                        max="100"
                                        value="{{ ($objectCoverage->percentage ?? 0) * 100 }}"
                                        {{ $isDisabled ? 'disabled' : '' }}>
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <div class="col-md-1 pt-3">
                                <button type="button"
                                    class="btn btn-danger btn-xs remove-item-btn"
                                    style="{{ $index == 0 ? 'display:none;' : '' }}"
                                    {{ $isDisabled ? 'disabled' : '' }}>
                                    <i class="icon-trash me-2"></i> Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="mt-2 px-3 py-3 border rounded item object-coverage-item">
                    <div class="row align-items-end">
                        <div class="col-md-6">
                            <label class="form-label">Coverage <span class="item-count">1</span></label>
                            <select class="form-select create-select2"
                                name="object_coverage_code[][]"
                                data-placeholder="Pilih Coverage"
                                style="width: 100%;"
                                {{ $isDisabled ? 'disabled' : '' }}>
                                <option value=""></option>
                                @foreach ($coverages as $coverage)
                                    <option value="{{ $coverage->code }}">
                                        {{ $coverage->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Persentase Coverage <span class="item-count">1</span></label>
                            <div class="input-group">
                                <input type="number"
                                    class="form-control object-coverage-percentage"
                                    name="object_coverage_percentage[][]"
                                    step="0.00001"
                                    min="0"
                                    max="100"
                                    {{ $isDisabled ? 'disabled' : '' }}>
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-md-1 pt-3">
                            <button type="button"
                                class="btn btn-danger btn-xs remove-item-btn"
                                style="display:none;"
                                {{ $isDisabled ? 'disabled' : '' }}>
                                <i class="icon-trash me-2"></i> Hapus
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="col-md-12 mb-3 input-group-solid">
        <label class="form-label" for="object_value_input">Harga Pertanggungan <span class="text-danger">*</span></label>
        <div class="input-group mb-3">
            <input class="form-control required check-negative @error('object_value_input') is-invalid @enderror"
                name="object_value_input"
                type="number"
                step="0.00001"
                min="0"
                readonly
                value="{{ isset($object) && $object ? $object->object_value ?? 0 : 0 }}" />
        </div>
        
        <div class="text-warning small negative-warning" style="display:none; font-weight: 500;">
            <i class="fa fa-exclamation-triangle me-1"></i> Nilai Negatif
        </div>
    </div>
</div>

@script
    <script>
        function isPolicyCoverageFilled() {
            let filled = false;

            function toNum(raw) {
                if (typeof parseNumber === 'function') {
                    return parseNumber(raw);
                }
                if (raw === null || raw === undefined || raw === '') {
                    return 0;
                }
                const n = parseFloat(String(raw).replace(',', '.'));
                return isNaN(n) ? 0 : n;
            }

            $('#policy-detail-form .item-coverage').not('.no-print').each(function() {
                if ($(this).css('display') === 'none') {
                    return;
                }

                const code = $(this).find('select[name="coverage_code[]"]').val();
                const percentage = toNum($(this).find('input[name="coverage_percentage[]"]').val());

                if ((code && String(code).trim() !== '') || percentage > 0) {
                    filled = true;
                    return false;
                }
            });

            return filled;
        }

        function toggleObjectCoverageSection() {
            const policyCoverageExists = isPolicyCoverageFilled();

            $('.object-coverage-section').each(function() {
                const $section = $(this);
                const $inputs = $section.find('input, select, textarea, button');

                if (policyCoverageExists) {
                    $section.hide();
                    $inputs.prop('disabled', true);
                } else {
                    $section.show();
                    $inputs.prop('disabled', false);
                }
            });
        }

        // 1. Logic existing untuk Occupation (biarkan saja)
        $(document).on('change', 'select[name^="object_occupation_id"]', function() {
            const $item = $(this).closest('.item');
            if ($(this).val() == 7)
                $item.find('.tjh-input-group').show();
            else {
                $item.find('.tjh-input-group').hide();
            }
        });

        // 2. Listener Input Manual (Untuk field yang diketik user: Interest, TJH)
        $(document).on('input keyup change blur', 'input[name="interest_value[]"], input[name="tjh"]', function() {
            if (typeof window.toggleNegativeWarning === 'function') {
                window.toggleNegativeWarning(this);
            }
        });

        // 3. MONITOR OTOMATIS (Polling) untuk Field Readonly (Harga Pertanggungan)
        // Ini akan mengecek setiap 300ms apakah nilai berubah negatif/positif
        // Jauh lebih stabil daripada mengandalkan trigger event manual
        setInterval(function() {
            $('input[name="object_value_input"]').each(function() {
                var currentVal = $(this).val();
                
                // Cek apakah fungsi global sudah siap
                if (typeof window.toggleNegativeWarning === 'function') {
                    // Panggil fungsi cek negatif
                    window.toggleNegativeWarning(this);
                }
            });
        }, 300); // Cek setiap 300 milidetik (tidak berat untuk browser)

        // 4. Initial Load Check
        setTimeout(function() {
            $('.check-negative').each(function() {
                if (typeof window.toggleNegativeWarning === 'function') {
                    window.toggleNegativeWarning(this);
                }
            });

            toggleObjectCoverageSection();
        }, 500);

        $(document).on('input change keyup blur', '#policy-detail-form input[name="coverage_percentage[]"]', function() {
            toggleObjectCoverageSection();
        });

        $(document).on('change select2:select select2:clear', '#policy-detail-form select[name="coverage_code[]"]', function() {
            toggleObjectCoverageSection();
        });

        $(document).on('click', '#policy-detail-form .add-item-btn[data-target="item-coverage"], #policy-detail-form .item-coverage .remove-item-btn', function() {
            setTimeout(function() {
                toggleObjectCoverageSection();
            }, 250);
        });

        document.addEventListener('policyCoverageVisibilityChanged', function() {
            toggleObjectCoverageSection();
        });
    </script>
@endscript
