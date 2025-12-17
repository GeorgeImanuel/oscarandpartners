@extends('layouts.master')

@section('title', 'Insurances')

@push('breadcrumb')
    <li class="breadcrumb-item active">Insurances</li>
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
                        <h5 style="display: inline">Daftar Insurances</h5>
                        <a href="{{ route('insurances.create') }}" class="btn btn-primary mb-3 float-end">Tambah Insurances</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display" id="basic-1">
                                <thead>
                                    <tr>
                                        <th>Nama Bank</th>
                                        <th>Akun Bank</th>
                                        <th>No VA</th>
                                        <th>Default Commission Percentage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($insurances as $insurances)
                                        <tr>
                                            <td>{{ $insurances->name }}</td>
                                            <td>{{ $insurances->bank_account }}</td>
                                            <td>{{ $insurances->va_number }}</td>
                                            <td>{{ $insurances->default_commission_pct }}</td>
                                            <td>
                                                <a href="{{ route('insurances.edit', $insurances->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                                <form action="{{ route('insurances.destroy', $insurances->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-sm" onClick="confirmDelete(this)">Delete</button>
                                                </form>
                                            </td>
                                        </tr>

                                    @empty
                                        <tr></tr>
                                        <td colspan="3" class="text-center">No insurances found.</td>
                                        
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