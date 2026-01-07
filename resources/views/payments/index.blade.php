@extends('layouts.master')

@section('title')Payment
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Pembayaran</li>
<li class="breadcrumb-item active">Daftar</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Daftar Pembayaran</h5>
                    <a href="{{ route('payments.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus"></i> Tambah Pembayaran
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>No Kartu Produksi</th>
                                    <th>Urutan Tagihan</th>
                                    <th>Created by</th>
                                    <th>Collected by</th>
                                    <th>Jumlah</th>
                                    <th>Note</th>
                                    <th>Status Pembayaran</th>
                                    <th>Tenggat Waktu</th>
                                    <th>Tanggal Pembayaran</th>
                                    <th>Bukti Transfer</th>
                                    <th>No Bukti Transfer</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payments as $payment)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">Belum ada data pembayaran.</td>
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
@endsection