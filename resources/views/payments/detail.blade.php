@extends('layouts.master')

@section('title')Detail Pembayaran
@endsection

@push('css')
    <style>
        /* Style untuk Tombol agar mirip DataTables */
        .btn-print-custom {
            background-color: #0d6efd;
            color: white;
            border: 1px solid #0d6efd;
            padding: 5px 10px;
            font-size: 12px;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-print-custom:hover { background-color: #0b5ed7; }

        .table-responsive-custom {
            overflow-x: auto; /* Aktifkan scroll samping */
            -webkit-overflow-scrolling: touch;
            margin-bottom: 15px;
            border: 1px solid #ddd;
        }

        /* 2. Update Table Manual biar Lebar di Web */
        .table-manual {
            width: 100%;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
            
            /* INI PENTING: Paksa tabel melebar min 1200px biar ga gepeng */
            min-width: 1200px; 
            
            font-size: 12px; 
            color: #000;
        }

        .table-manual th, .table-manual td {
            border: 1px solid #000 !important;
            padding: 8px 10px; /* Padding web agak lega */
            vertical-align: middle;
            white-space: nowrap; /* Teks jangan turun baris di web */
        }
        .table-manual thead th {
            background-color: #d1d1d1 !important;
            color: #000 !important;
            font-weight: bold;
            text-align: center;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .bg-total {
            background-color: #e9ecef !important;
            font-weight: bold;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .text-end { text-align: right; }
        .text-center { text-align: center; }
        .fw-bold { font-weight: bold; }
        
        /* Dynamic Installment Form Styles */
        .installment-row {
            background-color: #f8f9fa;
            transition: all 0.3s ease;
        }
        .installment-row:hover {
            background-color: #e9ecef;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .installment-amount {
            font-weight: bold;
            font-size: 1.05em;
        }
        .form-control-plaintext {
            padding-top: 0.375rem;
            padding-bottom: 0.375rem;
            margin-bottom: 0;
            line-height: 1.5;
        }

        .status-box {
            padding: 8px 10px;
            border-radius: 6px;
            margin-bottom: 6px;
            font-size: 11px;
            font-weight: bold;
            text-align: center;
            width: 100%;
            line-height: 1.2;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        .status-box .date {
            display: block; font-weight: normal; font-size: 10px; margin-top: 3px; opacity: 0.8;
        }

        /* 2. Warna Status */
        .status-client-lunas { background-color: #e8f5e9; color: #157347; border: 1px solid #c3e6cb; }
        .status-ins-disetor { background-color: #e3f2fd; color: #0b5ed7; border: 1px solid #b6d4fe; }
        
        /* 3. Label Merah "Belum Bayar" */
        .label-belum-bayar {
            background-color: #f8d7da; /* Background Merah Muda */
            color: #721c24;            /* Teks Merah Gelap */
            border: 1px solid #f5c6cb; /* Border Merah Tipis */
            
            /* Layout (Disamakan dengan .status-box agar rapi) */
            padding: 6px 10px;
            border-radius: 6px;
            margin-bottom: 6px;
            font-size: 11px;
            font-weight: bold;
            text-align: center;
            width: 100%;
            display: block;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* 4. Tombol Hijau Tua (Custom) */
        .btn-dark-green {
            background-color: #154c38; color: white; border: none;
            font-size: 11px; font-weight: 500; width: 100%;
            padding: 6px 10px; border-radius: 5px;
            transition: all 0.2s; text-align: center; display: block; text-decoration: none;
        }
        .btn-dark-green:hover { background-color: #0f3929; color: white; }

        @media print {
            .no-print { display: none !important; }
            
            /* Matikan scroll saat print */
            .table-responsive-custom {
                overflow: visible !important;
                border: none !important;
            }

            /* Reset tabel biar muat di kertas (Hapus min-width tadi) */
            .table-manual {
                width: 100% !important;
                min-width: 0 !important; /* RESET disini */
                font-size: 10px !important; /* Kecilkan font */
            }

            .table-manual th, .table-manual td {
                padding: 4px !important; /* Rapatkan padding */
                white-space: normal !important; /* Teks boleh turun baris */
            }

            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
            @page { margin: 10mm; size: landscape; } 
        }
    </style>
@endpush

@section('content')
    @component('components.breadcrumb')
    @slot('breadcrumb_title')
    <h3>Detail Pembayaran</h3>
    @endslot
    <li class="breadcrumb-item"><a href="{{ route('payments.index') }}">Pembayaran</a></li>
    <li class="breadcrumb-item active">Detail</li>
    @endcomponent

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0 no-print">
                        @php
                            $card = $payment->card;
                            $isFullPayment = ($card->payment_type_id == 1);
                        @endphp
                        <div class="d-flex justify-content-between align-items-center">
                            <h5>Perhitungan Pembayaran Premi</h5>
                            <div class="d-flex gap-2">
                                <a href="{{ route('payments.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="fa fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @php
                            $card = $payment->card;
                            $policy = $card->policy;
                            $client = $card->client;
                            $isFullPayment = ($card->payment_type_id == 1);
                        @endphp

                        {{-- Info Header Laporan --}}
                        <div class="mb-4">
                            <h6 class="fw-bold mb-2 text-center">PERHITUNGAN PEMBAYARAN PREMI {{ strtoupper($policy->object->name ?? '') }}</h6>
                            <div style="font-size: 11px; text-align: center;">
                                <strong>NO POLIS :</strong> {{ $policy->policy_number ?? '-' }} / 
                                {{ $policy->started_date ? $policy->started_date->format('d M Y') : '-' }} - 
                                {{ $policy->ended_date ? $policy->ended_date->format('Y') : '-' }}
                            </div>
                        </div>

                        {{--DETAIL POLIS --}}
                        <div class="row mb-3 no-print">
                            <div class="col-12">
                                <div class="card border mb-3">
                                    <div class="card-header bg-white border-bottom">
                                        <h6 class="card-title mb-0"><i class="fa fa-file-text-o"></i> Detail Polis</h6>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <table class="table table-sm table-borderless mb-0">
                                                    <tr>
                                                        <td width="30%" class="text-muted">Client</td>
                                                        <td class="fw-bold">: {{ $client->user->name ?? $client->id_name ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Object</td>
                                                        <td class="fw-bold">: {{ $policy->object->name ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Total Premi</td>
                                                        <td class="fw-bold text-primary">: {{ $policy->currency_code }} {{ number_format($card->premi_value ?? 0, 2, ',', '.') }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <table class="table table-sm table-borderless mb-0">
                                                    <tr>
                                                        <td width="30%" class="text-muted">Biaya Polis</td>
                                                        <td class="fw-bold">: {{ $policy->currency_code }} {{ number_format($card->policy_fee ?? 0, 2, ',', '.') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Komisi</td>
                                                        <td class="fw-bold">: {{ number_format(($card->commision_percentages ?? 0) * 100, 2) }}%</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

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
                                    <div class="card-body p-0">
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
                                                        $rowCount = $policy->policyInsurances->count();
                                                        $g_premi = 0; $g_komisi = 0; $g_pajak = 0; $g_netto = 0;
                                                    @endphp

                                                    @foreach($policy->policyInsurances as $loopIndex => $pi)
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
                                                            <td class="text-end">{{ $policy->currency_code }} {{ number_format($rowPremi, 2, ',', '.') }}</td>
                                                            
                                                            @if($loop->first)
                                                                <td rowspan="{{ $rowCount }}" class="text-end bg-white" style="vertical-align: top;">
                                                                    {{ $policy->currency_code }} {{ number_format($card->policy_fee, 2, ',', '.') }}
                                                                </td>
                                                            @endif

                                                            <td class="text-end">{{ $policy->currency_code }} {{ number_format($rowKomisi, 2, ',', '.') }}</td>
                                                            <td class="text-end">{{ $policy->currency_code }} {{ number_format($rowPajak, 2, ',', '.') }}</td>
                                                            <td class="text-end fw-bold">{{ $policy->currency_code }} {{ number_format($rowNetto, 2, ',', '.') }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr class="bg-total">
                                                        <td colspan="2" class="text-center">TOTAL</td>
                                                        <td class="text-end">{{ $policy->currency_code }} {{ number_format($g_premi + $card->policy_fee, 2, ',', '.') }}</td>
                                                        <td class="text-end">{{ $policy->currency_code }} {{ number_format($card->policy_fee, 2, ',', '.') }}</td>
                                                        <td class="text-end">{{ $policy->currency_code }} {{ number_format($g_komisi, 2, ',', '.') }}</td>
                                                        <td class="text-end">{{ $policy->currency_code }} {{ number_format($g_pajak, 2, ',', '.') }}</td>
                                                        <td class="text-end text-primary">{{ $policy->currency_code }} {{ number_format($g_netto, 2, ',', '.') }}</td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- STATUS BAR (Husus Full Payment) --}}
                        @if($isFullPayment)
                            <div class="mb-4 mt-3 p-3 border rounded bg-light no-print">
                                <div class="d-flex justify-content-between align-items-center">
                                    
                                    {{-- BAGIAN KIRI: LABEL STATUS --}}
                                    <div>
                                        <strong class="text-muted small">Status Pembayaran (Full Payment):</strong><br>
                                        
                                        {{-- 1. Status Nasabah --}}
                                        @if($payment->payment_status_id == 2)
                                            <span class="badge bg-success">
                                                <i class="fa fa-check"></i> NASABAH LUNAS 
                                                ({{ $payment->paid_at ? $payment->paid_at->format('d/m/y') : '-' }})
                                            </span>
                                        @else
                                            <span class="badge bg-danger">NASABAH BELUM BAYAR</span>
                                        @endif

                                        {{-- 2. Status Asuransi (Cuma tampil kalo nasabah udah lunas) --}}
                                        @if($payment->payment_status_id == 2)
                                            <i class="fa fa-arrow-right mx-2 text-muted"></i>
                                            @if($payment->insurance_payment_status_id == 2)
                                                <span class="badge bg-primary">
                                                    <i class="fa fa-check"></i> ASURANSI DISETOR 
                                                    ({{ $payment->insurance_paid_at ? $payment->insurance_paid_at->format('d/m/y') : '-' }})
                                                </span>
                                            @else
                                                <span class="badge bg-warning text-dark">ASURANSI BELUM DISETOR</span>
                                            @endif
                                        @endif
                                    </div>

                                    {{-- BAGIAN KANAN: TOMBOL AKSI --}}
                                    <div>
                                        {{-- Skenario 1: Bayar Nasabah --}}
                                        @if($payment->payment_status_id != 2)
                                            <button type="button" class="btn btn-success btn-trigger-client" 
                                                data-id="{{ $payment->id }}" data-amount="{{ $payment->amount }}">
                                                <i class="fa fa-money"></i> Terima Pembayaran
                                            </button>
                                        
                                        {{-- Skenario 2: Setor Asuransi (Muncul jika Nasabah Lunas & Asuransi Belum) --}}
                                        @elseif($payment->insurance_payment_status_id != 2)
                                            <button type="button" class="btn btn-info text-white btn-trigger-insurance" 
                                                data-id="{{ $payment->id }}" 
                                                data-amount="{{ $payment->insurance_paid_amount ?? $payment->amount }}">
                                                <i class="fa fa-bank"></i> Setor ke Asuransi
                                            </button>
                                        
                                        {{-- Skenario 3: Semua Lunas --}}
                                        @else
                                            <button class="btn btn-outline-secondary btn-sm" disabled>
                                                <i class="fa fa-lock"></i> Transaksi Selesai
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- HANYA TAMPIL JIKA TIPE = 1 (CASH) DAN STATUS BELUM LUNAS (!= 2) --}}
                        @if($card->payment_type_id == 1 && $payment->payment_status_id != 2)
                            <div class="row mb-3 no-print">
                                <div class="col-12">
                                    <div class="card border shadow-sm mb-3 border">
                                        <div class="card-header bg-white border-bottom">
                                            <h6 class="card-title mb-0"><i class="fa fa-cogs"></i> Pengaturan Pembayaran</h6>
                                        </div>
                                        <div class="card-body p-3">
                                            <div class="row align-items-center">
                                                <div class="col-md-6 border-end">
                                                    <div class="mb-3 mb-md-0">
                                                        <label class="form-label text-muted small text-uppercase fw-bold">Tipe Pembayaran</label>
                                                        <select id="payment-type-select" class="form-select form-select-lg">
                                                            @foreach($paymentTypes as $type)
                                                                <option value="{{ $type->id }}" data-name="{{ strtolower($type->name) }}"
                                                                    {{ $card->paymentType && $card->paymentType->id == $type->id ? 'selected' : '' }}>
                                                                    {{ $type->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    {{-- Installment Count Input --}}
                                                    <div id="installment-section" style="display: none;">
                                                        <label class="form-label text-muted small text-uppercase fw-bold">Jumlah Cicilan</label>
                                                        <div class="input-group">
                                                            <input type="number" id="installment-count" class="form-control form-control-lg" 
                                                                min="1" max="12" value="{{ count($allPayments) > 0 ? count($allPayments) : 1 }}">
                                                            <span class="input-group-text">Kali</span>
                                                        </div>
                                                        <small class="text-muted">Ubah angka untuk generate otomatis, atau gunakan tombol Tambah/Hapus di bawah.</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Dynamic Installment Form --}}
                        
                            <div id="installment-items-section" class="mb-4 no-print" style="display: none;">
                                <div class="card border">
                                    <div class="card-header bg-light border-bottom">
                                        <h6 class="mb-0 text-dark"><i class="fa fa-list-ol"></i> Rincian Cicilan</h6>
                                    </div>
                                    <div class="card-body">
                                        <div id="installments-container"></div>
                                        
                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <div class="alert alert-light border mb-0 py-2">
                                                    <i class="fa fa-info-circle"></i> Total Cicilan: <strong id="total-installments-display">0</strong>
                                                </div>
                                            </div>
                                            <div class="col-md-6 text-end">
                                                <div class="alert alert-light mb-0 py-2 text-end">
                                                    Total Nilai: <strong id="total-amount-display">{{ $policy->currency_code }} {{ number_format(0, 2, ',', '.') }}</strong>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-3 text-center border-top pt-3">
                                            <button type="button" id="apply-installments-btn" class="btn btn-success px-5">
                                                <i class="fa fa-save"></i> SIMPAN PERUBAHAN
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                        @if(!$isFullPayment)
                            {{-- Tabel View Laporan --}}
                            <div class="card shadow-sm border">
                                <div class="card-header border-bottom d-flex justify-content-between align-items-left">
                                    <h6 class="fw-bold mb-2">PERHITUNGAN INSTALLMENT</h6>
                                    <button onclick="printInstallment()" class="btn btn-info btn-sm text-white btn-print-custom no-print">
                                        <i class="fa fa-calendar"></i> Print Jadwal
                                    </button>
                                </div>
                                
                                <div class="table-responsive-custom" id="installment-table-section">
                                    {{-- ... (Isi tabel paymentTable BIARKAN SAMA SEPERTI SEBELUMNYA) ... --}}
                                    {{-- Copy paste tabel dari kode sebelumnya di sini --}}
                                    <table class="table-manual" id="paymentTable">
                                        <thead>
                                            <tr>
                                                {{-- Kolom 1: Keterangan Angsuran --}}
                                                <th rowspan="2" style="width: 10%;">Keterangan</th>
                                                
                                                {{-- Kolom 2-4: Asuransi --}}
                                                <th colspan="3" style="border-bottom: none;">Ko. Asuransi</th>
                                                
                                                {{-- Kolom 5-8: Financials --}}
                                                <th rowspan="2" style="width: 10%;">Biaya<br>Polis</th>
                                                <th rowspan="2" style="width: 10%;">Komisi</th>
                                                <th rowspan="2" style="width: 10%;">Pajak</th>
                                                <th rowspan="2" style="width: 12%;">Premi<br>Netto</th>
                                                
                                                {{-- KOLOM BARU (Hanya Tampil di Web, Hide saat Print) --}}
                                                <th rowspan="2" style="width: 15%;" class="no-print">Status & Aksi</th>
                                            </tr>
                                            <tr>
                                                <th colspan="2" style="width: 25%;">Share</th>
                                                <th style="width: 15%;">Premi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $grandTotalPremi = 0; $grandTotalBiaya = 0; $grandTotalKomisi = 0;
                                                $grandTotalPajak = 0; $grandTotalNetto = 0;
                                                $activeDetailId = $payment->id ?? null;
                                            @endphp

                                            @foreach($allPayments as $index => $pmt)
                                                @php
                                                    $policyInsurances = $pmt->card->policy->policyInsurances;
                                                    $insuranceCount = $policyInsurances->count();
                                                    $rowSpanCount = $insuranceCount;
                                                    
                                                    $subtotalPremi = 0; $subtotalBiaya = 0; $subtotalKomisi = 0;
                                                    $subtotalPajak = 0; $subtotalNetto = 0;
                                                    
                                                    // Jumlah total installment
                                                    $totalInstallments = $allPayments->count();
                                                @endphp

                                                @foreach($policyInsurances as $loopIndex => $pi)
                                                    @php
                                                        $share = $pi->percentage;
                                                        $premi = $pmt->amount * ($share);

                                                        $biayaPolis = 0;
                                                        if ($index == 0 && $loop->first) {
                                                            $biayaPolis = $card->policy_fee ?? 0; 
                                                        }

                                                        // Komisi per asuransi per installment
                                                        // Total komisi * share / jumlah installment
                                                        $komisiPerAsuransi = $card->commision_bruto * $share;
                                                        $komisi = $komisiPerAsuransi / $totalInstallments;
                                                        
                                                        // Tax per asuransi per installment (dari database)
                                                        // Tax sudah disimpan per asuransi, tinggal dibagi jumlah installment
                                                        $pajak = ($pi->tax ?? 0) / $totalInstallments;
                                                        
                                                        $netto = $premi - $komisi - $pajak + $biayaPolis;

                                                        $subtotalPremi += $premi;
                                                        $subtotalBiaya += $biayaPolis;
                                                        $subtotalKomisi += $komisi;
                                                        $subtotalPajak += $pajak;
                                                        $subtotalNetto += $netto;
                                                    @endphp
                                                    
                                                    <tr>
                                                        {{-- KOLOM KETERANGAN (Cuma muncul di baris pertama loop asuransi) --}}
                                                        @if($loop->first)
                                                            <td rowspan="{{ $rowSpanCount }}" class="text-center" style="vertical-align: top; padding-top: 10px;">
                                                                <strong>ANGSURAN {{ $pmt->sequence }}</strong><br>
                                                                <small>{{ $pmt->deadline_at ? $pmt->deadline_at->format('d/m/Y') : '-' }}</small>
                                                            </td>
                                                        @endif

                                                        <td>{{ $pi->insurance->name }}</td>
                                                        <td class="text-center">{{ number_format(($share * 100), 2) }}%</td>
                                                        <td class="text-end">{{ numberFormat($premi) }}</td>
                                                        
                                                        {{-- KOLOM BIAYA POLIS --}}
                                                        @if($loop->first)
                                                            <td rowspan="{{ $rowSpanCount }}" class="text-end" style="vertical-align: top; padding-top: 8px;">
                                                                {{ numberFormat($biayaPolis) }}
                                                            </td>
                                                        @endif
                                                        
                                                        <td class="text-end">{{ numberFormat($komisi) }}</td>
                                                        <td class="text-end">{{ numberFormat($pajak) }}</td>
                                                        <td class="text-end">{{ numberFormat($netto) }}</td>

                                                        {{-- KOLOM STATUS & AKSI --}}
                                                        @if($loop->first)
                                                            <td rowspan="{{ $rowSpanCount }}" class="text-center no-print" style="vertical-align: middle; padding: 12px;">
                                                                
                                                                {{-- Wrapper Flex: Agar elemen menumpuk rapi ke bawah --}}
                                                                <div class="d-flex flex-column align-items-center w-100 gap-2">

                                                                    {{-- ========================================== --}}
                                                                    {{-- 1. URUSAN NASABAH --}}
                                                                    {{-- ========================================== --}}
                                                                    @if($pmt->payment_status_id != 2)
                                                                        
                                                                        {{-- KONDISI A: BELUM BAYAR (ID: 1, 3, 4) --}}
                                                                        <div class="w-100">
                                                                            <div class="label-belum-bayar">Belum Bayar</div>

                                                                            @if($pmt->id == $activeDetailId)
                                                                                <button type="button" 
                                                                                    class="btn-dark-green btn-trigger-client"
                                                                                    data-id="{{ $pmt->id }}"
                                                                                    data-amount="{{ $pmt->amount }}">
                                                                                    Bayar Nasabah
                                                                                </button>
                                                                            @endif
                                                                        </div>

                                                                    @else
                                                                        
                                                                        {{-- KONDISI B: SUDAH LUNAS (ID: 2) --}}
                                                                        <div class="status-box status-client-lunas">
                                                                            Nasabah Lunas
                                                                            <span class="date">{{ $pmt->paid_at ? $pmt->paid_at->format('d/m/y') : '-' }}</span>
                                                                        </div>

                                                                    @endif


                                                                    {{-- ========================================== --}}
                                                                    {{-- 2. URUSAN ASURANSI --}}
                                                                    {{-- (Hanya muncul jika Nasabah SUDAH Lunas) --}}
                                                                    {{-- ========================================== --}}
                                                                    @if($pmt->payment_status_id == 2)
                                                                        
                                                                        @if($pmt->insurance_payment_status_id != 2)
                                                                            {{-- KONDISI C: MENUNGGU SETOR KE ASURANSI --}}
                                                                            @if($pmt->id == $activeDetailId)
                                                                                <button type="button" 
                                                                                    class="btn-dark-green btn-trigger-insurance"
                                                                                    data-id="{{ $pmt->id }}"
                                                                                    data-amount="{{ $pmt->insurance_paid_amount ?? $pmt->amount }}">
                                                                                    Bayar Asuransi
                                                                                </button>
                                                                            @endif
                                                                        @else
                                                                            {{-- KONDISI D: SUDAH DISETOR --}}
                                                                            <div class="status-box status-ins-disetor">
                                                                                Asuransi Disetor
                                                                                <span class="date">{{ $pmt->insurance_paid_at ? $pmt->insurance_paid_at->format('d/m/y') : '-' }}</span>
                                                                            </div>
                                                                        @endif

                                                                    @endif

                                                                </div>
                                                            </td>
                                                        @endif
                                                    </tr>
                                                @endforeach

                                                {{-- Row Total per Installment --}}
                                                <tr class="bg-total" style="border-top: 2px solid #000;">
                                                    <td colspan="3"></td> 
                                                    <td class="text-end">{{ numberFormat($subtotalPremi) }}</td>
                                                    <td class="text-end">{{ numberFormat($subtotalBiaya) }}</td>
                                                    <td class="text-end">{{ numberFormat($subtotalKomisi) }}</td>
                                                    <td class="text-end">{{ numberFormat($subtotalPajak) }}</td>
                                                    <td class="text-end">{{ numberFormat($subtotalNetto) }}</td>
                                                    <td class="no-print"></td> {{-- Sel Kosong untuk kolom aksi di row total --}}
                                                </tr>
                                            @endforeach
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

    {{-- MODAL 1: PEMBAYARAN CLIENT --}}
    <div class="modal fade" id="modalClientPay" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('payments.update_client_pay', $payment->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi Pembayaran Nasabah</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Tanggal Bayar <span class="text-danger">*</span></label>
                            <input type="date" name="paid_at" class="form-control" required value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jumlah Dibayar (Rp) <span class="text-danger">*</span></label>
                            {{-- Default value mengambil total tagihan --}}
                            <input type="number" name="paid_amount" class="form-control" required value="{{ $payment->amount }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Simpan & Lunaskan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL 2: PEMBAYARAN ASURANSI --}}
    <div class="modal fade" id="modalInsurancePay" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('payments.update_insurance_pay', $payment->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi Setor ke Asuransi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info py-2 small">
                            <i class="fa fa-info-circle"></i> Mengisi ini akan mengupdate status pembayaran asuransi menjadi Lunas.
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Setor <span class="text-danger">*</span></label>
                            <input type="date" name="insurance_paid_at" class="form-control" required value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jumlah Disetor (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="insurance_paid_amount" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-info text-white">Simpan Setoran</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                // --- 1. DEFINISI VARIABEL & STATUS ---
                // Pastikan totalPremi dibaca sebagai Angka (Float), bukan string error
                const rawAmount = "{{ $payment->amount ?? 0 }}";
                const totalPremi = parseFloat(rawAmount) || 0;

                const currencyCode = "{{ $policy->currency_code }}";
                
                const commissionPercent = {{ $card->commision_percentages ?? 0 }};
                const totalTax = {{ $card->tax ?? 0 }};
                
                // Variabel untuk menghitung persentase pajak per item (jika perlu)
                // Asumsi: Pajak dipukul rata proporsional, atau sesuaikan rumus Anda
                const taxPercent = (totalPremi > 0) ? (totalTax / totalPremi) : 0;

                // Status Lock dari PHP
                const isPaymentLocked = {{ isset($payment) && $payment->payment_status_id == 2 ? 'true' : 'false' }};
                
                // --- 2. INIT HALAMAN ---
                // Jalankan pengecekan awal saat halaman dimuat
                checkPaymentType();

                // --- 3. HANDLER UTAMA ---
                
                // Ganti Tipe Pembayaran
                $('#payment-type-select').on('change', function() {
                    checkPaymentType();
                });

                // Input Jumlah Cicilan (Generate Otomatis)
                $('#installment-count').on('input change', function() {
                    if(isPaymentLocked) return; // Cegah edit jika lunas

                    let count = parseInt($(this).val()) || 0;
                    if (count < 1) count = 1;
                    
                    // Generate ulang item
                    generateInstallmentItems(count);
                });

                // Tombol Tambah Manual
                $(document).on('click', '#add-installment-btn', function() {
                    addInstallmentRow(0); 
                    syncInstallmentState();
                });

                // Tombol Hapus Manual
                $(document).on('click', '.remove-installment-btn', function() {
                    const rowCount = $('#installments-container .installment-row').length;
                    if (rowCount <= 1) {
                        Swal.fire('Minimal 1 cicilan!', '', 'warning');
                        return;
                    }
                    $(this).closest('.installment-row').remove();
                    syncInstallmentState();
                });

                // Hitung Otomatis saat Nilai Rupiah diketik manual
                $(document).on('input', '.installment-amount', function() {
                    const $row = $(this).closest('.installment-row');
                    const amount = parseFloat($(this).val()) || 0;
                    calculateRowValues($row, amount);
                    updateTotalDisplay();
                });

                // --- 4. LOGIC CORE (PERBAIKAN DISINI) ---

                function checkPaymentType() {
                    // Ambil data-name dengan .attr agar lebih konsisten
                    const selectedOption = $('#payment-type-select option:selected');
                    const selectedType = (selectedOption.attr('data-name') || '').toLowerCase();
                    
                    // Cek apakah tipe mengandung kata 'installment' atau 'cicilan'
                    if (selectedType.includes('installment') || selectedType.includes('cicilan')) {
                        $('#installment-section').slideDown();
                        $('#installment-items-section').slideDown();
                        
                        // FIX: Jika container kosong, PAKSA generate otomatis berdasarkan jumlah cicilan
                        if ($('#installments-container').children().length === 0) {
                            let currentCount = parseInt($('#installment-count').val()) || 1;
                            generateInstallmentItems(currentCount);
                        }
                    } else {
                        $('#installment-section').slideUp();
                        $('#installment-items-section').slideUp();
                    }
                }

                function generateInstallmentItems(count) {
                    $('#installments-container').empty();
                    
                    // Hitung pembagian rata
                    // Hati-hati pembagian koma, kita bulatkan 2 desimal atau round
                    let amountPerInstallment = totalPremi / count;
                    
                    // Opsional: Bulatkan ke bawah agar rapi, sisa ditaruh di akhir (logic sederhana dulu)
                    // amountPerInstallment = Math.floor(amountPerInstallment); 

                    for (let i = 0; i < count; i++) {
                        addInstallmentRow(amountPerInstallment);
                    }
                    updateTotalDisplay();
                }

                function addInstallmentRow(amount = 0) {
                    const newIndex = $('#installments-container .installment-row').length + 1;
                    
                    // Hitung due date (+30 hari per index)
                    const today = new Date();
                    today.setDate(today.getDate() + (newIndex * 30));
                    const dueDate = today.toISOString().split('T')[0];

                    // Status Disabled jika Locked
                    const disabledAttr = isPaymentLocked ? 'disabled style="background-color: #eee;"' : '';
                    const removeBtnHtml = '';
                    const colClass = isPaymentLocked ? 'col-md-4' : 'col-md-3';

                    const html = `
                        <div class="installment-row mb-2 p-3 bg-white border rounded shadow-sm">
                            <div class="row align-items-end">
                                <div class="col-md-1 text-center">
                                    <label class="form-label fw-bold installment-label text-primary">Cicilan #${newIndex}</label>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small text-muted">Nilai (Rp)</label>
                                    <input type="number" class="form-control fw-bold installment-amount" 
                                        value="${amount.toFixed(2)}" min="0" ${disabledAttr}>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small text-muted">Jatuh Tempo</label>
                                    <input type="date" class="form-control installment-due-date" value="${dueDate}" ${disabledAttr}>
                                </div>
                                <div class="${colClass}">
                                    <div class="row g-0">
                                        <div class="col-4 px-1">
                                            <label class="small text-muted d-block">Komisi</label>
                                            <span class="installment-commission small">0</span>
                                        </div>
                                        <div class="col-4 px-1">
                                            <label class="small text-muted d-block">Pajak</label>
                                            <span class="installment-tax small">0</span>
                                        </div>
                                        <div class="col-4 px-1">
                                            <label class="small text-muted d-block fw-bold">Netto</label>
                                            <span class="installment-netto small fw-bold text-success">0</span>
                                        </div>
                                    </div>
                                </div>
                                ${removeBtnHtml}
                            </div>
                        </div>
                    `;
                    
                    $('#installments-container').append(html);
                    
                    // Hitung rincian baris ini langsung
                    const $newRow = $('#installments-container .installment-row').last();
                    calculateRowValues($newRow, amount);
                }

                function syncInstallmentState() {
                    const rows = $('#installments-container .installment-row');
                    $('#installment-count').val(rows.length);
                    
                    rows.each(function(index) {
                        $(this).find('.installment-label').text(`Cicilan #${index + 1}`);
                    });
                    updateTotalDisplay();
                }

                function calculateRowValues($row, amount) {
                    const commission = amount * commissionPercent;
                    const tax = amount * taxPercent; 
                    const netto = amount - commission - tax;
                    
                    $row.find('.installment-commission').text(formatNumber(commission));
                    $row.find('.installment-tax').text(formatNumber(tax));
                    $row.find('.installment-netto').text(formatNumber(netto));
                }

                function updateTotalDisplay() {
                    const count = $('#installments-container .installment-row').length;
                    let currentTotal = 0;
                    
                    $('#installments-container .installment-amount').each(function() {
                        currentTotal += parseFloat($(this).val()) || 0;
                    });
                    
                    $('#total-installments-display').text(count);
                    $('#total-amount-display').text('Rp ' + formatNumber(currentTotal));

                    // Optional: Kasih warning visual jika total tidak match dengan Tagihan
                    // Gunakan toleransi kecil (epsilon) untuk komparasi float
                    if (Math.abs(currentTotal - totalPremi) > 100) { 
                        $('#total-amount-display').addClass('text-danger').removeClass('text-dark');
                    } else {
                        $('#total-amount-display').removeClass('text-danger').addClass('text-dark');
                    }
                }

                function formatNumber(num) {
                    let formatted = new Intl.NumberFormat('id-ID', { 
                        minimumFractionDigits: 0, 
                        maximumFractionDigits: 2 
                    }).format(num);

                    // Tambahkan Currency Code di depan
                    return currencyCode + ' ' + formatted;
                }

                // --- 5. AJAX SIMPAN ---
                $('#apply-installments-btn').on('click', function() {
                    const $btn = $(this);
                    const originalText = $btn.html();
                    
                    const rows = $('#installments-container .installment-row');
                    if (rows.length === 0) {
                        Swal.fire('Error', 'Tidak ada cicilan untuk disimpan!', 'error');
                        return;
                    }

                    Swal.fire({
                        title: 'Simpan Perubahan?',
                        html: `Ini akan mengupdate pembayaran cicilan.<br><strong>Total: ${rows.length} cicilan</strong>`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Simpan!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');

                            const installments = [];
                            rows.each(function() {
                                installments.push({
                                    amount: parseFloat($(this).find('.installment-amount').val()) || 0,
                                    due_date: $(this).find('.installment-due-date').val()
                                });
                            });

                            const paymentTypeId = $('#payment-type-select').val();

                            $.ajax({
                                url: '{{ route("payments.updateInstallments", $payment->id) }}',
                                method: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    payment_type_id: paymentTypeId,
                                    installments: installments
                                },
                                success: function(response) {
                                    if (response.success) {
                                        Swal.fire('Berhasil!', response.message, 'success').then(() => {
                                            window.location.href = '{{ route("payments.index") }}';
                                        });
                                    } else {
                                        throw new Error(response.message || 'Gagal menyimpan');
                                    }
                                },
                                error: function(xhr) {
                                    let errorMsg = xhr.responseJSON?.message || 'Terjadi kesalahan saat menyimpan';
                                    Swal.fire('Gagal!', errorMsg, 'error');
                                },
                                complete: function() {
                                    $btn.prop('disabled', false).html(originalText);
                                }
                            });
                        }
                    });
                });

                // Handler Modal Client & Insurance (Biarkan tetap ada)
                $(document).on('click', '.btn-trigger-client', function() {
                    let paymentId = $(this).data('id');
                    let amount = $(this).data('amount');
                    let url = "{{ route('payments.update_client_pay', ':id') }}".replace(':id', paymentId);
                    $('#modalClientPay form').attr('action', url);
                    $('#modalClientPay input[name="paid_amount"]').val(amount);
                    $('#modalClientPay').modal('show');
                });

                $(document).on('click', '.btn-trigger-insurance', function() {
                    let paymentId = $(this).data('id');
                    let amount = $(this).data('amount');
                    let url = "{{ route('payments.update_insurance_pay', ':id') }}".replace(':id', paymentId);
                    $('#modalInsurancePay form').attr('action', url);
                    $('#modalInsurancePay input[name="insurance_paid_amount"]').val(amount);
                    $('#modalInsurancePay').modal('show');
                });
            });

            function printPremi() {
                printTableOnly('master-table-section', 'PERHITUNGAN PEMBAYARAN PREMI');
            }

            // --- FUNCTION PRINT 2: JADWAL INSTALLMENT ---
            function printInstallment() {
                printTableOnly('installment-table-section', 'JADWAL ANGSURAN PEMBAYARAN');
            }

            // Function Print Laporan
            function printTableOnly(elementId, title) {
                var content = document.getElementById(elementId);
                
                if (!content) {
                    Swal.fire('Error', 'Tabel tidak ditemukan / Belum digenerate', 'error');
                    return;
                }

                var objectName = "{{ strtoupper($policy->object->name ?? '-') }}";
                var policyNo = "{{ $policy->policy_number ?? '-' }}";
                var clientName = "{{ $client->user->name ?? $client->id_name ?? '-' }}";
                
                var win = window.open('', '', 'height=700,width=900');
                
                win.document.write('<html><head><title>Print Laporan</title>');
                win.document.write('<style>');
                win.document.write('body{font-family:Arial,sans-serif;padding:20px}');
                win.document.write('.table-manual{width:100%;border-collapse:collapse;font-size:11px;margin-bottom:10px}');
                win.document.write('.table-manual th,.table-manual td{border:1px solid #000;padding:4px;vertical-align:middle}');
                win.document.write('.table-manual thead th{background-color:#d1d1d1!important;font-weight:700;text-align:center}');
                win.document.write('.bg-total{background-color:#e9ecef!important;font-weight:700}');
                win.document.write('.text-end{text-align:right}.text-center{text-align:center}.fw-bold{font-weight:700}');
                win.document.write('.no-print{display:none!important}'); // Hide tombol aksi pas print
                win.document.write('</style>');
                win.document.write('</head><body>');
                
                // Header Standar
                win.document.write('<div style="text-align:center;margin-bottom:20px">');
                win.document.write('<h3 style="margin:0">'+ title +'</h3>');
                win.document.write('<p style="margin:5px 0;font-weight:700">'+objectName+'</p>');
                win.document.write('</div>');
                
                win.document.write('<table style="width:100%;font-size:11px;margin-bottom:10px;border:none">');
                win.document.write('<tr><td style="border:none"><b>NO POLIS:</b> '+policyNo+'</td><td style="border:none;text-align:right"><b>Client:</b> '+clientName+'</td></tr>');
                win.document.write('</table>');

                // Isi Tabel Pilihan
                win.document.write(content.innerHTML); // Pakai innerHTML biar wrapper div-nya ga keikut double
                
                win.document.write('</body></html>');
                win.document.close();
                win.focus();
                setTimeout(function(){win.print();win.close();},500);
            }
        </script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endpush
@endsection