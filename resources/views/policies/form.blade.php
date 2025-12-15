@extends('layouts.master')

@section('title', 'Policies')

@push('breadcrumb')
    <li class="breadcrumb-item">Policies</li>
    <li class="breadcrumb-item active">{{ isset($policies) ? 'Ubah' : 'Tambah'  }} Policies</li>
@endpush

@section('content')
    @yield('breadcrumb-list')
    <!-- Container-fluid starts-->
    <div class="container-fluid dashboard-default-sec">
        <form class="needs-validation" novalidate="" method="POST" action="{{ isset($policies) ? route('policies.update', $policies->id) : route('policies.store') }}">
            <div class="card">
                <div class="card-header">
                    <h5 style="display: inline">Tambah Policies</h5>
                </div>
                <div class="card-body">
                    @csrf
                    @if(isset($policies))
                        @method('PUT')
                    @endif

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="inputinsurance_id">Insurance ID</label>
                            <input class="form-control @error('insurance_id') is-invalid @enderror" id="inputinsurance_id" name="insurance_id" type="text" value="{{ isset($policies) ? $policies->insurance_id : old('insurance_id', '') }}" required="" />
                            @error('insurance_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div><br>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="inputparent_policy_id">Parent Policy ID</label>
                            <input class="form-control @error('parent_policy_id') is-invalid @enderror" id="inputparent_policy_id" name="parent_policy_id" type="text" value="{{ isset($policies) ? $policies->parent_policy_id : old('parent_policy_id', '') }}" required="" />
                            @error('parent_policy_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div><br>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="inputobject_type_id">Object Type ID</label>
                            <input class="form-control @error('object_type_id') is-invalid @enderror" id="inputobject_type_id" name="object_type_id" type="text" value="{{ isset($policies) ? $policies->object_type_id : old('object_type_id', '') }}" required="" />
                            @error('object_type_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div><br>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="inputpolicy_number">Policy Number</label>
                            <input class="form-control @error('policy_number') is-invalid @enderror" id="inputpolicy_number" name="policy_number" type="text" value="{{ isset($policies) ? $policies->policy_number : old('policy_number', '') }}" required="" />
                            @error('policy_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div><br>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="inputobjectr">Object</label>
                            <input class="form-control @error('object') is-invalid @enderror" id="inputobject" name="object" type="text" value="{{ isset($policies) ? $policies->object : old('object', '') }}" required="" />
                            @error('object')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div><br>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="inputobject_address">Object Address</label>
                            <input class="form-control @error('object_address') is-invalid @enderror" id="inputobject_address" name="object_address" type="text" value="{{ isset($policies) ? $policies->object_address : old('object_address', '') }}" required="" />
                            @error('object_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div><br>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="inputobject_value">Object Value</label>
                            <input class="form-control @error('object_value') is-invalid @enderror" id="inputobject_value" name="object_value" type="text" value="{{ isset($policies) ? $policies->object_value : old('object_value', '') }}" required="" />
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