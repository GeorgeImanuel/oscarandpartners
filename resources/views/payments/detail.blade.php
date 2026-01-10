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
                        <div class="d-flex justify-content-between align-items-center">
                            <h5>Perhitungan Installment Premi</h5>
                            <div class="d-flex gap-2">
                                <button onclick="printTableOnly()" class="btn btn-primary btn-sm">
                                    <i class="fa fa-print"></i> Print Laporan
                                </button>
                                <a href="{{ route('payments.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="fa fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @php
                            $card = $payment->productionCard;
                            $policy = $card->policy;
                            $client = $card->client;
                        @endphp

                        {{-- Info Header Laporan --}}
                        <div class="mb-4">
                            <h6 class="fw-bold mb-2 text-center">PERHITUNGAN INSTALLMENT PREMI {{ strtoupper($policy->object->name ?? '') }}</h6>
                            <div style="font-size: 11px; text-align: center;">
                                <strong>NO POLIS :</strong> {{ $policy->policy_number ?? '-' }} / 
                                {{ $policy->started_date ? $policy->started_date->format('d M Y') : '-' }} - 
                                {{ $policy->ended_date ? $policy->ended_date->format('Y') : '-' }}
                            </div>
                        </div>

                        <div class="table-responsive-custom">
                            <table class="table-manual" id="paymentTable">
                                <thead>
                                    {{-- HEADER ROW 1 --}}
                                    <tr>
                                        {{-- Kolom 1 & 2 (Kosong untuk Tanggal & Angsuran) --}}
                                        <th rowspan="2" style="width: 10%;"></th>
                                        
                                        {{-- Header Ko. Asuransi (Menaungi Share & Premi) --}}
                                        <th colspan="3" style="border-bottom: none;">Ko. Asuransi</th>
                                        
                                        <th rowspan="2" style="width: 10%;">Biaya<br>Polis</th>
                                        <th rowspan="2" style="width: 10%;">Komisi</th>
                                        <th rowspan="2" style="width: 10%;">Pajak</th>
                                        <th rowspan="2" style="width: 12%;">Premi<br>Netto</th>
                                    </tr>
                                    {{-- HEADER ROW 2 --}}
                                    <tr>
                                        {{-- Share dibagi 2: Nama Asuransi & Persen --}}
                                        <th colspan="2" style="width: 25%;">Share</th>
                                        <th style="width: 15%;">Premi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        // Variabel Grand Total
                                        $grandTotalPremi = 0;
                                        $grandTotalBiaya = 0;
                                        $grandTotalKomisi = 0;
                                        $grandTotalPajak = 0;
                                        $grandTotalNetto = 0;
                                    @endphp

                                    @foreach($allPayments as $index => $pmt)
                                        @php
                                            $policyInsurances = $pmt->productionCard->policy->policyInsurances;
                                            $insuranceCount = $policyInsurances->count();
                                            // Rowspan = Jumlah Asuransi (tanpa baris tambahan header/footer di dalam loop)
                                            $rowSpanCount = $insuranceCount;
                                            
                                            // Reset Subtotal per Angsuran
                                            $subtotalPremi = 0;
                                            $subtotalBiaya = 0;
                                            $subtotalKomisi = 0;
                                            $subtotalPajak = 0;
                                            $subtotalNetto = 0;
                                        @endphp

                                        {{-- Loop Rincian Asuransi --}}
                                        @foreach($policyInsurances as $loopIndex => $pi)
                                            @php
                                                $share = $pi->percentage;
                                                $premi = $pmt->amount * ($share / 100);

                                                $taxPercent = $card->tax_percentages / 100;
                                                $commPercent = $card->commision_percentages / 100;

                                                $biayaPolis = 0;

                                                if ($index == 0 && $loop->first) {
                                                    // Ambil dari database. Jika null, anggap 0.
                                                    $biayaPolis = $card->policy_fee ?? 0; 
                                                }

                                                $komisi = $premi * $commPercent;
                                                $pajak = $premi * $taxPercent;
                                                
                                                $netto = $premi - $komisi - $pajak + $biayaPolis;

                                                // Akumulasi Subtotal
                                                $subtotalPremi += $premi;
                                                $subtotalBiaya += $biayaPolis;
                                                $subtotalKomisi += $komisi;
                                                $subtotalPajak += $pajak;
                                                $subtotalNetto += $netto;
                                            @endphp
                                            
                                            <tr>
                                                @if($loop->first)
                                                
                                                    {{-- KOLOM 2: ANGSURAN (Hanya di baris pertama angsuran) --}}
                                                    <td rowspan="{{ $rowSpanCount }}" class="text-center" style="vertical-align: top; padding-top: 10px;">
                                                        ANGSURAN {{ $index + 1 }}<br>
                                                        <small>{{ $pmt->deadline_at ? $pmt->deadline_at->format('d/m/Y') : '' }}</small>
                                                    </td>
                                                @endif

                                                {{-- KOLOM 3: NAMA ASURANSI (Bagian 1 dari Share) --}}
                                                <td>{{ $pi->insurance->name }}</td>

                                                {{-- KOLOM 4: PERSENTASE (Bagian 2 dari Share) --}}
                                                <td class="text-center">{{ number_format($share, 2) }}%</td>

                                                {{-- KOLOM 5-9: NILAI --}}
                                                <td class="text-end">{{ numberFormat($premi) }}</td>
                                                @if($loop->first)
                                                    {{-- Hanya cetak <td> di baris pertama, tapi kasih rowspan --}}
                                                    <td rowspan="{{ $rowSpanCount }}" class="text-end" style="vertical-align: top; padding-top: 8px;">
                                                        {{ numberFormat($biayaPolis) }}
                                                    </td>
                                                @endif
                                                <td class="text-end">{{ numberFormat($komisi) }}</td>
                                                <td class="text-end">{{ numberFormat($pajak) }}</td>
                                                <td class="text-end">{{ numberFormat($netto) }}</td>
                                            </tr>
                                        @endforeach

                                        {{-- BARIS SUBTOTAL PER ANGSURAN --}}
                                        <tr class="bg-total" style="border-top: 2px solid #000;">
                                            {{-- Merge 4 kolom pertama untuk tempat kosong --}}
                                            <td colspan="3"></td> 
                                            
                                            {{-- Nilai Subtotal --}}
                                            <td class="text-end">{{ numberFormat($subtotalPremi) }}</td>
                                            <td class="text-end">{{ numberFormat($subtotalBiaya) }}</td>
                                            <td class="text-end">{{ numberFormat($subtotalKomisi) }}</td>
                                            <td class="text-end">{{ numberFormat($subtotalPajak) }}</td>
                                            <td class="text-end">{{ numberFormat($subtotalNetto) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function printTableOnly() {
                // 1. Ambil data Header
                var objectName = "{{ strtoupper($policy->object->name ?? '-') }}";
                var policyNo = "{{ $policy->policy_number ?? '-' }}";
                var clientName = "{{ $client->user->name ?? $client->id_name ?? '-' }}";
                
                // 2. Ambil HTML Tabel
                var tableHTML = document.getElementById('paymentTable').outerHTML;

                // 3. Buat Jendela Baru (Popup)
                var win = window.open('', '', 'height=700,width=900');

                // 4. Tulis HTML Lengkap ke Jendela Baru
                win.document.write('<html><head><title>Print Laporan</title>');
                
                // Inject CSS langsung agar terbawa
                win.document.write('<style>');
                win.document.write('body { font-family: Arial, sans-serif; padding: 20px; }');
                win.document.write('.table-manual { width: 100%; border-collapse: collapse; font-size: 10px; }');
                win.document.write('.table-manual th, .table-manual td { border: 1px solid #000; padding: 4px; vertical-align: middle; }');
                win.document.write('.table-manual thead th { background-color: #d1d1d1 !important; font-weight: bold; text-align: center; -webkit-print-color-adjust: exact; }');
                win.document.write('.bg-total { background-color: #e9ecef !important; font-weight: bold; -webkit-print-color-adjust: exact; }');
                win.document.write('.text-end { text-align: right; }');
                win.document.write('.text-center { text-align: center; }');
                win.document.write('.fw-bold { font-weight: bold; }');
                win.document.write('@page { size: landscape; margin: 10mm; }');
                win.document.write('</style>');
                win.document.write('</head><body>');

                // Header Laporan
                win.document.write('<div style="text-align:center; margin-bottom:20px;">');
                win.document.write('<h3 style="margin:0;">PERHITUNGAN INSTALLMENT PREMI</h3>');
                win.document.write('<p style="margin:5px 0; font-weight:bold;">' + objectName + '</p>');
                win.document.write('</div>');

                // Info Polis & Client
                win.document.write('<table style="width:100%; font-size:11px; margin-bottom:10px; border:none;">');
                win.document.write('<tr><td style="border:none;"><b>NO POLIS:</b> ' + policyNo + '</td>');
                win.document.write('<td style="border:none; text-align:right;"><b>Client:</b> ' + clientName + '</td></tr>');
                win.document.write('</table>');

                // Isi Tabel
                win.document.write(tableHTML);

                win.document.write('</body></html>');

                // 5. Eksekusi Print
                win.document.close();
                win.focus();
                setTimeout(function() {
                    win.print();
                    win.close(); // Otomatis tutup setelah print (opsional)
                }, 500);
            }
        </script>
    @endpush
@endsection