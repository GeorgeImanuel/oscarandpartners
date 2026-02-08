<div id="new-client"
    class="row">
    <div class="col-md-12 mb-3">
        <label class="form-label"
            for="ended_date">Nomor Kartu Identitas (NIK/KTP)<span class="text-danger">*</span></label>
        <input class="form-control required @error('ended_date') is-invalid @enderror"
            name="id_number"
            type="text"
            value="{{ isset($client) ? $client->id_number : old('id_number', '') }}">
        @error('id_number')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-12 mb-3">
        <label class="form-label"
            for="coverage_id">Relasi <span class="text-danger">*</span></label>
        <select class="form-select add-select2 required @error('coverage_id') is-invalid @enderror"
            data-placeholder="Pilih Relasi"
            name="relation_id">
            <option value="">- Pilih Relasi -</option>
            @foreach ($relations ?? [] as $relation)
                <option value="{{ $relation->id }}"
                    {{ isset($client) && $relation->id == $client->relation_id ? 'selected' : (old('relation_id') == $relation->id ? 'selected' : '') }}>
                    {{ $relation->name }}
                </option>
            @endforeach
        </select>
        @error('relation_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-12 mb-3">
        <label class="form-label"
            for="ended_date">Alamat<span class="text-danger">*</span></label>
        <textarea class="form-control required @error('ended_date') is-invalid @enderror"
            name="id_address"
            type="text">{{ isset($client) ? $client->id_address : old('id_address', '') }}
        </textarea>
        @error('id_address')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
