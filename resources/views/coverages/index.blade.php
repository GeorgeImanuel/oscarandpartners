@extends('layouts.master')

@section('title', 'Coverage')

@push('breadcrumb')
    <li class="breadcrumb-item active">Coverage</li>
@endpush

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
@endpush
@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <h3>Coverage</h3>
        @endslot
        <li class="breadcrumb-item">Coverage</li>
        <li class="breadcrumb-item">Daftar</li>    
    @endcomponent

    <!-- Container-fluid starts-->
    <div class="container-fluid dashboard-default-sec">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5 style="display: inline">Daftar Coverage</h5>
                        <a href="{{ route('coverages.create') }}" class="btn btn-primary mb-3 float-end"><i class="icon-plus me-2"></i>Tambah Coverage</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display" id="basic-1">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Coverage</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($coverages as $coverage)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $coverage->code }}</td>
                                            <td>{{ $coverage->name }}</td>
                                            <td>
                                                <a href="{{ route('coverages.edit', $coverage->code) }}" class="btn btn-warning btn-sm"><i class="icon-pencil me-2"></i>Edit</a>
                                                <form action="{{ route('coverages.destroy', $coverage->code) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-xs" onClick="confirmDelete(this)"><i class="icon-trash me-2"></i>Delete</button>
                                                </form>
                                            </td>
                                        </tr>

                                    @empty
                                        <tr>
                                        <td colspan="3" class="text-center">No coverages found.</td>
                                        </tr>
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