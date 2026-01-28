@extends('layouts.master')

@section('title')
    Client List
@endsection

@push('css')
    <link rel="stylesheet"
        type="text/css"
        href="{{ asset('assets/css/datatables.css') }}">
@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <h3>Client List</h3>
        @endslot
        <li class="breadcrumb-item active">Clients</li>
        <li class="breadcrumb-item active">Daftar</li>
    @endcomponent

    <div class="container-fluid">
        <div class="row">
            <!-- Client List Table Starts-->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>Daftar Client</h5>
                        <a href="{{ route('clients.create') }}"
                            class="btn btn-primary"><i class="icon-plus me-2"></i>Tambah Client</a>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show"
                                role="alert">
                                {{ session('success') }}
                                <button type="button"
                                    class="btn-close"
                                    data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="display"
                                id="basic-1">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Alamat</th>
                                        <th>Relasi/Grup</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($clients as $client)
                                        <tr>
                                            <td>{{ $client->id_name ?? '-' }}</td>
                                            <td>{{ $client->id_address ?? '-' }}</td>
                                            <td>{{ $client->relation ? $client->relation->name : '-' }}</td>
                                            <td style="white-space: nowrap;">
                                                <a href="{{ route('clients.edit', $client) }}"
                                                    class="btn btn-sm btn-warning">Edit</a>
                                                <form action="{{ route('clients.destroy', $client) }}"
                                                    method="POST"
                                                    style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-xs btn-danger"
                                                        onclick="return confirm('Are you sure you want to delete this client?')">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6"
                                                class="text-center">No clients found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Client List Table Ends-->
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
    @endpush
@endsection
