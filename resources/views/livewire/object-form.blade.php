<div id="new-object"
    class="row gt-3"
    style="display:none;">
    <div class="col-md-12 mb-3">
        <label class="form-label"
            for="object_occupation_id">Objek Occupation<span class="text-danger">*</span></label>
        <select class="form-select required add-select2 @error('object_occupation_id') is-invalid @enderror"
            name="object_occupation_id"
            id="object_occupation_id"
            data-placeholder="Pilih Objek Occupation"
            style="width: 100%;">
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

    <div class="col-md-12 mb-3">
        <label class="form-label"
            for="object_keterangan">Keterangan <span class="text-danger">*</span></label>
        <textarea class="form-control required @error('object_keterangan') is-invalid @enderror"
            id="object_keterangan"
            name="object_keterangan"
            rows="3">{{ isset($object) ? $object->keterangan : old('object_keterangan', '') }}</textarea>
        @error('object_keterangan')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-12 mb-3">
        <label class="form-label"
            for="object_city">Kota <span class="text-danger">*</span></label>
        <input class="form-control required @error('object_city') is-invalid @enderror"
            id="object_city"
            name="object_city"
            type="text"
            value="{{ isset($object) ? $object->city : old('object_city', '') }}" />
        @error('object_city')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-12 mb-3 multiple-item-container">
        <label>Bagian Objek</label>
        <button type="button"
            class="btn btn-primary btn-sm float-end add-item-btn"
            data-target="object-interest-item">
            <i class="fa fa-plus"></i> Tambah Bagian
        </button>
        <div class="item-list pt-2">
            <div class="mt-2 px-3 py-3 border rounded item object-interest-item">
                <div class="row align-items-end">
                    <div class="col-md">
                        <label class="form-label">Bagian <span class="item-count">1</span></label>
                        <select class="form-select add-select2 required"
                            name="interest_id[]"
                            data-placeholder="Pilih Bagian"
                            style="width: 100%;">
                            <option value=""></option>
                            @foreach ($interests as $interest)
                                <option value="{{ $interest->id }}">
                                    {{ $interest->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-auto">
                        <label class="form-label">Harga Bagian <span class="item-count">1</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number"
                                class="form-control"
                                name="value"
                                onkeyup="updateObjectValue()">
                        </div>
                    </div>
                    <div class="col-md-1 pt-3">
                        <button type="button"
                            class="btn btn-danger btn-xs remove-item-btn"
                            style="display:none">
                            <i class="fa fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 mb-3">
        <label class="form-label"
            for="object_value_input">Harga Pertanggungan <span class="text-danger">*</span></label>
        <div class="input-group mb-3">
            <span class="input-group-text">Rp</span>
            <input class="form-control required @error('object_value_input') is-invalid @enderror"
                id="object_value_input"
                name="object_value_input"
                type="number"
                step="0.00001"
                min="0"
                value="{{ isset($object) ? $object->object_value : old('object_value_input', 0) }}" />
            @error('object_value_input')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
