@extends('layouts.master')

@section('title', 'Polis')

@push('breadcrumb')
    <li class="breadcrumb-item active">polis</li>
@endpush

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
@endpush
@section('content')
    @yield('breadcrumb-list')
    <!-- Container-fluid starts-->
    <div class="container-fluid dashboard-default-sec">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5 style="display: inline">Daftar Polis</h5>
                        <a href="{{ route('policies.create') }}" class="btn btn-primary mb-3 float-end">Tambah Polis</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display" id="basic-1">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Insurance ID</th>
                                        <th>Parent Policy ID</th>
                                        <th>Object Type ID</th>
                                        <th>Nomor Polis</th>
                                        <th>Nama Objek</th>
                                        <th>Alamat Objek</th>
                                        <th>Nilai Object</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($policies as $policy)
                                        <tr>
                                            <td>{{ $policy->id }}</td>
                                            <td>{{ $policy->insurance_id }}</td>
                                            <td>{{ $policy->parent_policy_id ?? '-' }}</td>
                                            <td>{{ $policy->object_type_id }}</td>
                                            <td>{{ $policy->policy_number }}</td>
                                            <td>{{ $policy->object }}</td>
                                            <td>{{ $policy->object_address }}</td>
                                            <td>{{ numberFormat($policy->object_value) }}</td>
                                            <td>
                                                <a  class="btn btn-warning btn-sm mb-2 me-2" href="{{ route('policies.edit', $policy->id) }}">Edit</a>
                                                <br><form action="{{ route('policies.destroy', $policy->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-sm" onClick="confirmDelete(this)">Delete</button>
                                                </form>
                                            </td>
                                        </tr>

                                    @empty
                                        <tr></tr>
                                        <td colspan="3" class="text-center">No policies found.</td>
                                        
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->
    @push('scripts')
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
    @endpush
@endsection