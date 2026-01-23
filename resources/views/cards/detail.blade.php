@extends('layouts.master')

@section('title')
    Detail Kartu Produksi
@endsection

@push('css')
@endpush

@push('css')
    <style>
        /* CSS Khusus Tabel Laporan */
        .table-manual { width: 100%; border-collapse: collapse; font-size: 12px; color: #000; }
        .table-manual th, .table-manual td { border: 1px solid #dee2e6; padding: 8px 10px; vertical-align: middle; }
        .table-manual thead th { background-color: #f8f9fa; font-weight: bold; text-align: center; }
        
        /* Status Badge Styles (Non-Clickable) */
        .status-box { padding: 5px 10px; border-radius: 4px; font-size: 11px; font-weight: bold; text-align: center; display: block; }
        .status-client-lunas { background-color: #e8f5e9; color: #157347; border: 1px solid #c3e6cb; }
        .status-ins-disetor { background-color: #e3f2fd; color: #0b5ed7; border: 1px solid #b6d4fe; }
        .status-pending { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .status-warning { background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; }

        .text-end { text-align: right; }
        .text-center { text-align: center; }
        .bg-total { background-color: #e9ecef; font-weight: bold; }

        @media print {
            .no-print { display: none !important; } /* Sembunyikan elemen non-print */
            .col-status { display: none !important; } /* Sembunyikan kolom status khusus */
            
            /* Reset style tabel biar rapi di kertas */
            .table-manual { width: 100% !important; font-size: 10px !important; }
            .table-manual th, .table-manual td { padding: 4px !important; border: 1px solid #000 !important; }
            .table-manual thead th { background-color: #d1d1d1 !important; -webkit-print-color-adjust: exact; }
            .bg-total { background-color: #e9ecef !important; -webkit-print-color-adjust: exact; }
        }
    </style>
@endpush

@php
    $currency = $card->policy->currency_code ?? 'Rp';
@endphp

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <h3><i class="icon-eye me-2"></i>Detail Kartu Produksi</h3>
        @endslot
        <li class="breadcrumb-item">Kartu Produksi</li>
        <li class="breadcrumb-item active"><i class="icon-eye me-2"></i>Detail</li>
    @endcomponent

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="icon-eye me-2"></i>Detail Kartu Produksi #{{ str_pad($card->id, 4, '0', STR_PAD_LEFT) }}</h5>
                    </div>
                    <div class="card-body">
                        <!-- Action Buttons -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('cards.index') }}"
                                    class="btn btn-secondary btn-sm">
                                    <i class="fa fa-arrow-left"></i> Kembali
                                </a>
                                <div>
                                    <a href="{{ route('cards.preview', $card) }}"
                                        class="btn btn-success btn-sm">
                                        <i class="fa fa-file-text"></i> Preview Kartu
                                    </a>
                                    <a href="{{ route('cards.edit', $card) }}"
                                        class="btn btn-warning btn-sm">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Detail Information -->
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">Identitas Kartu</h6>
                                <table class="table table-borderless table-sm">
                                    <tbody>
                                        <tr>
                                            <td width="40%" class="fw-bold">Status Kartu</td>
                                            <td width="5%">:</td>
                                            <td>
                                                @if ($card->cardStatus)
                                                    <span class="badge badge-primary">{{ $card->cardStatus->name }}</span>
                                                @else
                                                    <span class="badge badge-secondary">NEW</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">No. Polis</td>
                                            <td>:</td>
                                            <td>{{ $card->policy->policy_number ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Nama Asuransi</td>
                                            <td>:</td>
                                            <td>
                                                @if($card->policy && $card->policy->policyInsurances->count() > 0)
                                                    <ul class="list-unstyled mb-0">
                                                        @foreach($card->policy->policyInsurances->sortByDesc('is_main') as $polIns)
                                                            <li>
                                                                {{ $polIns->insurance->name ?? '-' }}
                                                                @if($polIns->is_main)
                                                                    <span class="badge badge-info" style="font-size: 9px; padding: 2px 4px;">Leader</span>
                                                                @else
                                                                    <span class="text-muted" style="font-size: 11px;">(Member)</span>
                                                                @endif
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Nama Tertanggung</td>
                                            <td>:</td>
                                            <td>{{ $card->client->id_name ?? $card->client->user->name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Obyek Asuransi</td>
                                            <td>:</td>
                                            <td>{{ $card->policy->object->name ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Periode</td>
                                            <td>:</td>
                                            <td>
                                                {{ $card->policy->started_date ? $card->policy->started_date->format('d/m/Y') : '-' }} s/d
                                                {{ $card->policy->ended_date ? $card->policy->ended_date->format('d/m/Y') : '-' }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">Parameter Perhitungan</h6>
                                <table class="table table-borderless table-sm">
                                    <tbody>
                                        {{-- HARGA PERTANGGUNGAN --}}
                                        <tr>
                                            <td width="40%" class="fw-bold">Harga Pertanggungan</td>
                                            <td width="5%">:</td>
                                            <td class="fw-bold">{{ $currency }} {{ number_format($card->object_value,  $currency == 'Rp' ? 0 : 2, ',', '.') }}</td>
                                        </tr>
                                        
                                        <tr>
                                            <td class="fw-bold">Persentase Premi</td>
                                            <td>:</td>
                                            <td>{{ number_format($card->premi_percentages * 100, 5, ',', '.') }}%</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Persentase Komisi</td>
                                            <td>:</td>
                                            <td>{{ number_format($card->commision_percentages * 100, 5, ',', '.') }}%</td>
                                        </tr>

                                        {{-- BIAYA & DISKON --}}
                                        <tr>
                                            <td class="fw-bold">Nilai TJH</td>
                                            <td>:</td>
                                            <td>{{ $currency }} {{ number_format($card->policy->object->tjh ?? 0,  $currency == 'Rp' ? 0 : 2, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Disc</td>
                                            <td>:</td>
                                            <td>{{ $currency }} {{ number_format($card->discount_value,  $currency == 'Rp' ? 0 : 2, ',', '.') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <hr>

                        <!-- Premi Section -->
                        <h6 class="mb-3">Perhitungan Premi</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nilai Produksi</th>
                                        <th>Biaya Polis</th>
                                        <th>Total Premi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $currency }} {{ number_format($card->production_value,  $currency == 'Rp' ? 0 : 2, ',', '.') }}</td>
                                        <td>{{ $currency }} {{ number_format($card->policy_fee,  $currency == 'Rp' ? 0 : 2, ',', '.') }}</td>
                                        <td class="fw-bold table-active">{{ $currency }} {{ number_format($card->premi_value,  $currency == 'Rp' ? 0 : 2, ',', '.') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <h6 class="mb-3 mt-4">Perhitungan Komisi</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th>Komisi Total</th>
                                        <th>Pajak</th>
                                        <th>Diskon/Agen</th>
                                        <th>Komisi Bersih (Diterima)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $currency }} {{ number_format($card->commision_bruto,  $currency == 'Rp' ? 0 : 2, ',', '.') }}</td>
                                        <td>{{ $currency }} {{ number_format($card->tax,  $currency == 'Rp' ? 0 : 2, ',', '.') }}</td>
                                        <td>{{ $currency }} {{ number_format($card->discount_agent,  $currency == 'Rp' ? 0 : 2, ',', '.') }}</td>
                                        <td class="fw-bold table-success">{{ $currency }} {{ number_format($card->commision_netto,  $currency == 'Rp' ? 0 : 2, ',', '.') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pembayaran Section -->
                        <h6 class="mb-3 mt-4">Informasi Pembayaran</h6>
						<div class="row">
							<div class="col-md-6">
								<div class="card border">
									<div class="card-header border-bottom" style="background-color: #f8f9fa;">
										<strong>Tagihan ke Nasabah</strong>
									</div>
									<div class="card-body">
										<table class="table table-sm table-borderless mb-0">
											<tr>
												<td>Total Premi</td>
												<td class="text-end">{{ $currency }} {{ number_format($card->premi_value,  $currency == 'Rp' ? 0 : 2, ',', '.') }}</td>
											</tr>
											<tr>
												<td>Discount/Agen</td>
												<td class="text-end">{{ $currency }} {{ number_format($card->discount_agent ?? 0,  $currency == 'Rp' ? 0 : 2, ',', '.') }}</td>
											</tr>
											<tr class="border-top">
												<td><strong>Netto Tagihan</strong></td>
												<td class="text-end"><strong>{{ $currency }} {{ number_format($card->client_pay,  $currency == 'Rp' ? 0 : 2, ',', '.') }}</strong></td>
											</tr>
										</table>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="card border">
									<div class="card-header border-bottom" style="background-color: #f8f9fa;">
										<strong>Pembayaran ke Asuransi</strong>
									</div>
									<div class="card-body">
										<table class="table table-sm table-borderless mb-0">
											<tr>
												<td>Total Premi</td>
												<td class="text-end">{{ $currency }} {{ number_format($card->premi_value,  $currency == 'Rp' ? 0 : 2, ',', '.') }}</td>
											</tr>
											<tr>
												<td>Komisi</td>
												<td class="text-end">{{ $currency }} {{ number_format($card->commision_netto,  $currency == 'Rp' ? 0 : 2, ',', '.') }}</td>
											</tr>
											<tr class="border-top">
												<td><strong>Netto Bayar</strong></td>
												<td class="text-end"><strong>{{ $currency }} {{ number_format($card->insurance_pay,  $currency == 'Rp' ? 0 : 2, ',', '.') }}</strong></td>
											</tr>
										</table>
									</div>
								</div>
							</div>
						</div>

                        <!-- Pelunasan Info -->
                        {{-- <h6 class="mb-3 mt-4">Informasi Pelunasan</h6>
						<div class="row">
							<div class="col-md-6">
								<table class="table table-borderless">
									<tbody>
										<tr>
											<td width="40%" class="fw-bold">Tanggal Pelunasan</td>
											<td width="5%">:</td>
											<td>{{ $card->insurance_paid_at ? $card->insurance_paid_at->format('d/m/Y') : '-' }}</td>
										</tr>
										<tr>
											<td class="fw-bold">No. Bukti</td>
											<td>:</td>
											<td>{{ $card->insurance_evident_number ?? '-' }}</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div> --}}

                        <!-- Riwayat Pembayaran -->
                        <div class="d-flex justify-content-between align-items-center mb-3 mt-4 no-print">
                            <h6 class="mb-0"><i class="fa fa-list-alt"></i> Rincian Pembayaran & Share Asuransi</h6>
                            <button onclick="printPaymentTable()" class="btn btn-primary btn-sm">
                                <i class="fa fa-print"></i> Print Tabel
                            </button>
                        </div>
                        
                        <div class="table-responsive" id="printable-area">
                            <table class="table-manual" id="paymentTable">
                                <thead>
                                    <tr>
                                        {{-- Kolom 1: Keterangan Angsuran --}}
                                        <th rowspan="2" style="width: 10%;">Angsuran</th>
                                        
                                        {{-- Kolom 2-4: Asuransi --}}
                                        <th colspan="3">Share Asuransi</th>
                                        
                                        {{-- Kolom 5-8: Financials --}}
                                        <th rowspan="2" style="width: 10%;">Biaya<br>Polis</th>
                                        <th rowspan="2" style="width: 10%;">Komisi</th>
                                        <th rowspan="2" style="width: 10%;">Pajak</th>
                                        <th rowspan="2" style="width: 12%;">Premi<br>Netto</th>
                                        
                                        {{-- Kolom Status (Tanpa Aksi) --}}
                                        <th rowspan="2" style="width: 15%;" class="col-status no-print">Status Pembayaran</th>
                                    </tr>
                                    <tr>
                                        <th style="width: 20%;">Nama Asuransi</th>
                                        <th style="width: 8%;">Share</th>
                                        <th style="width: 15%;">Nominal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        // Load relasi payments
                                        $payments = $card->payments->sortBy('sequence'); 
                                        $policyInsurances = $card->policy->policyInsurances;
                                        $insuranceCount = $policyInsurances->count();
                                    @endphp

                                    @forelse($payments as $pmt)
                                        @php
                                            // Reset Subtotal per Payment Row
                                            $subtotalPremi = 0; $subtotalBiaya = 0; $subtotalKomisi = 0;
                                            $subtotalPajak = 0; $subtotalNetto = 0;
                                        @endphp

                                        @foreach($policyInsurances as $loopIndex => $pi)
                                            @php
                                                $share = $pi->percentage;
                                                $premi = $pmt->amount * ($share);
                                                
                                                // Biaya Polis hanya di baris asuransi pertama
                                                $biayaPolis = 0;
                                                if ($loop->first) {
                                                    $biayaPolis = ($pmt->sequence == 1) ? ($card->policy_fee ?? 0) : 0; 
                                                }

                                                // Hitung Komisi & Pajak Proporsional
                                                $commPercent = $card->commision_percentages;
                                                $komisi = $premi * $commPercent;
                                                $pajak = ($pmt->amount > 0) ? ($card->tax * ($premi / $pmt->amount)) : 0;
                                                $netto = $premi - $komisi - $pajak + $biayaPolis;

                                                // Accumulate Subtotals
                                                $subtotalPremi += $premi;
                                                $subtotalBiaya += $biayaPolis;
                                                $subtotalKomisi += $komisi;
                                                $subtotalPajak += $pajak;
                                                $subtotalNetto += $netto;
                                            @endphp
                                            
                                            <tr>
                                                {{-- KOLOM KETERANGAN (Rowspan) --}}
                                                @if($loop->first)
                                                    <td rowspan="{{ $insuranceCount }}" class="text-center bg-white" style="vertical-align: top; padding-top: 10px;">
                                                        <strong>Ke-{{ $pmt->sequence }}</strong><br>
                                                        <small class="text-muted">Jatuh Tempo:</small><br>
                                                        <span class="fw-bold">{{ $pmt->deadline_at ? $pmt->deadline_at->format('d/m/y') : '-' }}</span>
                                                    </td>
                                                @endif

                                                <td>{{ $pi->insurance->name }}</td>
                                                <td class="text-center">{{ number_format(($share * 100), 2) }}%</td>
                                                <td class="text-end">{{ numberFormat($premi) }}</td>
                                                
                                                {{-- KOLOM BIAYA POLIS (Rowspan) --}}
                                                @if($loop->first)
                                                    <td rowspan="{{ $insuranceCount }}" class="text-end bg-white" style="vertical-align: top;">
                                                        {{ numberFormat($biayaPolis) }}
                                                    </td>
                                                @endif
                                                
                                                <td class="text-end">{{ numberFormat($komisi) }}</td>
                                                <td class="text-end">{{ numberFormat($pajak) }}</td>
                                                <td class="text-end">{{ numberFormat($netto) }}</td>

                                                {{-- KOLOM STATUS (Rowspan, Tanpa Tombol) --}}
                                                @if($loop->first)
                                                    <td rowspan="{{ $insuranceCount }}" class="text-center bg-white col-status no-print" style="vertical-align: middle;">
                                                        <div class="d-flex flex-column gap-2">
                                                            
                                                            {{-- Status Nasabah --}}
                                                            @if($pmt->payment_status_id != 2)
                                                                <div class="status-box status-pending">
                                                                    Belum Bayar
                                                                </div>
                                                            @else
                                                                <div class="status-box status-client-lunas">
                                                                    Lunas Nasabah
                                                                    <span class="d-block small fw-normal">{{ $pmt->paid_at ? $pmt->paid_at->format('d/m/y') : '-' }}</span>
                                                                </div>
                                                            @endif

                                                            {{-- Status Asuransi --}}
                                                            @if($pmt->payment_status_id == 2)
                                                                @if($pmt->insurance_payment_status_id != 2)
                                                                    <div class="status-box status-warning">
                                                                        Menunggu Setor
                                                                    </div>
                                                                @else
                                                                    <div class="status-box status-ins-disetor mt-1">
                                                                        Asuransi Disetor
                                                                        <span class="d-block small fw-normal">{{ $pmt->insurance_paid_at ? $pmt->insurance_paid_at->format('d/m/y') : '-' }}</span>
                                                                    </div>
                                                                @endif
                                                            @endif

                                                        </div>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach

                                        {{-- Row Total per Payment --}}
                                        <tr class="bg-total" style="border-top: 2px solid #aaa;">
                                            <td colspan="3" class="text-end">Total Pembayaran {{ $pmt->sequence }}:</td> 
                                            <td class="text-end">{{ numberFormat($subtotalPremi) }}</td>
                                            <td class="text-end">{{ numberFormat($subtotalBiaya) }}</td>
                                            <td class="text-end">{{ numberFormat($subtotalKomisi) }}</td>
                                            <td class="text-end">{{ numberFormat($subtotalPajak) }}</td>
                                            <td class="text-end">{{ numberFormat($subtotalNetto) }}</td>
                                            <td class="bg-total col-status no-print"></td> {{-- Empty cell for status column --}}
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center p-4">Belum ada data pembayaran (Installments).</td>
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
        {{-- TAMBAHAN: SCRIPT PRINT --}}
        <script>
            function printPaymentTable() {
                var objectName = "{{ strtoupper($card->policy->object->name ?? '-') }}";
                var policyNo = "{{ $card->policy->policy_number ?? '-' }}";
                var clientName = "{{ $card->client->id_name ?? $card->client->user->name ?? '-' }}";
                
                // Clone tabel untuk dimanipulasi sebelum print
                var tableClone = document.getElementById('paymentTable').cloneNode(true);
                
                // Hapus kolom status dari clone menggunakan JavaScript agar bersih
                var elementsToRemove = tableClone.getElementsByClassName('col-status');
                while(elementsToRemove.length > 0){
                    elementsToRemove[0].parentNode.removeChild(elementsToRemove[0]);
                }

                var tableHTML = tableClone.outerHTML;
                
                var win = window.open('', '', 'height=700,width=900');
                
                win.document.write('<html><head><title>Print Rincian Pembayaran</title>');
                win.document.write('<style>');
                win.document.write('body{font-family:Arial,sans-serif;padding:20px}');
                win.document.write('.table-manual{width:100%;border-collapse:collapse;font-size:10px}');
                win.document.write('.table-manual th,.table-manual td{border:1px solid #000;padding:4px;vertical-align:middle}');
                win.document.write('.table-manual thead th{background-color:#d1d1d1!important;font-weight:700;text-align:center; -webkit-print-color-adjust: exact;}');
                win.document.write('.bg-total{background-color:#e9ecef!important;font-weight:700; -webkit-print-color-adjust: exact;}');
                win.document.write('.text-end{text-align:right}');
                win.document.write('.text-center{text-align:center}');
                win.document.write('.fw-bold{font-weight:700}');
                win.document.write('</style>');
                win.document.write('</head><body>');
                
                // Header Laporan
                win.document.write('<div style="text-align:center;margin-bottom:20px">');
                win.document.write('<h3 style="margin:0">RINCIAN PEMBAYARAN & SHARE ASURANSI</h3>');
                win.document.write('<p style="margin:5px 0;font-weight:700">'+objectName+'</p>');
                win.document.write('</div>');
                
                // Info Client & Polis
                win.document.write('<table style="width:100%;font-size:11px;margin-bottom:10px;border:none">');
                win.document.write('<tr><td style="border:none;width:50%"><b>NO POLIS:</b> '+policyNo+'</td>');
                win.document.write('<td style="border:none;text-align:right;width:50%"><b>Client:</b> '+clientName+'</td></tr>');
                win.document.write('</table>');
                
                win.document.write(tableHTML);
                
                win.document.write('</body></html>');
                
                win.document.close();
                win.focus();
                
                setTimeout(function(){
                    win.print();
                    win.close();
                }, 500);
            }
        </script>
    @endpush

@endsection
