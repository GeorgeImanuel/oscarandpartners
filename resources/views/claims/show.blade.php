@extends('layouts.master')

@section('title')
    Detail Claim
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <h3>Detail Claim</h3>
        @endslot
        <li class="breadcrumb-item"><a href="{{ route('claims.index') }}">Claim</a></li>
        <li class="breadcrumb-item active">Detail</li>
    @endcomponent

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5>Detail Claim</h5>
                            <div class="d-flex gap-2">
                                <a href="{{ route('claims.edit', $claim->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fa fa-edit"></i> Edit
                                </a>
                                <a href="{{ route('claims.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="fa fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="row">
                            {{-- Info Polis --}}
                            <div class="col-md-6">
                                <div class="card mb-3 shadow-none border">
                                    <div class="card-body">
                                        <h6 class="card-title mb-3 border-bottom pb-2">Informasi Polis</h6>
                                        <table class="table table-borderless table-sm mb-0">
                                            <tr>
                                                <td width="35%" class="text-muted">No. Polis</td>
                                                <td class="fw-bold">: {{ $claim->policy->policy_number ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Tertanggung</td>
                                                <td class="fw-bold">: {{ $claim->policy->productionCard->client->id_name ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Objek</td>
                                                <td class="fw-bold">: {{ $claim->policy->object->ObjectOccupation->name ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Periode</td>
                                                <td class="fw-bold">: 
                                                    {{ $claim->policy->started_date ? $claim->policy->started_date->format('d/m/Y') : '-' }} 
                                                    s/d 
                                                    {{ $claim->policy->ended_date ? $claim->policy->ended_date->format('d/m/Y') : '-' }}
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            {{-- Tanggal Penting --}}
                            <div class="col-md-6">
                                <div class="card mb-3 shadow-none border">
                                    <div class="card-body">
                                        <h6 class="card-title mb-3 border-bottom pb-2">Tanggal Penting</h6>
                                        <table class="table table-borderless table-sm mb-0">
                                            <tr>
                                                <td width="35%" class="text-muted">Tgl Kejadian</td>
                                                <td class="fw-bold">: {{ $claim->incident_date->format('d/m/Y') }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Tgl Lapor</td>
                                                <td class="fw-bold">: {{ $claim->insurance_report_date->format('d/m/Y') }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Tgl Bayar</td>
                                                <td class="fw-bold">: {{ $claim->payment_date ? $claim->payment_date->format('d/m/Y') : 'Belum dibayar' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- KRONOLOGI (KEMBALI SEPERTI ASAL - FULL WIDTH) --}}
                        <div class="card mb-3">
                            <div class="card-body">
                                <h6 class="card-title mb-3">Kronologi Kejadian</h6>
                                <p class="text-muted mb-0" style="white-space: pre-line;">{{ $claim->chronology }}</p>
                            </div>
                        </div>

                        {{-- DOKUMEN (KEMBALI SEPERTI ASAL - FULL WIDTH) --}}
                        <div class="card mb-3">
                            <div class="card-body">
                                <h6 class="card-title mb-3">Dokumen yang Diperlukan</h6>
                                <p class="text-muted mb-0" style="white-space: pre-line;">{{ $claim->docs }}</p>
                            </div>
                        </div>

                        {{-- 3 KOTAK NILAI (GAYA BARU: POLOS/CLEAN) --}}
                        <div class="row mt-2">
                            {{-- Kotak 1: Casco --}}
                            <div class="col-md-4">
                                <div class="card mb-3 shadow-sm border">
                                    <div class="card-body text-center p-3">
                                        <p class="text-muted mb-1 small uppercase">Claim Casco (Nilai Unit)</p>
                                        <h4 class="fw-bold mb-0">Rp {{ number_format($claim->casco, 0, ',', '.') }}</h4>
                                    </div>
                                </div>
                            </div>

                            {{-- Kotak 2: TJH --}}
                            <div class="col-md-4">
                                <div class="card mb-3 shadow-sm border">
                                    <div class="card-body text-center p-3">
                                        <p class="text-muted mb-1 small uppercase">Claim TJH (Pihak Ke-3)</p>
                                        <h4 class="fw-bold mb-0">Rp {{ number_format($claim->tjh, 0, ',', '.') }}</h4>
                                    </div>
                                </div>
                            </div>

                            {{-- Kotak 3: Total --}}
                            <div class="col-md-4">
                                <div class="card mb-3 shadow-sm border" style="background-color: #f8f9fa;">
                                    <div class="card-body text-center p-3">
                                        <p class="text-muted mb-1 small uppercase">Total Claim</p>
                                        <h4 class="fw-bold text-dark mb-0">Rp {{ number_format($claim->amount, 0, ',', '.') }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Keterangan Tambahan --}}
                        @if($claim->note)
                            <div class="card shadow-none border">
                                <div class="card-body">
                                    <h6 class="card-title mb-2">Keterangan Proses Claim</h6>
                                    <p class="text-muted mb-0" style="white-space: pre-line;">{{ $claim->note }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection