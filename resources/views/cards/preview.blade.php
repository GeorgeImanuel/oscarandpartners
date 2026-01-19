@extends('layouts.master')

@section('title')Preview Kartu Produksi
@endsection

@push('css')
<style>
    .production-card-detail {
        font-family: 'Times New Roman', serif;
        max-width: 900px;
        margin: 0 auto;
        background: white;
        padding: 30px;
        color: #000;
    }
    .production-card-header {
        text-align: center;
        margin-bottom: 30px;
        padding-bottom: 0px;
    }
    .production-card-header h2 {
		text-align: left;
        font-size: 32px;
        font-weight: bold;
        margin: 0;
        font-style: italic;
    }
    .production-card-header p {
		text-align: left;
        font-size: 14px;
        margin: 5px 0 15px 0;
        font-style: italic;
    }
    .production-card-title {
        border: 2px solid #000;
        padding: 8px;
        font-weight: bold;
        font-size: 16px;
        text-align: center;
        margin: 20px auto;
        width: 250px;
    }
    .info-row {
        display: flex;
        margin-bottom: 8px;
        font-size: 14px;
    }
    .info-label {
        width: 200px;
        font-weight: normal;
    }
    .info-separator {
        width: 20px;
        text-align: center;
    }
    .info-value {
        flex: 1;
        font-weight: normal;
    }
    .section-number {
        display: inline-block;
        width: 25px;
        font-weight: bold;
    }
    .table-custom {
        width: 100%;
        border-collapse: collapse;
        margin: 15px 0;
        font-size: 13px;
    }
    .table-custom th, .table-custom td {
        border: 1px solid #000;
        padding: 6px 8px;
        text-align: center;
    }
    .table-custom th {
        background-color: #f0f0f0;
        font-weight: bold;
    }
    .payment-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 5px;
        font-size: 13px;
    }
    .signature-boxes {
        display: flex;
        justify-content: space-between;
        margin-top: 30px;
        gap: 15px;
    }
    .signature-box {
        border: 1px solid #000;
        padding: 10px;
        flex: 1;
        min-height: 120px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .signature-box p {
        margin: 0 0 5px 0;
        font-size: 12px;
    }
    
    @media print {
        @page { size: A4; margin: 5mm; }
        body { margin: 0; padding: 0; -webkit-print-color-adjust: exact; }
        .no-print { display: none !important; }
        .page-wrapper, .page-header, .sidebar-wrapper, footer { display: none !important; }
        .production-card-detail { 
            display: block !important; 
            width: 100%; 
            max-width: none; 
            padding: 0;
            border: none;
        }
        .container-fluid, .row, .col-sm-12, .card, .card-body {
            display: block !important;
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
            border: none !important;
        }
    }
</style>
@endpush

@php
    $currency = $productionCard->policy->currency_code ?? 'Rp';
@endphp

@section('content')
    <div class="no-print">
        @component('components.breadcrumb')
            @slot('breadcrumb_title')
                <h3>Preview Kartu Produksi</h3>
            @endslot
            <li class="breadcrumb-item">Kartu Produksi</li>
            <li class="breadcrumb-item active">Preview</li>
        @endcomponent
        
        <div class="container-fluid mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('cards.show', $productionCard->id) }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left"></i> Kembali ke Detail
                        </a>
                        <button onclick="printCard()" class="btn btn-info btn-sm">
                            <i class="fa fa-print"></i> Print (A4)
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="production-card-detail">
        <div class="production-card-header">
            <h2>Oscar & Partners</h2>
            <p>Professional Insurance Consultants</p>
			<hr style="border: 0; border-top: 2px solid #000; opacity: 1; margin: 10px 0 15px 0;">
            <div style="display: flex; justify-content: space-between; font-size: 12px; margin-top: 10px;">
                <span>REG. NO: {{ str_pad($productionCard->id, 4, '0', STR_PAD_LEFT) }}</span>
                <span>BLN: {{ $productionCard->created_at ? $productionCard->created_at->format('m/Y') : '-' }}</span>
                <span>{{ strtoupper($productionCard->cardType->name) }}</span>
            </div>
        </div>

        <div class="production-card-title">KARTU PRODUKSI</div>

        <div style="margin: 20px 0;">
            <div class="info-row">
                <span class="section-number">1</span>
                <span class="info-label">No. Polis/ Asuransi</span>
                <span class="info-separator">:</span>
                <span class="info-value">{{ $productionCard->policy->policy_number ?? '-' }}</span>
                <span style="width: 100px; text-align: right;">Asuransi :</span>
                <span style="margin-left: 10px; width: 200px;">
                    {{ $productionCard->policy->policyInsurances->firstWhere('is_main', true)?->insurance->name ?? '-' }}
                </span>
            </div>
            
            <div class="info-row">
                <span class="section-number">2</span>
                <span class="info-label">Nama Tertanggung</span>
                <span class="info-separator">:</span>
                <span class="info-value">{{ $productionCard->client->id_name ?? '-' }}</span>
                @if($productionCard->client && $productionCard->client->relation)
                    <span style="width: 100px; text-align: right;">Kode Relasi :</span>
                    <span style="margin-left: 10px; width: 200px;">{{ $productionCard->client->relation->code }}</span>
                @endif
            </div>

            <div class="info-row">
                <span class="section-number">3</span>
                <span class="info-label">Obyek Asuransi</span>
                <span class="info-separator">:</span>
                <span class="info-value">{{ $productionCard->policy->object->ObjectOccupation->name ?? '-' }} - {{ $productionCard->policy->object->keterangan ?? '-' }}</span>
            </div>

            <div class="info-row">
                <span class="section-number">4</span>
                <span class="info-label">Periode Pertanggungan</span>
                <span class="info-separator">:</span>
                <span class="info-value">
                    {{ $productionCard->policy->started_date ? $productionCard->policy->started_date->format('d/m/Y') : '-' }} -  
                    {{ $productionCard->policy->ended_date ? $productionCard->policy->ended_date->format('d/m/Y') : '-' }}
                </span>
            </div>

            <div class="info-row" style="align-items: flex-start;">
                {{-- BAGIAN KIRI: Harga Pertanggungan --}}
                <span class="section-number">5</span>
                <span class="info-label">Harga Pertanggungan</span>
                <span class="info-separator">:</span>
                <span class="info-value">{{ $currency }} {{ number_format($productionCard->object_value, $currency == 'Rp' ? 0 : 2, ',', '.') }}</span>

                {{-- BAGIAN KANAN: Tarif, TJH, Disc --}}
                {{-- margin-left: auto mendorong ke kanan, width: auto agar pas dengan konten --}}
                <div style="margin-left: auto; display: flex; flex-direction: column; align-items: flex-end;">
                    
                    {{-- Baris 1: Tarif Premi --}}
                    <div style="display: flex; width: 320px; margin-bottom: 2px;">
                        {{-- Spacer agar mepet kanan --}}
                        <span style="flex: 1;"></span>
                        {{-- Label: Lebar disamakan dengan label 'Asuransi' diatas --}}
                        <span style="width: 100px; text-align: right;">Tarif Premi</span>
                        <span style="width: 20px; text-align: center;">:</span>
                        {{-- Value --}}
                        <span style="width: 200px; text-align: left;">
                            {{ number_format($productionCard->premi_percentages * 100, 5, ',', '.') }}%
                        </span>
                    </div>

                    {{-- Baris 2: TJH --}}
                    <div style="display: flex; width: 320px; margin-bottom: 2px;">
                        <span style="flex: 1;"></span>
                        <span style="width: 100px; text-align: right;">TJH</span>
                        <span style="width: 20px; text-align: center;">:</span>
                        <span style="width: 200px; text-align: left;">
                            {{ $currency }} {{ number_format($productionCard->tjh_value,  $currency == 'Rp' ? 0 : 2, ',', '.') }}
                        </span>
                    </div>

                    {{-- Baris 3: Disc --}}
                    <div style="display: flex; width: 320px;">
                        <span style="flex: 1;"></span>
                        <span style="width: 100px; text-align: right;">Disc</span>
                        <span style="width: 20px; text-align: center;">:</span>
                        <span style="width: 200px; text-align: left;">
                            {{ $currency }} {{ number_format($productionCard->discount_value,  $currency == 'Rp' ? 0 : 2, ',', '.') }}
                        </span>
                    </div>

                </div>
            </div>
        </div>

        <div style="margin: 25px 0;">
            <div class="info-row">
                <span class="section-number">6</span>
                <span class="info-label" style="font-weight: bold;">Premi</span>
            </div>
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Nilai Produksi</th>
                        <th>Biaya Polis</th>
                        <th>Total Tagihan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $currency }} {{ number_format($productionCard->production_value,  $currency == 'Rp' ? 0 : 2, ',', '.') }}</td>
                        <td>{{ $currency }} {{ number_format($productionCard->policy_fee,  $currency == 'Rp' ? 0 : 2, ',', '.') }}</td>
                        <td>{{ $currency }} {{ number_format($productionCard->client_pay,  $currency == 'Rp' ? 0 : 2, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div style="margin: 25px 0;">
            <div class="info-row">
                <span class="section-number">7</span>
                <span class="info-label" style="font-weight: bold;">Komisi</span>
            </div>
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Total</th>
                        <th>Pajak</th>
                        <th>Disc/Agen</th>
                        <th>Komisi Bersih</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $currency }} {{ number_format($productionCard->commision_bruto,  $currency == 'Rp' ? 0 : 2, ',', '.') }}</td>
                        <td>{{ $currency }} {{ number_format($productionCard->tax,  $currency == 'Rp' ? 0 : 2, ',', '.') }}</td>
                        <td>{{ $currency }} {{ number_format($productionCard->discount_agent,  $currency == 'Rp' ? 0 : 2, ',', '.') }}</td>
                        <td>{{ $currency }} {{ number_format($productionCard->commision_netto,  $currency == 'Rp' ? 0 : 2, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="info-row">
			<span class="section-number">8</span>
			<span class="info-label" style="font-weight: bold;">Keterangan Pembayaran</span>
		</div>
		<div style="display: flex; gap: 20px; margin-top: 20px;">
            <div style="flex: 1; border: 1px solid #000; padding: 10px;">
                <h6 style="margin: 0 0 10px 0; font-weight: bold; text-decoration: underline;">Tagihan ke Nasabah</h6>
                <div class="payment-item">
                    <span>Premi Total</span>
                    <span>{{ $currency }} {{ number_format($productionCard->premi_value,  $currency == 'Rp' ? 0 : 2, ',', '.') }}</span>
                </div>
                <div class="payment-item">
                    <span>Discount/Agen</span>
                    <span>{{ $currency }} {{ number_format($productionCard->discount_agent,  $currency == 'Rp' ? 0 : 2, ',', '.') }}</span>
                </div>
                <div class="payment-item" style="border-top: 1px solid #ccc; margin-top: 5px; padding-top: 5px; font-weight: bold;">
                    <span>Netto Tagihan</span>
                    <span>{{ $currency }} {{ number_format(($productionCard->premi_value - $productionCard->discount_agent),  $currency == 'Rp' ? 0 : 2, ',', '.') }}</span>
                </div>
            </div>

            <div style="flex: 1; border: 1px solid #000; padding: 10px;">
                <h6 style="margin: 0 0 10px 0; font-weight: bold; text-decoration: underline;">Setor ke Asuransi</h6>
                <div class="payment-item">
                    <span>Premi Total</span>
                    <span>{{ $currency }} {{ number_format($productionCard->premi_value,  $currency == 'Rp' ? 0 : 2, ',', '.') }}</span>
                </div>
                <div class="payment-item">
                    <span>Komisi</span>
                    <span>{{ $currency }} {{ number_format($productionCard->commision_netto,  $currency == 'Rp' ? 0 : 2, ',', '.') }}</span>
                </div>
                <div class="payment-item" style="border-top: 1px solid #ccc; margin-top: 5px; padding-top: 5px; font-weight: bold;">
                    <span>Netto Bayar</span>
                    <span>{{ $currency }} {{ number_format($productionCard->insurance_pay,  $currency == 'Rp' ? 0 : 2, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="signature-boxes">
            <div class="signature-box" style="flex: 2;">
                <div>
                    <p>Semarang, {{ now()->format('d/m/Y') }}</p>
                </div>
                <div style="display: flex; justify-content: space-between; margin-top: 40px; padding: 0 10px;">
                    <p>( Bag. Ad )</p>
                    <p>( Bag Keu )</p>
                    <p>( Pimpinan )</p>
                </div>
            </div>

            <div class="signature-box">
                <div>
                    <p style="font-weight: bold;">Pelunasan Tagihan</p>
                    <p>Tgl : {{ $productionCard->insurance_paid_at ? $productionCard->insurance_paid_at->format('d/m/Y') : '' }}</p>
                    <p>No.bukti : {{ $productionCard->insurance_evident_number ?? '' }}</p>
                </div>
            </div>

            <div class="signature-box">
                <div>
                    <p style="font-weight: bold;">Lunas Komisi / Ass</p>
                    <p>Tgl : </p>
                    <p>No.bukti : </p>
                </div>
            </div>
        </div>

        <div style="margin-top: 15px; border-top: 1px solid #ccc; padding-top: 10px;">
            <p style="margin: 0; font-size: 13px;"><strong>Note :</strong></p>
        </div>
    </div>
@endsection

@push('scripts')
<script>
function printCard() {
    var content = document.querySelector('.production-card-detail').innerHTML;
    
    var styles = '';
    var styleTags = document.querySelectorAll('style');
    styleTags.forEach(function(tag) {
        styles += tag.outerHTML;
    });

    var printWindow = window.open('', '', 'height=600,width=800');
    
    printWindow.document.write('<html><head><title>Print Kartu Produksi</title>');
    printWindow.document.write(styles);
    printWindow.document.write('</head><body >');
    printWindow.document.write('<div class="production-card-detail" style="margin:0; padding:0; border:none;">');
    printWindow.document.write(content);
    printWindow.document.write('</div>');
    printWindow.document.write('</body></html>');
    
    printWindow.document.close();
    printWindow.focus();
    
    setTimeout(function() {
        printWindow.print();
        printWindow.close();
    }, 500);
}
</script>
@endpush