@extends('layouts.master')

@section('title')Daftar Pembayaran
@endsection

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
    <style>
        #basic-1 th, #basic-1 td {
            white-space: nowrap; 
            vertical-align: middle;
        }
        .col-keterangan {
            max-width: 200px;
            white-space: normal !important; /* Keterangan boleh wrap */
            word-wrap: break-word;
        }
    </style>
@endpush

@section('content')
    @component('components.breadcrumb')
    @slot('breadcrumb_title')
    <h3>Daftar Pembayaran</h3>
    @endslot
    <li class="breadcrumb-item">Pembayaran</li>
    <li class="breadcrumb-item active">Daftar</li>
    @endcomponent

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header text-end">
                        {{-- Tombol Print --}}
                        <button class="btn btn-primary" onclick="printTableOnly()"><i class="fa fa-print"></i> Print</button>
                    </div>
                    <div class="card-body">
                        
                        {{-- Container table-responsive bawaan Bootstrap sudah cukup untuk scroll --}}
                        <div class="table-responsive">
                            <table class="display table-bordered" id="basic-1" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Periode</th>
                                        <th>No</th>
                                        <th>No Polis</th>
                                        <th>Nama Tertanggung</th>
                                        <th>Letak Resiko</th>
                                        <th>TSI (Nilai Pertanggungan)</th>
                                        <th>Keterangan</th>
                                        <th>Jumlah Tagihan</th>
                                        
                                        <th class="text-center">Sts. Client</th>
                                        <th class="text-center">Sts. Asuransi</th>
                                        
                                        <th>Tipe Pembayaran</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($payments as $payment)
                                        <tr>
                                            <td>{{ $payment->deadline_at ? $payment->deadline_at->format('d/m/Y') : '-' }}</td>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $payment->card?->policy?->policy_number ?? '-' }}</td>
                                            <td>{{ $payment->insured_name }}</td>
                                            
                                            {{-- DATA KOLOM YANG DIBUKA KEMBALI --}}
                                            <td>{{ $payment->card?->policy?->object?->name ?? '-' }}</td>
                                            <td class="text-end">{{ number_format($payment->card?->object_value ?? 0, 0, ',', '.') }}</td>
                                            <td class="col-keterangan">{{ $payment->note ?? '-' }}</td>
                                            {{-- END DATA KOLOM --}}
                                            
                                            <td class="text-end fw-bold">{{ number_format($payment->amount ?? 0, 0, ',', '.') }}</td>

                                            {{-- BADGE STATUS CLIENT --}}
                                            <td class="text-center">
                                                @php
                                                    $statusId = $payment->payment_status_id;
                                                    // Mengembalikan Array [Nama Status, Warna Badge]
                                                    [$statusName, $badgeClass] = match($statusId) {
                                                        2 => ['Lunas', 'success'],       
                                                        3 => ['Gagal', 'danger'],        
                                                        4 => ['Refund', 'secondary'], 
                                                        default => ['Belum Lunas', 'warning'] 
                                                    };
                                                @endphp
                                                <span class="badge bg-{{ $badgeClass }} p-2">
                                                    {{ $statusName }}
                                                </span>
                                            </td>

                                            {{-- BADGE STATUS ASURANSI --}}
                                            <td class="text-center">
                                                @php
                                                    $insStatusId = $payment->insurance_payment_status_id;
                                                    // Mengembalikan Array [Nama Status, Warna Badge]
                                                    [$insName, $insClass] = match($insStatusId) {
                                                        2 => ['Disetor', 'success'],      
                                                        3 => ['Gagal', 'danger'],         
                                                        4 => ['Refund', 'secondary'],     
                                                        default => ['Belum Disetor', 'warning'] 
                                                    };
                                                @endphp
                                                <span class="badge bg-{{ $insClass }} p-2">
                                                    {{ $insName }}
                                                </span>
                                            </td>

                                            <td>{{ $payment->card?->paymentType?->name ?? '-' }}</td>
                                            
                                            <td class="text-center">
                                                <a href="{{ route('payments.show', $payment->id) }}" class="btn btn-info btn-sm" title="Lihat Detail">
                                                    <i class="fa fa-eye"></i> Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            {{-- Update colspan sesuai jumlah kolom total --}}
                                            <td colspan="13" class="text-center py-4 text-muted">
                                                <i class="fa fa-folder-open-o fa-3x mb-3"></i><br>
                                                Belum ada data pembayaran.
                                            </td>
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

    @push('scripts')
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
        
        <script>
            function printTableOnly() {
                var tableElement = document.getElementById('basic-1');
                if(!tableElement) return;

                var tableClone = tableElement.cloneNode(true);
                tableClone.className = "table-manual";

                var win = window.open('', '', 'height=700,width=1200');
                win.document.write('<html><head><title>Print Laporan Pembayaran</title>');
                
                win.document.write('<style>');
                win.document.write('body { font-family: Arial, sans-serif; padding: 20px; color: #000; }');
                
                // --- STYLE TABEL ---
                win.document.write('.table-manual { width: 100%; border-collapse: collapse; font-size: 10px; }');
                win.document.write('.table-manual th, .table-manual td { border: 1px solid #000; padding: 5px; vertical-align: middle; }');
                win.document.write('.table-manual thead th { background-color: #eee !important; font-weight: bold; text-align: center; -webkit-print-color-adjust: exact; }');
                
                // --- UTILITIES ---
                win.document.write('.text-end { text-align: right; }');
                win.document.write('.text-center { text-align: center; }');
                win.document.write('.fw-bold { font-weight: bold; }');

                // --- STYLE BADGE (HITAM PUTIH) ---
                // Hapus background warna, ganti jadi border hitam biasa agar rapi
                win.document.write('.badge { display: inline-block; padding: 2px 6px; font-size: 9px; font-weight: normal; border: 1px solid #000; border-radius: 3px; color: #000 !important; background: transparent !important; text-decoration: none; }');
                
                // Hilangkan class warna bawaan bootstrap agar tidak bentrok
                win.document.write('.bg-success, .bg-danger, .bg-warning, .bg-secondary { background-color: transparent !important; color: #000 !important; }');

                // --- PAGE SETUP ---
                win.document.write('@page { size: landscape; margin: 10mm; }');
                
                // Hide Kolom Aksi (Detail Button)
                win.document.write('.table-manual th:last-child, .table-manual td:last-child { display: none; }');
                
                win.document.write('</style></head><body>');
                
                win.document.write('<h3 style="text-align:center; margin-bottom:20px;">LAPORAN DAFTAR PEMBAYARAN</h3>');
                win.document.write(tableClone.outerHTML);
                win.document.write('</body></html>');

                win.document.close();
                win.focus();
                
                setTimeout(function() {
                    win.print();
                }, 800);
            }

            $(document).ready(function() {
                function buildFilterUrl() {
                    var params = [];
                    var clientId = $('#clientFilter').val();
                    var statusId = $('#statusFilter').val();
                    
                    if (clientId) params.push('client_id=' + clientId);
                    if (statusId) params.push('payment_status_id=' + statusId);
                    
                    var url = "{{ route('payments.index') }}";
                    
                    if (params.length > 0) {
                        url += '?' + params.join('&');
                    }
                    return url;
                }
                
                $('#clientFilter, #statusFilter').on('change', function() {
                    window.location.href = buildFilterUrl();
                });
            });
        </script>
    @endpush
@endsection