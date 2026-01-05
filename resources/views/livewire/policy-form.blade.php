<div id="new-policy" class="row gt-3" style="display:none;">
    <div class="col-md-12 mb-3">
        <label class="form-label" for="started_date">Periode Awal Pertanggungan <span class="text-danger">*</span></label>
        <input class="form-control @error('started_date') is-invalid @enderror" id="started_date" name="started_date" type="date" value="{{ isset($policy) ? $policy->started_date->format('Y-m-d') : old('started_date', '') }}" required />
        @error('started_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-12 mb-3">
        <label class="form-label" for="ended_date">Periode Akhir Pertanggungan <span class="text-danger">*</span></label>
        <input class="form-control @error('ended_date') is-invalid @enderror" id="ended_date" name="ended_date" type="date" value="{{ isset($policy) ? $policy->ended_date->format('Y-m-d') : old('ended_date', '') }}" required />
        @error('ended_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-12 mb-3">
        <label class="form-label" for="coverage_code">Coverage <span class="text-danger">*</span></label>
        <select class="form-select add-single-select2 @error('coverage_code') is-invalid @enderror" name="coverage_code" id="coverage_code" required style="width: 100%;">
            <option value="">- Pilih Coverage -</option>
            @foreach ($coverages as $coverage)
                <option value="{{ $coverage->code }}" {{ isset($policy) && $coverage->code == $policy->coverage_code ? 'selected' : (old('coverage_code') == $coverage->code ? 'selected' : '') }}>
                    {{ $coverage->name }}
                </option>
            @endforeach
        </select>
        @error('coverage_code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-12 mb-3">
        <label class="form-label" for="coverage_percentage">Persentase Coverage (%) <span class="text-danger">*</span></label>
        <input class="form-control @error('coverage_percentage') is-invalid @enderror" id="coverage_percentage" name="coverage_percentage" type="number" step="0.01" min="0" max="100" value="{{ isset($policy) ? $policy->coverage_percentage : old('coverage_percentage', 0) }}" required />
        <small class="text-muted">Contoh: 0.03 untuk 0.03%</small>
        @error('coverage_percentage')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-12 mb-3">
        <label class="form-label" for="object_id">Objek <span class="text-danger">*</span></label>
        <select class="add-single-select2 @error('object_id') is-invalid @enderror" name="object_id" id="object_id" required style="width: 100%;">
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
    </div>

</div>
