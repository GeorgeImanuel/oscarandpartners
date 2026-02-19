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
    <div class="d-flex justify-content-between align-items-center">
        @component('components.breadcrumb')
            @slot('breadcrumb_title')
                <h3>Detail Kartu Produksi #{{ str_pad($card->id, 4, '0', STR_PAD_LEFT) }}</h3>
            @endslot
            <li class="breadcrumb-item"><a href="{{ route('cards.index') }}">Kartu Produksi</a></li>
            <li class="breadcrumb-item active">Detail</li>
        @endcomponent
        <div class="d-flex gap-2"> 
            <a href="{{ route('cards.index') }}" class="btn btn-info btn-sm">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('cards.preview', $card) }}" class="btn btn-primary btn-sm text-nowrap">
                <i class="fa fa-file-text"></i> Preview Kartu
            </a>
        </div>
    </div>


    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">

                    <div class="card mb-4 shadow-sm">
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="mb-3">Identitas Kartu</h6>
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
                                                            @foreach($card->policy->policyInsurances->sortByDesc('percentage') as $polIns)
                                                                <li>
                                                                    {{ $polIns->insurance->name ?? '-' }} ({{ number_format($polIns->percentage * 100, 2) }}%)
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
                                                <td>{{ $card->policy->policyObjects->first()?->object?->name ?? '-' }} - {{ $card->policy->policyObjects->first()?->object?->keterangan ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Periode</td>
                                                <td>:</td>
                                                <td>
                                                    {{ $card->policy->started_date ? $card->policy->started_date->format('d M Y') : '-' }} -
                                                    {{ $card->policy->ended_date ? $card->policy->ended_date->format('d M Y') : '-' }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-md-6">
                                    <h6 class="mb-3">Parameter Perhitungan</h6>
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
                                                <td>{{ number_format($card->commision_percentage * 100, 5, ',', '.') }}%</td>
                                            </tr>

                                            {{-- BIAYA & DISKON --}}
                                            <tr>
                                                <td class="fw-bold">Nilai TJH</td>
                                                <td>:</td>
                                                <td>{{ $currency }} {{ number_format($card->policy->policyObject->object->tjh ?? 0,  $currency == 'Rp' ? 0 : 2, ',', '.') }}</td>
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
                        </div>
                    </div>
                    
                    <div class="card mb-4 shadow-sm">
                        <div class="card-body p-4">
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
                                            <td class="fw-bold table-active">{{ $currency }} {{ number_format($card->commision_netto,  $currency == 'Rp' ? 0 : 2, ',', '.') }}</td>
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
                                                    <td class="text-end">{{ $currency }} {{ number_format($card->client_pay,  $currency == 'Rp' ? 0 : 2, ',', '.') }}</td>
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
                        </div>
                    </div>

                    <div class="card mb-4 shadow-sm">
                        <div class="card-body p-4">
                            {{-- TABEL PERHITUNGAN PREMI (MASTER) --}}
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="card shadow-sm border h-100">
                                        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                                            <h6 class="card-title mb-0"><i class="fa fa-calculator"></i> Perhitungan Premi (Master)</h6>
                                            
                                            {{-- TOMBOL PRINT PREMI DISINI --}}
                                            <button onclick="printPremi()" class="btn btn-primary btn-sm btn-print-custom no-print">
                                                <i class="fa fa-print"></i> Print Premi
                                            </button>
                                        </div>
                                        <div class="card-body p-0" id="master-table-section">
                                            <div class="table-responsive" id="master-table-section">
                                                {{-- Tabel Master --}}
                                                <table class="table-manual table-striped mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th colspan="3" class="no-border-bottom">Ko. Asuransi</th>
                                                            <th rowspan="2" style="width: 10%;">Biaya<br>Polis</th>
                                                            <th rowspan="2" style="width: 10%;">Komisi</th>
                                                            <th rowspan="2" style="width: 10%;">Pajak</th>
                                                            <th rowspan="2" style="width: 12%;">Premi<br>Netto</th>
                                                        </tr>
                                                        <tr>
                                                            <th style="width: 15%;">Nama</th>
                                                            <th style="width: 5%;">%</th>
                                                            <th style="width: 15%;">Premi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            // Data Master
                                                            $basePremi = $card->production_value;
                                                            $rowCount = $card->policy->policyInsurances->count();
                                                            $g_premi = 0; $g_komisi = 0; $g_pajak = 0; $g_netto = 0;
                                                        @endphp

                                                        @foreach($card->policy->policyInsurances->sortByDesc('percentage') as $loopIndex => $pi)
                                                            @php
                                                                $share = $pi->percentage;
                                                                $rowPremi = $basePremi * $share;
                                                                $rowBiaya = ($loop->first) ? $card->policy_fee : 0;
                                                                $rowKomisi = $card->commision_bruto * $share;
                                                                $rowPajak = ($basePremi > 0) ? ($pi->tax) : 0; 
                                                                $rowNetto = $rowPremi - $rowKomisi + $rowPajak + $rowBiaya;

                                                                $g_premi += $rowPremi;
                                                                $g_komisi += $rowKomisi;
                                                                $g_pajak += $rowPajak;
                                                                $g_netto += $rowNetto;
                                                            @endphp
                                                            <tr>
                                                                <td>{{ $pi->insurance->name }}</td>
                                                                <td class="text-center">{{ number_format($share * 100, 2) }}%</td>
                                                                <td class="text-end">{{ $card->policy->currency_code }} {{ number_format($rowPremi, 2, ',', '.') }}</td>
                                                                
                                                                @if($loop->first)
                                                                    <td rowspan="{{ $rowCount }}" class="text-end bg-white" style="vertical-align: top;">
                                                                        {{ $card->policy->currency_code }} {{ number_format($card->policy_fee, 2, ',', '.') }}
                                                                    </td>
                                                                @endif

                                                                <td class="text-end">{{ $card->policy->currency_code }} {{ number_format($rowKomisi, 2, ',', '.') }}</td>
                                                                <td class="text-end">{{ $card->policy->currency_code }} {{ number_format($rowPajak, 2, ',', '.') }}</td>
                                                                <td class="text-end fw-bold">{{ $card->policy->currency_code }} {{ number_format($rowNetto, 2, ',', '.') }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr class="bg-total">
                                                            <td colspan="2" class="text-center">TOTAL</td>
                                                            <td class="text-end">{{ $card->policy->currency_code }} {{ number_format($g_premi, 2, ',', '.') }}</td>
                                                            <td class="text-end">{{ $card->policy->currency_code }} {{ number_format($card->policy_fee, 2, ',', '.') }}</td>
                                                            <td class="text-end">{{ $card->policy->currency_code }} {{ number_format($g_komisi, 2, ',', '.') }}</td>
                                                            <td class="text-end">{{ $card->policy->currency_code }} {{ number_format($g_pajak, 2, ',', '.') }}</td>
                                                            <td class="text-end text-primary">{{ $card->policy->currency_code }} {{ number_format($g_netto, 2, ',', '.') }}</td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($card->payment_type_id == 1)
                                @php
                                    $payment = $card->payments->first(); // Ambil payment pertama untuk full payment
                                @endphp
                                <div class="mb-4 mt-3 p-3 border rounded bg-light no-print">
                                    <div class="d-flex justify-content-between align-items-center">
                                        
                                        {{-- BAGIAN KIRI: LABEL STATUS --}}
                                        <div>
                                            <strong class="text-muted small">Status Pembayaran (Full Payment):</strong><br>
                                            
                                            {{-- 1. Status Nasabah --}}
                                            @if($payment && $payment->payment_status_id == 2)
                                                <span class="badge bg-success">
                                                    <i class="fa fa-check"></i> NASABAH LUNAS 
                                                    ({{ $payment->paid_at ? $payment->paid_at->format('d M y') : '-' }})
                                                </span>
                                            @else
                                                <span class="badge bg-danger">NASABAH BELUM BAYAR</span>
                                            @endif

                                            {{-- 2. Status Asuransi (Cuma tampil kalo nasabah udah lunas) --}}
                                            @if($payment && $payment->payment_status_id == 2)
                                                <i class="fa fa-arrow-right mx-2 text-muted"></i>
                                                @if($payment->insurance_payment_status_id == 2)
                                                    <span class="badge bg-primary">
                                                        <i class="fa fa-check"></i> ASURANSI DISETOR 
                                                        ({{ $payment->insurance_paid_at ? $payment->insurance_paid_at->format('d M y') : '-' }})
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning text-dark">ASURANSI BELUM DISETOR</span>
                                                @endif
                                            @endif
                                        </div>

                                        {{-- BAGIAN KANAN: TOMBOL AKSI --}}
                                        <div>
                                            @if($payment && $payment->payment_status_id == 2 && $payment->insurance_payment_status_id == 2)
                                                <button class="btn btn-outline-secondary btn-sm" disabled>
                                                    <i class="fa fa-lock"></i> Transaksi Selesai
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Riwayat Pembayaran -->
                            @if($card->payment_type_id != 1)
                                <div class="card shadow-sm border mt-4">
                                    <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0"><i class="fa fa-list-alt"></i> Rincian Pembayaran & Share Asuransi</h6>
                                        <button onclick="printPaymentTable()" class="btn btn-primary btn-sm">
                                            <i class="fa fa-print"></i> Print Tabel
                                        </button>
                                    </div>
                                    
                                    <div class="table-responsive" id="installment-table-section">
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
                                                    $policyInsurances = $card->policy->policyInsurances->sortByDesc('percentage');
                                                @endphp
                                                @forelse($payments as $pmt)
                                                    @php
                                                        // Reset Subtotal per Payment Row
                                                        $subtotalPremi = 0; $subtotalBiaya = 0; $subtotalKomisi = 0;
                                                        $subtotalPajak = 0; $subtotalNetto = 0;
                                                        
                                                        // Jumlah total installment
                                                        $totalInstallments = $payments->count();
                                                    @endphp

                                                    @foreach($policyInsurances as $loopIndex => $pi)
                                                        @php
                                                            $share = $pi->percentage;
                                                            $premi = $card->production_value/$totalInstallments * ($share);
                                                            
                                                            // Biaya Polis hanya di baris asuransi pertama
                                                            $biayaPolis = 0;
                                                            if ($loop->first) {
                                                                $biayaPolis = ($pmt->sequence == 1) ? ($card->policy_fee ?? 0) : 0; 
                                                            }

                                                            // Komisi per asuransi per installment
                                                            // Total komisi * share / jumlah installment
                                                            $komisiPerAsuransi = $card->commision_bruto * $share;
                                                            $komisi = $komisiPerAsuransi / $totalInstallments;
                                                            
                                                            // Tax per asuransi per installment (dari database)
                                                            // Tax sudah disimpan per asuransi, tinggal dibagi jumlah installment
                                                            $pajak = ($pi->tax ?? 0) / $totalInstallments;
                                                            
                                                            $netto = $premi + $biayaPolis - $komisi + $pajak;

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
                                                                    <span class="fw-bold">{{ $pmt->deadline_at ? $pmt->deadline_at->format('d M y') : '-' }}</span>
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
                                                                                <span class="d-block small fw-normal">{{ $pmt->paid_at ? $pmt->paid_at->format('d M y') : '-' }}</span>
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
                                                                                    <span class="d-block small fw-normal">{{ $pmt->insurance_paid_at ? $pmt->insurance_paid_at->format('d M y') : '-' }}</span>
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
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // --- VARIABEL GLOBAL UNTUK PRINT ---
            var objectName = "{{ strtoupper($card->policy->policyObjects->first()?->object?->name ?? '-') }}";
            var policyNo = "{{ $card->policy->policy_number ?? '-' }}";
            var clientName = "{{ $card->client->id_name ?? $card->client->user->name ?? '-' }}";

            // --- 1. FUNGSI PRINT PREMI (MASTER) ---
            function printPremi() {
                // Ambil tabel dari ID 'master-table-section'
                var content = document.getElementById('master-table-section');
                
                if (!content) {
                    Swal.fire('Error', 'Tabel Premi tidak ditemukan', 'error');
                    return;
                }

                // Kirim HTML ke fungsi layout
                // (Tabel premi biasanya tidak perlu hapus kolom aksi, jadi langsung innerHTML)
                printLayout(content.innerHTML, 'PERHITUNGAN PEMBAYARAN PREMI');
            }

            // --- 2. FUNGSI PRINT INSTALLMENT (JADWAL) ---
            function printPaymentTable() {
                var tableOriginal = document.getElementById('paymentTable');
                
                if (!tableOriginal) {
                    Swal.fire('Error', 'Tabel Installment tidak ditemukan', 'error');
                    return;
                }

                // Clone tabel agar manipulasi (hapus kolom) tidak merusak tampilan web asli
                var tableClone = tableOriginal.cloneNode(true);
                
                // Logic Hapus Kolom Status/Aksi dari hasil clone
                var elementsToRemove = tableClone.getElementsByClassName('col-status');
                while(elementsToRemove.length > 0){
                    elementsToRemove[0].parentNode.removeChild(elementsToRemove[0]);
                }
                
                // Hapus elemen lain yang berkelas 'no-print' di dalam tabel
                var noPrintElements = tableClone.getElementsByClassName('no-print');
                while(noPrintElements.length > 0){
                    noPrintElements[0].parentNode.removeChild(noPrintElements[0]);
                }

                // Kirim HTML clone yang sudah bersih ke fungsi layout
                printLayout(tableClone.outerHTML, 'RINCIAN PEMBAYARAN & SHARE ASURANSI');
            }

            // --- 3. HELPER: LAYOUT KERTAS & STYLE ---
            function printLayout(htmlContent, title) {
                var win = window.open('', '', 'height=700,width=900');
                
                win.document.write('<html><head><title>Print Laporan</title>');
                win.document.write('<style>');
                win.document.write('body{font-family:Arial,sans-serif;padding:20px}');
                
                // Style Tabel
                win.document.write('.table-manual, table {width:100%;border-collapse:collapse;font-size:10px}');
                win.document.write('th, td {border:1px solid #000;padding:4px;vertical-align:middle}');
                win.document.write('thead th {background-color:#d1d1d1!important;font-weight:700;text-align:center; -webkit-print-color-adjust: exact;}');
                win.document.write('.bg-total {background-color:#e9ecef!important;font-weight:700; -webkit-print-color-adjust: exact;}');
                
                // Helper Utility Classes
                win.document.write('.text-end{text-align:right}');
                win.document.write('.text-center{text-align:center}');
                win.document.write('.fw-bold{font-weight:700}');
                
                // Paksa sembunyikan class no-print (backup)
                win.document.write('.no-print {display:none !important}');
                win.document.write('</style>');
                win.document.write('</head><body>');
                
                // Header Laporan
                win.document.write('<div style="text-align:center;margin-bottom:20px">');
                win.document.write('<h3 style="margin:0">'+ title +'</h3>');
                win.document.write('<p style="margin:5px 0;font-weight:700">'+objectName+'</p>');
                win.document.write('</div>');
                
                // Info Client & Polis
                win.document.write('<table style="width:100%;font-size:11px;margin-bottom:10px;border:none">');
                win.document.write('<tr><td style="border:none;width:50%"><b>NO POLIS:</b> '+policyNo+'</td>');
                win.document.write('<td style="border:none;text-align:right;width:50%"><b>Client:</b> '+clientName+'</td></tr>');
                win.document.write('</table>');
                
                // Isi Tabel Utama
                win.document.write(htmlContent);
                
                win.document.write('</body></html>');
                
                win.document.close();
                win.focus();
                
                setTimeout(function(){
                    win.print();
                    win.close();
                }, 500);
            }
        </script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endpush

@endsection
