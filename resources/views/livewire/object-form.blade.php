<div id="new-object" class="row gt-3" style="display:none;">
    <div class="col-md-12 mb-3">
        <label class="form-label" for="object_type_id">Tipe Objek <span class="text-danger">*</span></label>
        <select class="form-select add-single-select2 @error('object_type_id') is-invalid @enderror" name="object_type_id" id="object_type_id" required style="width: 100%;">
            <option value="">- Pilih Tipe Objek -</option>
            @foreach ($objectTypes ?? [] as $objectType)
                <option value="{{ $objectType->id }}" {{ isset($object) && $object->object_type_id == $objectType->id ? 'selected' : (old('object_type_id') == $objectType->id ? 'selected' : '') }}>
                    {{ $objectType->name }}
                </option>
            @endforeach
        </select>
        @error('object_type_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-12 mb-3">
        <label class="form-label" for="object_address">Alamat <span class="text-danger">*</span></label>
        <textarea class="form-control @error('object_address') is-invalid @enderror" id="object_address" name="object_address" rows="3" required>{{ isset($object) ? $object->address : old('object_address', '') }}</textarea>
        @error('object_address')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-12 mb-3">
        <label class="form-label" for="object_city">Kota <span class="text-danger">*</span></label>
        <input class="form-control @error('object_city') is-invalid @enderror" id="object_city" name="object_city" type="text" value="{{ isset($object) ? $object->city : old('object_city', '') }}" required />
        @error('object_city')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-12 mb-3 multiple-item-container">
        <label>Bagian Objek</label>
        <button type="button" class="btn btn-primary btn-sm float-end add-item-btn" data-target="object-part-item">
            <i class="fa fa-plus"></i> Tambah Bagian
        </button>
        <div class="item-list pt-2">
            <div class="mt-2 px-3 py-2 border rounded item object-part-item">
                <div class="row align-items-end">
                    <div class="col-md">
                        <label class="form-label">Bagian <span class="item-count">1</span></label>
                        <select class="form-select add-select2" name="part_id[]" style="width: 100%;">
                            <option value=""></option>
                            @foreach ($parts as $part)
                                <option value="{{ $part->id }}">
                                    {{ $part->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-auto">
                        <label class="form-label">Harga Bagian <span class="item-count">1</span></label>
                        <input type="number" class="form-control" name="value">
                    </div>
                    <div class="col-md-1 pt-3">
                        <button type="button" class="btn btn-danger btn-xs remove-item-btn" style="display:none">
                            <i class="fa fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 mb-3">
        <label class="form-label" for="object_value_input">Harga Pertanggungan (Rp) <span class="text-danger">*</span></label>
        <input class="form-control @error('object_value_input') is-invalid @enderror" id="object_value_input" name="object_value_input" type="number" step="0.01" min="0" value="{{ isset($object) ? $object->object_value : old('object_value_input', 0) }}" required />
        @error('object_value_input')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
