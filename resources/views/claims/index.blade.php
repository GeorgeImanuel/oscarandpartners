@extends('layouts.master')

@section('title')
    Claim
@endsection

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <h3>Daftar Claim</h3>
        @endslot
        <li class="breadcrumb-item">Claim</li>
        <li class="breadcrumb-item active">Daftar</li>
    @endcomponent

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>Daftar Claim</h5>
                            <a href="{{ route('claims.create') }}" class="btn btn-primary btn-sm">
                                <i class="icon-plus me-2"></i>Tambah Claim
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="display" id="basic-1">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No. Polis</th>
                                        <th>Tertanggung</th>
                                        <th>Tanggal Kejadian</th>
                                        <th>Tanggal Lapor</th>
                                        <th>Casco</th>
                                        <th>TJH</th>
                                        <th>Jumlah Claim</th>
                                        <th class="text-center"width="10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($claims as $index => $claim)
                                        <tr>
                                            <td>{{ $claims->firstItem() + $index }}</td>
                                            <td>{{ $claim->policy->policy_number ?? '-' }}</td>
                                            <td>{{ $claim->policy->productionCard->client->id_name ?? '-' }}</td>
                                            <td>{{ $claim->incident_date->format('d/m/Y') }}</td>
                                            <td>{{ $claim->insurance_report_date->format('d/m/Y') }}</td>
                                            <td>Rp {{ number_format($claim->casco, 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($claim->tjh, 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($claim->amount, 0, ',', '.') }}</td>
                                            <td class="text-center text-nowrap">
                                                {{-- Tombol Detail --}}
                                                <a href="{{ route('claims.show', $claim->id) }}" 
                                                   class="btn btn-info btn-sm">
                                                    <i class="icon-eye me-2"></i>Detail
                                                </a>

                                                {{-- Tombol Edit --}}
                                                <a href="{{ route('claims.edit', $claim->id) }}" 
                                                   class="btn btn-warning btn-sm">
                                                    <i class="fa fa-edit"></i> Edit
                                                </a>

                                                {{-- Tombol Hapus --}}
                                                <form action="{{ route('claims.destroy', $claim->id) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Yakin ingin menghapus claim ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-danger btn-xs" 
                                                            style="padding: 0.2rem 0.5rem; font-size: 0.7rem;">
                                                        <i class="fa fa-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center">Tidak ada data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                Menampilkan {{ $claims->firstItem() ?? 0 }} sampai {{ $claims->lastItem() ?? 0 }} 
                                dari {{ $claims->total() }} data
                            </div>
                            <div>
                                {{ $claims->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
@endpush