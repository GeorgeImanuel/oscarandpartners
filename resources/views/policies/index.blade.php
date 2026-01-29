@extends('layouts.master')

@section('title', 'Polis')

@push('breadcrumb')
    <li class="breadcrumb-item active">polis</li>
@endpush

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
@endpush
@section('content')
    <div class="d-flex justify-content-between align-items-center">
        @component('components.breadcrumb')
            @slot('breadcrumb_title')
                <h3>Daftar Polis</h3>
            @endslot
            <li class="breadcrumb-item active">Polis</li>
            <li class="breadcrumb-item">Daftar</li>
        @endcomponent
    </div>

    <!-- Container-fluid starts-->
    <div class="container-fluid dashboard-default-sec">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5 style="display: inline">Daftar Polis</h5>
                        <a href="{{ route('policies.create') }}" class="btn btn-primary mb-3 float-end"><i class="icon-plus me-2"></i>Tambah Polis</a>
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
                                            <td data-order="{{ $policy->started_date ? $policy->started_date->format('Y-m-d') : '' }}">{{ $policy->started_date->format('d M Y') }}</td>
                                            <td data-order="{{ $policy->ended_date ? $policy->ended_date->format('Y-m-d') : '' }}">{{ $policy->ended_date->format('d M Y') }}</td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    {{-- Detail --}}
                                                    <a href="{{ route('policies.show', $policy->id) }}" class="btn btn-info btn-sm">
                                                        <i class="icon-eye"></i> Detail
                                                    </a>
                                                    
                                                    {{-- Edit --}}
                                                    <a href="{{ route('policies.edit', $policy->id) }}" class="btn btn-warning btn-sm">
                                                        <i class="icon-pencil"></i> Edit
                                                    </a>

                                                    {{-- Delete --}}
                                                    <form action="{{ route('policies.destroy', $policy->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-danger btn-xs" onClick="confirmDelete(this)">
                                                            <i class="icon-trash"></i> Del
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>

                                    @empty
                                        <tr></tr>
                                        <td colspan="7" class="text-center">No policies found.</td>
                                        
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