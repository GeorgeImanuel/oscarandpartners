<div id="new-object"
    class="row gt-3">
    <div class="col-md-6 mb-3">
        <label class="form-label">Objek Occupation<span class="text-danger">*</span></label>
        <select class="form-select required add-select2 @error('object_occupation_id') is-invalid @enderror"
            name="object_occupation_id[{{ isset($object) ? $object->id : '' }}]"
            data-placeholder="Pilih Objek Occupation"
            style="width: 100%;"
            {{ $isDisabled ? 'disabled' : '' }}>
            <option value="">- Pilih Objek Occupation -</option>
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
            for="object_keterangan">Alamat <span class="text-danger">*</span></label>
        <textarea class="form-control required @error('object_keterangan') is-invalid @enderror"
            id="object_keterangan"
            name="object_keterangan[{{ isset($object) ? $object->id : '' }}]"
            rows="3"
            {{ $isDisabled ? 'disabled' : '' }}>{{ isset($object) ? $object->keterangan : old('object_keterangan', '') }}</textarea>
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
                name="tjh[{{ isset($object) ? $object->id : '' }}]"
                type="text"
                value="{{ isset($object) ? $object->tjh : old('tjh', '') }}"
                {{ $isDisabled ? 'disabled' : '' }} />
            @error('tjh')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-12 mb-3 multiple-item-container">
        <label>Komponen Objek (Interests)</label>
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
                                <select class="form-select add-select2 required"
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
                                    <span class="input-group-text">Rp</span>
                                    <input type="number"
                                        class="form-control"
                                        name="interest_value[][]"
                                        value="{{ $objectInterest->value ?? 0 }}"
                                        {{ $isDisabled ? 'disabled' : '' }}>
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
                            <select class="form-select add-select2 required"
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
                                <span class="input-group-text">Rp</span>
                                <input type="number"
                                    class="form-control"
                                    name="interest_value[][]"
                                    {{ $isDisabled ? 'disabled' : '' }}>
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
        <label class="form-label"
            for="object_value_input">Harga Pertanggungan <span class="text-danger">*</span></label>
        <div class="input-group mb-3">
            <span class="input-group-text">Rp</span>
            <input class="form-control required @error('object_value_input') is-invalid @enderror"
                name="object_value_input"
                type="number"
                step="0.00001"
                min="0"
                readonly
                value="{{ isset($object) && $object ? $object->object_value ?? 0 : 0 }}" />
        </div>
    </div>
</div>

@script
    <script>
        $(document).on('change', 'select[name^="object_occupation_id"]', function() {
            const $item = $(this).closest('.item');
            console.log($(this).val());
            if ($(this).val() == 7)
                $item.find('.tjh-input-group').show();
            else {
                $item.find('.tjh-input-group').hide();
            }
        });
    </script>
@endscript
