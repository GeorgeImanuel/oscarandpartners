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
                                        <th>No Polis</th>
                                        <th>Mata Uang</th>
                                        <th>Presentase Coverage</th>
                                        <th>Tanggal Mulai</th>
                                        <th>Tanggal Selesai</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($policies as $policy)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $policy->policy_number }}</td>
                                            <td>{{ $policy->currency_code ?? '-' }}</td>
                                            <td>{{ number_format($policy->coverage_percentages * 100, 7, '.', '') }}</td>
                                            <td>{{ $policy->started_date }}</td>
                                            <td>{{ $policy->ended_date }}</td>
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