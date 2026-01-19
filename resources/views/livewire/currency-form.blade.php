<div id="new-currency"
    class="row gt-3"
    style="display:none;">
    <div class="col-md-12 mb-3">
        <label class="form-label"
            for="code">Kode Mata Uang <span class="text-danger">*</span></label>
        <input class="form-control required @error('code') is-invalid @enderror"
            id="code"
            name="code"
            type="text"
            maxlength="3"
            placeholder="USD"
            value="{{ isset($currency) ? $currency->code : old('code', '') }}" 
            {{ isset($currency) ? 'readonly' : '' }} />
        <small class="text-muted">Contoh: USD, IDR, EUR (3 karakter)</small>
        @error('code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-12 mb-3">
        <label class="form-label"
            for="name">Nama Mata Uang <span class="text-danger">*</span></label>
        <input class="form-control required @error('name') is-invalid @enderror"
            id="name"
            name="name"
            type="text"
            placeholder="US Dollar"
            value="{{ isset($currency) ? $currency->name : old('name', '') }}" />
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
