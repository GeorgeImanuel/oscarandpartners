@extends('layouts.master')

@section('title', 'Mata Uang')

@push('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('policies.index') }}">Polis</a></li>
    <li class="breadcrumb-item active">Mata Uang</li>
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
                        <h5 style="display: inline">Daftar Mata Uang</h5>
                        <a href="{{ route('currencies.create') }}" class="btn btn-primary mb-3 float-end">Tambah Mata Uang</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display" id="basic-1">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Nama</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($currencies as $currency)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $currency->code }}</td>
                                            <td>{{ $currency->name }}</td>
                                            <td>
                                                <a class="btn btn-warning btn-sm mb-2 me-2" href="{{ route('currencies.edit', $currency->code) }}">Edit</a>
                                                <br><form action="{{ route('currencies.destroy', $currency->code) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-sm" onClick="confirmDelete(this)">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Tidak ada data mata uang.</td>
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
