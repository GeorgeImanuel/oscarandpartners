@extends('layouts.master')

@section('title', 'Insurances')

@push('breadcrumb')
    <li class="breadcrumb-item active">Asuransi</li>
@endpush

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
@endpush
@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <h3>Asuransi</h3>
        @endslot
        <li class="breadcrumb-item">Asuransi</li>
        <li class="breadcrumb-item">Daftar</li>    
    @endcomponent

    <div class="container-fluid dashboard-default-sec">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5 style="display: inline">Daftar Asuransi</h5>
                        <a href="{{ route('insurances.create') }}" class="btn btn-primary mb-3 float-end"><i class="icon-plus me-2"></i>Tambah Asuransi</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display" id="basic-1">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Asuransi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($insurances as $insurance)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $insurance->name }}</td>
                                            <td>
                                                <a href="{{ route('insurances.edit', $insurance->id) }}" class="btn btn-warning btn-sm"><i class="icon-pencil me-2"></i>Edit</a>
                                                <form action="{{ route('insurances.destroy', $insurance->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-xs" onClick="confirmDelete(this)"><i class="icon-trash me-2"></i>Delete</button>
                                                </form>
                                            </td>
                                        </tr>

                                    @empty
                                        <tr>
                                        <td colspan="3" class="text-center">No insurances found.</td>
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