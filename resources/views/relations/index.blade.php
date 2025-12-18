@extends('layouts.master')

@section('title', 'Relasi')

@push('breadcrumb')
    <li class="breadcrumb-item active">Relasi</li>
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
                        <h5 style="display: inline">Daftar Relasi</h5>
                        <a href="{{ route('relations.create') }}" class="btn btn-primary float-end">Tambah Relasi</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display" id="basic-1">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama Relasi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($relations as $relation)
                                        <tr>
                                            <td>{{ $relation->code }}</td>
                                            <td>{{ $relation->name }}</td>
                                            <td>
                                                <a href="{{ route('relations.edit', $relation->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                                <form action="{{ route('relations.destroy', $relation->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-sm" onClick="confirmDelete(this)">Delete</button>
                                                </form>
                                            </td>
                                        </tr>

                                    @empty
                                        <tr></tr>
                                        <td colspan="3" class="text-center">No relations found.</td>
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