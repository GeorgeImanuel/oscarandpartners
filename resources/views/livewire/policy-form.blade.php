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
            @foreach ($coverages ?? [] as $coverage)
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

    <div class="col-md-12 mb-3 multiple-item-container">
        <label>List Asuransi</label>
        <div class="row">
            <div class="col-md">
                <div id="percentage-info" class="alert alert-info">
                    Total Persentase Asuransi: <span id="total-percentage">0.00</span>%
                </div>
            </div>
            <div class="col-md-auto">
                <button type="button" class="btn btn-primary btn-sm add-item-btn float-end" class="add-item-btn" data-target="item-1">
                    <i class="fa fa-plus"></i> Tambah Asuransi
                </button>
            </div>
        </div>
        <div class="item-list">
            <div class="insurance-row mb-2 px-3 py-2 border rounded item item-1" style="display: none">
                <div class="row">
                    <div class="col-md">
                        <label class="form-label">Asuransi <span class="item-count"></span></label>
                        <select class="form-select add-select2" name="insurance_id[]" style="width: 100%;" required>
                            <option value="">- Pilih Asuransi -</option>
                            @foreach ($insurances as $insurance)
                                <option value="{{ $insurance->id }}">
                                    {{ $insurance->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-auto">
                        <label class="form-label">Persentase (%)</label>
                        <input type="number" class="form-control insurance-percentage" name="percentage[]" value="" placeholder="0" min="0" max="100" step="0.01">
                    </div>
                    <div class="col-md-auto pt-3">
                        <div class="form-check">
                            <input class="form-check-input is-main-checkbox" type="checkbox" name="is_main[]" value="1">
                            <label class="form-check-label">Asuransi <br> Utama</label>
                        </div>
                    </div>
                    <div class="col-md-auto pt-3">
                        <button type="button" class="btn btn-danger btn-xs remove-item-btn">
                            <i class="fa fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div id="empty-state" class="text-center py-4 text-muted" style="{{ isset($policyInsurances) && $policyInsurances->count() > 0 ? 'display: none;' : '' }}">
            <i class="fa fa-info-circle fa-2x mb-2 d-block"></i>
            <p class="mb-0">Klik tombol <strong class="text-primary">"Tambah Asuransi"</strong> untuk menambahkan asuransi</p>
        </div>
    </div>
</div>

{{-- <div class="col-md-12 mb-3">
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
    </div> --}}
