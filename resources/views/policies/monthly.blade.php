@extends('layouts.master')

@section('title')
    Daftar Polis Jatuh Tempo - {{ $month }} {{ $year }}
@endsection

@push('css')
    <link rel="stylesheet"
        type="text/css"
        href="{{ asset('assets/css/datatables.css') }}">
    <style>
        @media print {
            /* Atur halaman landscape */
            @page {
                size: landscape;
                margin: 8mm;
            }

            /* Reset body */
            body, html {
                margin: 0 !important;
                padding: 0 !important;
                background: white !important;
            }

            /* Sembunyikan semua elemen */
            body * {
                visibility: hidden !important;
                position: static !important;
            }

            /* Tampilkan hanya printArea */
            #printArea,
            #printArea * {
                visibility: visible !important;
            }

            #printArea {
                position: absolute !important;
                left: 0 !important;
                top: 0 !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            /* Sembunyikan kolom aksi saat print */
            .no-print {
                display: none !important;
                visibility: hidden !important;
            }

            /* Sembunyikan footer & elemen lain secara eksplisit */
            .page-footer,
            footer,
            .copyright,
            .breadcrumb,
            .card-header,
            .btn,
            .alert,
            nav,
            .sidebar,
            .dataTables_wrapper .dataTables_filter,
            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_info,
            .dataTables_wrapper .dataTables_paginate {
                display: none !important;
                visibility: hidden !important;
            }

            /* Hilangkan arrow sorting DataTables */
            table.dataTable thead th.sorting::before,
            table.dataTable thead th.sorting::after,
            table.dataTable thead th.sorting_asc::before,
            table.dataTable thead th.sorting_asc::after,
            table.dataTable thead th.sorting_desc::before,
            table.dataTable thead th.sorting_desc::after,
            .sorting::before,
            .sorting::after,
            .sorting_asc::before,
            .sorting_asc::after,
            .sorting_desc::before,
            .sorting_desc::after {
                display: none !important;
                visibility: hidden !important;
                content: none !important;
            }

            /* Hilangkan class sorting dari header */
            table thead th {
                cursor: default !important;
                background-image: none !important;
            }

            /* Header laporan */
            .print-header {
                display: block !important;
                visibility: visible !important;
                text-align: center;
                margin-bottom: 12px;
                padding-bottom: 8px;
            }

            .print-header h2 {
                margin: 0 0 5px 0 !important;
                font-size: 16px !important;
                font-weight: bold !important;
                color: #000 !important;
            }

            .print-header p {
                margin: 0 !important;
                font-size: 11px !important;
                color: #000 !important;
            }

            /* Style tabel */
            .table-responsive {
                overflow: visible !important;
            }

            table {
                width: 100% !important;
                border-collapse: collapse !important;
                font-size: 9px !important;
                page-break-inside: auto !important;
                border: none !important;
            }

            table thead {
                display: table-header-group !important;
            }

            table tbody {
                display: table-row-group !important;
            }

            table tr {
                page-break-inside: avoid !important;
                page-break-after: auto !important;
            }

            table th, table td {
                border: 0.1px solid #000 !important;
                padding: 3px 5px !important;
                color: #000 !important;
                vertical-align: middle !important;
            }

            table th {
                background-color: #e0e0e0 !important;
                font-weight: bold !important;
                text-align: center !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            /* Alignment khusus kolom */
            table td:nth-child(1) { text-align: center !important; } /* Periode */
            table td:nth-child(2) { text-align: center !important; } /* No */
            table td:nth-child(5) { text-align: right !important; } /* TSI */
            table td:nth-child(6) { text-align: left !important; } /* Keterangan */

            /* Badge hitam putih tanpa warna */
            .badge {
                background-color: white !important;
                border: 0.1px solid #000 !important;
                color: #000 !important;
                padding: 2px 5px !important;
                font-size: 8px !important;
                display: inline-block !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }

        /* Sembunyikan print header di layar normal */
        .print-header {
            display: none;
        }
    </style>
@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <h3>Daftar Polis Jatuh Tempo {{ $month }} {{ $year }}</h3>
        @endslot
        <li class="breadcrumb-item">Daftar Polis Jatuh Tempo</li>
        <li class="breadcrumb-item active">{{ $month }} {{ $year }}</li>
    @endcomponent

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5>Daftar Polis Jatuh Tempo - {{ $month }} {{ $year }}</h5>
                            <div>
                                <button onclick="window.print()" class="btn btn-primary me-2">
                                    <i class="fa fa-print"></i> Print
                                </button>
                                <a href="{{ route('policies.index') }}"
                                    class="btn btn-secondary">
                                    <i class="fa fa-list"></i> Semua Polis
                                </a>
                            </div>
                        </div>
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

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show"
                                role="alert">
                                {{ session('error') }}
                                <button type="button"
                                    class="btn-close"
                                    data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <div id="printArea">
                            <div class="print-header">
                                <h2>Daftar Polis Jatuh Tempo</h2>
                                <p>Periode: {{ $month }} {{ $year }}</p>
                            </div>

                            <div class="table-responsive">
                                <table class="display"
                                    id="basic-1">
                                <thead>
                                    <tr>
                                        <th width="10%">Periode</th>
                                        <th width="3%">No</th>
                                        <th width="12%">No. Polis</th>
                                        <th width="20%">Nama Tertanggung</th>
                                        <th width="13%" class="text-end">TSI</th>
                                        <th width="35%">Keterangan</th>
                                        <th width="10%" class="no-print">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp
                                    @forelse($policies as $policy)
                                        {{-- Ganti policyObjects menjadi objects --}}
                                        @foreach($policy->objects as $object)
                                            @php 
                                                // Hitung total TSI per object
                                                $totalTsiPerObject = $object->objectInterests->sum('value');
                                            @endphp
                                            <tr>
                                                <td class="text-center">{{ $policy->ended_date->format('d M Y') }}</td>
                                                <td class="text-center">{{ $no++ }}</td>
                                                <td>{{ $policy->policy_number }}</td>
                                                
                                                {{-- Nama Tertanggung --}}
                                                <td>{{ $policy->productionCard->client->id_name ?? '-' }}</td>
                                                
                                                {{-- TSI per Object --}}
                                                <td class="text-end">
                                                    {{ number_format($totalTsiPerObject, 0, ',', '.') }}
                                                </td>
                                                
                                                {{-- Keterangan diambil dari kolom keterangan di tabel objects --}}
                                                <td>{{ $object->keterangan ?? '-' }}</td>
                                                
                                                {{-- Tombol Detail --}}
                                                <td class="text-center no-print">
                                                    <a href="{{ route('policies.show', $policy->id) }}" class="btn btn-info btn-xs">
                                                        <i class="icon-eye"></i> Detail
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Tidak ada data polis bulan ini</td>
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
        </div>
        @push('scripts')
            <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
            <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
        @endpush
    @endsection
