<div id="new-client"
    class="row">

    <div class="col-md-12 mb-3">
        <label class="form-label"
            for="coverage_code">Group</label>
        <select class="form-select create-select2 required @error('coverage_code') is-invalid @enderror"
            data-placeholder="Pilih Group"
            name="relation_id">
            <option value="">- Pilih Group -</option>
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
            for="ended_date">Alamat Tertanggung</label>
        <textarea class="form-control required @error('ended_date') is-invalid @enderror"
            name="id_address"
            type="text">{{ isset($client) ? $client->id_address : old('id_address', '') }}</textarea>
        @error('id_address')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
