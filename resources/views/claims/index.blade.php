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
                            {{-- Tambahkan class 'nowrap' atau 'text-nowrap' pada tabel --}}
                            <table class="display table text-nowrap" id="basic-1" style="width:100%">
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
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($claims as $index => $claim)
                                        <tr>
                                            <td>{{ $claims->firstItem() + $index }}</td>
                                            <td>{{ $claim->policy->policy_number ?? '-' }}</td>
                                            <td>{{ $claim->policy->productionCard->client->id_name ?? '-' }}</td>
                                            <td data-order="{{ $claim->incident_date ? $claim->incident_date->format('Y-m-d') : '' }}">{{ $claim->incident_date->format('d M Y') }}</td>
                                            <td>{{ $claim->insurance_report_date->format('d M Y') }}</td>
                                            <td>Rp {{ number_format($claim->casco, 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($claim->tjh, 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($claim->amount, 0, ',', '.') }}</td>
                                            <td class="text-center">
                                                <div class="btn-group gap-2">
                                                    {{-- Tombol Detail --}}
                                                    <a href="{{ route('claims.show', $claim->id) }}" class="btn btn-info btn-sm" title="Detail">
                                                        Detail
                                                    </a>

                                                    {{-- Tombol Edit --}}
                                                    <a href="{{ route('claims.edit', $claim->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                                        Edit
                                                    </a>

                                                    {{-- Tombol Hapus --}}
                                                    <form action="{{ route('claims.destroy', $claim->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus claim ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-xs" title="Hapus">
                                                            <i class="fa fa-trash"></i>Hapus
                                                        </button>
                                                    </form>
                                                </div>
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