<div id="new-policy"
    class="row"
    style="display:none;">
    <div class="col-md-12 mb-3">
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

    <div class="col-md-12 mb-3">
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

    <div class="col-md-12 mb-3">
        <label class="form-label"
            for="currency_code">Mata Uang <span class="text-danger">*</span></label>
        <select class="form-select add-select2 required @error('currency_code') is-invalid @enderror"
            name="currency_code"
            id="currency_code"
            data-placeholder="Pilih Mata Uang">
            <option value=""></option>
            @foreach ($currencies as $currency)
                <option value="{{ $currency->code }}"
                    {{ isset($policy) && $currency->code == $policy->currency_code ? 'selected' : (old('currency_code') == $currency->code ? 'selected' : '') }}>
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
        <div class="row">
            <div class="col-md">
                <div id="percentage-info"
                    class="alert alert-danger py-2">
                    <strong>Total Persentase Coverage: <span id="total-percentage">0.00</span>%</strong><br>
                </div>
            </div>
            <div class="col-md-auto">
                <button type="button"
                    class="btn btn-primary btn-sm add-item-btn float-end"
                    class="add-item-btn"
                    data-target="item-coverage">
                    <i class="icon-plus me-2"></i> Tambah Coverage
                </button>
            </div>
        </div>
        <div class="item-list pt-2">
            <div class="mb-2 px-3 py-2 border rounded item item-coverage">
                <div class="row">
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
                    <div class="col-md-5">
                        <label class="form-label">Persentase Coverage</label>
                        <div class="input-group mb-3">
                            <input type="number"
                                class="form-control insurance-percentage"
                                name="percentage[]"
                                value=""
                                placeholder="0"
                                min="0"
                                max="100"
                                step="0.00001">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                    <div class="col-md-1 pt-4">
                        <button type="button"
                            class="btn btn-danger btn-xs remove-item-btn"
                            style="display:none">
                            <i class="icon-trash me-2"></i> Hapus
                        </button>
                    </div>
                </div>
            </div>
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
        <div class="row">
            <div class="col-md">
                <div id="percentage-info"
                    class="alert alert-danger py-2">
                    <strong>Total Persentase Asuransi: <span id="total-percentage">0.00</span>%</strong><br>
                </div>
            </div>
            <div class="col-md-auto">
                <button type="button"
                    class="btn btn-primary btn-sm add-item-btn float-end mt-4"
                    class="add-item-btn"
                    data-target="item-1">
                    <i class="icon-plus me-2"></i> Tambah Asuransi
                </button>
            </div>
        </div>
        <div class="item-list pt-2">
            <div class="mb-2 px-3 py-2 border rounded item item-1">
                <div class="row">
                    <div class="col-md-6">
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
                    <div class="col-md-3">
                        <label class="form-label">Persentase Asuransi</label>
                        <div class="input-group mb-3">
                            <input type="number"
                                class="form-control insurance-percentage"
                                name="percentage[]"
                                value=""
                                placeholder="0"
                                min="0"
                                max="100"
                                step="0.00001">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                    <div class="col-md-2 pt-4">
                        <div class="form-check">
                            <input class="form-check-input is-main-checkbox"
                                type="checkbox"
                                name="is_main[]"
                                value="1">
                            <label class="form-check-label">Asuransi Utama</label>
                        </div>
                    </div>
                    <div class="col-md-1 pt-3">
                        <button type="button"
                            class="btn btn-danger btn-xs remove-item-btn"
                            style="display:none">
                            <i class="icon-trash me-2"></i> Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div id="empty-state"
            class="text-center py-4 text-muted"
            style="{{ isset($policyInsurances) && $policyInsurances->count() > 0 ? 'display: none;' : '' }}">
            <i class="fa fa-info-circle fa-2x mb-2 d-block"></i>
            <p class="mb-0">Klik tombol <strong class="text-primary">"Tambah Asuransi"</strong> untuk menambahkan asuransi</p>
        </div>
    </div>
</div>

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
