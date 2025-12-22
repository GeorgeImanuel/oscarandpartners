@extends('layouts.master')

@section('title', 'policy')

@push('breadcrumb')
    <li class="breadcrumb-item">policy</li>
    <li class="breadcrumb-item active">{{ isset($policy) ? 'Ubah' : 'Tambah'  }} policy</li>
@endpush

@section('content')
    @yield('breadcrumb-list')
    <!-- Container-fluid starts-->
    <div class="container-fluid dashboard-default-sec">
        <form class="needs-validation" novalidate="" method="POST" action="{{ isset($policy) ? route('policies.update', $policy->id) : route('policies.store') }}">
            <div class="card">
                <div class="card-header">
                    <h5 style="display: inline">{{ isset($policy) ? 'Ubah' : 'Tambah'  }} policy</h5>
                </div>
                <div class="card-body row">
                    @csrf
                    @if(isset($policy))
                        @method('PUT')
                    @endif
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="inputinsurance_id">Insurance ID </label>
                        <select class="form-control error(insurance_id)" name="insurance_id">
                                <option value="" selected disabled>- Pilih Data -</option>
                                @foreach ($insurances as $insurance)
                                    <option value="{{ $insurance->id }}" {{ isset($policy) ? $insurance->id == $policy->insurance_id ? "selected" : "" : "" }}>{{ $insurance->name }}</option>                                        
                                @endforeach
                            </select>
                        @error('insurance_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="inputparent_policy_id">Policy Turunan</label>
                        <select class="form-control error(parent_policy_id)" name="parent_policy_id">
                            <option value="" selected disabled>- Pilih Data -</option>
                            {{-- Mengubah $policy sebagai variabel loop ke $allpolicy dan $singlePolicy --}}
                            @forelse ($parent_policies as $parent_policy)
                                <option value="{{ $parent_policy->id }}" {{ (isset($policy) && isset($policy->parent_policy_id)) ? $parent_policy->id == $policy->parent_policy_id ? "selected" : "" : "" }}>{{ $parent_policy->policy_number }}</option>                                        
                            @empty
                                <option value="" disabled>Tidak ada parent policy tersedia</option>
                            @endforelse
                        </select>
                        @error('parent_policy_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="inputobject_type_id">Tipe Objek</label>
                        <select class="form-control error(object_type_id)" name="object_type_id">
                                <option value="" selected disabled>- Pilih Data -</option>
                                @foreach ($object_types as $object_type)
                                    <option value="{{ $object_type->id }}" {{ isset($policy) ? $object_type->id == $policy->object_type_id ? "selected" : "" : "" }}>{{ $object_type->name }}</option>                                        
                                @endforeach
                            </select>
                        @error('object_type_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="inputpolicy_number">Nomor Polis</label>
                        <input class="form-control @error('policy_number') is-invalid @enderror" id="inputpolicy_number" name="policy_number" type="text" value="{{ isset($policy) ? $policy->policy_number : old('policy_number', '') }}" required="" />
                        @error('policy_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="inputobjectr">Nama Objek</label>
                        <input class="form-control @error('object') is-invalid @enderror" id="inputobject" name="object" type="text" value="{{ isset($policy) ? $policy->object : old('object', '') }}" required="" />
                        @error('object')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="inputobject_address">Alamat Objek</label>
                        <input class="form-control @error('object_address') is-invalid @enderror" id="inputobject_address" name="object_address" type="text" value="{{ isset($policy) ? $policy->object_address : old('object_address', '') }}" required="" />
                        @error('object_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="inputobject_value">Nilai Objek</label>
                        <input class="form-control @error('object_value') is-invalid @enderror" id="inputobject_value" name="object_value" type="text" value="{{ isset($policy) ? $policy->object_value : old('object_value', '') }}" required="" />
                        @error('object_value')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('policies.index') }}" class="btn btn-light">Kembali</a>
                <button class="btn btn-primary float-end" type="submit">Simpan</button>
            </div>
        </div>
    </form>
</div>
<!-- Container-fluid Ends-->
@endsection
@push('scripts')
    <script src="{{ asset('assets/js/form-validation.js') }}"></script>
    <script>
        initializeFormValidation({
            formSelector: '.needs-validation'
        });
    </script>
@endpush