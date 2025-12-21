@extends('layouts.master')

@section('title', 'Policies')

@push('breadcrumb')
    <li class="breadcrumb-item">Policies</li>
    <li class="breadcrumb-item active">{{ isset($policy) ? 'Ubah' : 'Tambah'  }} Policies</li>
@endpush

@section('content')
    @yield('breadcrumb-list')
    <!-- Container-fluid starts-->
    <div class="container-fluid dashboard-default-sec">
        <form class="needs-validation" novalidate="" method="POST" action="{{ isset($policy) ? route('policies.update', $policy->id) : route('policies.store') }}">
            <div class="card">
                <div class="card-header">
                    <h5 style="display: inline">{{ isset($policy) ? 'Ubah' : 'Tambah'  }} Policies</h5>
                </div>
                <div class="card-body">
                    @csrf
                    @if(isset($policy))
                        @method('PUT')
                    @endif

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="inputinsurance_id">Insurance ID </label>
                            <select class="form-control error(insurance_id)" name="insurance_id">
                                    <option value="" selected disabled>- Pilih ID -</option>
                                    @foreach ($insurances as $insurance)
                                        <option value="{{ $insurance->id }}" {{ isset($policy) ? $insurance->id == $policy->insurance_id ? "selected" : "" : "" }}>{{ $insurance->name }}</option>                                        
                                    @endforeach
                                </select>
                            @error('insurance_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div><br>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="inputparent_policy_id">Parent Policy ID</label>
                            <select class="form-control error(parent_policy_id)" name="parent_policy_id">
                                <option value="" selected disabled>- Pilih ID -</option>
                                {{-- Mengubah $policies sebagai variabel loop ke $allPolicies dan $singlePolicy --}}
                                @foreach ($policies as $singlePolicy)
                                    <option value="{{ $singlePolicy->id }}" {{ (isset($policy) && isset($policy->parent_policy_id)) ? $singlePolicy->id == $policy->parent_policy_id ? "selected" : "" : "" }}>{{ $singlePolicy->policy_number }}</option>                                        
                                @endforeach
                            </select>
                            @error('parent_policy_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div><br>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="inputobject_type_id">Object Type ID</label>
                            <select class="form-control error(object_type_id)" name="object_type_id">
                                    <option value="" selected disabled>- Pilih ID -</option>
                                    @foreach ($object_types as $object_type)
                                        <option value="{{ $object_type->id }}" {{ isset($policy) ? $object_type->id == $policy->object_type_id ? "selected" : "" : "" }}>{{ $object_type->name }}</option>                                        
                                    @endforeach
                                </select>
                            @error('object_type_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div><br>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="inputpolicy_number">Policy Number</label>
                            <input class="form-control @error('policy_number') is-invalid @enderror" id="inputpolicy_number" name="policy_number" type="text" value="{{ isset($policy) ? $policy->policy_number : old('policy_number', '') }}" required="" />
                            @error('policy_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div><br>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="inputobjectr">Object</label>
                            <input class="form-control @error('object') is-invalid @enderror" id="inputobject" name="object" type="text" value="{{ isset($policy) ? $policy->object : old('object', '') }}" required="" />
                            @error('object')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div><br>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="inputobject_address">Object Address</label>
                            <input class="form-control @error('object_address') is-invalid @enderror" id="inputobject_address" name="object_address" type="text" value="{{ isset($policy) ? $policy->object_address : old('object_address', '') }}" required="" />
                            @error('object_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div><br>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="inputobject_value">Object Value</label>
                            <input class="form-control @error('object_value') is-invalid @enderror" id="inputobject_value" name="object_value" type="text" value="{{ isset($policy) ? $policy->object_value : old('object_value', '') }}" required="" />
                            @error('object_value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div><br>
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