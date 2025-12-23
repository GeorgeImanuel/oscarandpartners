@extends('layouts.master')

@section('title')Detail Kartu Produksi
@endsection

@push('css')
<style>
	.production-card-detail {
		font-family: 'Times New Roman', serif;
		max-width: 900px;
		margin: 0 auto;
		background: white;
		padding: 30px;
	}
	.production-card-header {
		text-align: center;
		margin-bottom: 30px;
		border-bottom: 2px solid #000;
		padding-bottom: 20px;
	}
	.production-card-header h2 {
		font-size: 32px;
		font-weight: bold;
		margin: 0;
		font-style: italic;
	}
	.production-card-header p {
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
	.table-premi {
		width: 100%;
		border-collapse: collapse;
		margin: 15px 0;
	}
	.table-premi th, .table-premi td {
		border: 1px solid #000;
		padding: 8px;
		text-align: center;
	}
	.table-premi th {
		background-color: #f0f0f0;
		font-weight: bold;
	}
	.table-komisi {
		width: 100%;
		border-collapse: collapse;
		margin: 15px 0;
	}
	.table-komisi th, .table-komisi td {
		border: 1px solid #000;
		padding: 8px;
		text-align: center;
	}
	.table-komisi th {
		background-color: #f0f0f0;
		font-weight: bold;
	}
	.payment-section {
		margin-top: 20px;
	}
	.payment-box {
		margin-bottom: 15px;
	}
	.payment-box h6 {
		font-weight: bold;
		margin-bottom: 10px;
	}
	.payment-item {
		display: flex;
		justify-content: space-between;
		margin-bottom: 5px;
		padding-left: 20px;
	}
	.signature-boxes {
		display: flex;
		justify-content: space-between;
		margin-top: 30px;
		gap: 15px;
	}
	.signature-box {
		border: 1px solid #000;
		padding: 15px;
		flex: 1;
		min-height: 100px;
	}
	.signature-box:first-child {
		flex: 2.5;
	}
	.signature-box p {
		margin: 5px 0;
		font-size: 13px;
	}
	.no-print {
		margin-bottom: 20px;
	}
	@page {
		size: auto;
		margin: 0mm;
	}
	
	@media print {
		/* Hide header/footer browser default */
		html, body {
			margin: 0 !important;
			padding: 0 !important;
		}
		
		/* Hide SEMUA elemen layout */
		body > *:not(.page-wrapper),
		.page-header,
		.page-sidebar,
		.sidebar-wrapper,
		.sidebar,
		.page-main-header,
		.breadcrumb-section,
		.no-print,
		nav,
		header,
		footer,
		.footer,
		.tap-top,
		.bg-overlay1,
		#sidebar-menu,
		.iconsidebar-menu,
		.mega-menu-container,
		.left-header,
		.right-header,
		.notification-dropdown,
		.profile-dropdown,
		.mobile-sidebar,
		.logo-wrapper,
		.toggle-sidebar,
		.breadcrumb,
		[class*="sidebar"],
		[class*="header"],
		[class*="footer"],
		[class*="nav-"] {
			display: none !important;
			visibility: hidden !important;
			opacity: 0 !important;
			height: 0 !important;
			width: 0 !important;
			overflow: hidden !important;
		}
		
		/* Reset body dan wrapper */
		body {
			margin: 0 !important;
			padding: 0 !important;
		}
		
		.page-wrapper {
			margin: 0 !important;
			padding: 0 !important;
			width: 100% !important;
		}
		
		.page-body-wrapper {
			margin: 0 !important;
			padding: 0 !important;
			width: 100% !important;
			margin-left: 0 !important;
		}
		
		.page-body {
			margin: 0 !important;
			padding: 0 !important;
			width: 100% !important;
		}
		
		.container-fluid {
			margin: 0 !important;
			padding: 0 !important;
			width: 100% !important;
			max-width: 100% !important;
		}
		
		.row {
			margin: 0 !important;
		}
		
		.col-sm-12 {
			padding: 0 !important;
		}
		
		.card {
			border: none !important;
			box-shadow: none !important;
			margin: 0 !important;
		}
		
		.card-body {
			padding: 0 !important;
		}
		
		.production-card-detail {
			padding: 20px !important;
			max-width: 100% !important;
		}
		
		/* Prevent page break inside important elements */
		.production-card-header,
		.production-card-title,
		.table-premi,
		.table-komisi,
		.signature-boxes {
			page-break-inside: avoid;
		}
	}
</style>
@endpush

@section('content')
	@component('components.breadcrumb')
		@slot('breadcrumb_title')
			<h3>Detail Kartu Produksi</h3>
		@endslot
		<li class="breadcrumb-item">Kartu Produksi</li>
		<li class="breadcrumb-item active">Detail</li>
	@endcomponent
	
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="card">
					<div class="card-body">
						<!-- Action Buttons -->
						<div class="no-print mb-3">
							<div class="d-flex justify-content-between align-items-center">
								<a href="{{ route('production-cards.index') }}" class="btn btn-secondary btn-sm">
									<i class="fa fa-arrow-left"></i> Kembali
								</a>
								<div>
									<a href="{{ route('production-cards.edit', $productionCard->id) }}" class="btn btn-warning btn-sm">
										<i class="fa fa-edit"></i> Edit
									</a>
									<button onclick="window.print()" class="btn btn-info btn-sm">
										<i class="fa fa-print"></i> Print
									</button>
								</div>
							</div>
						</div>

						<!-- Production Card Detail -->
						<div class="production-card-detail">
							<!-- Header -->
							<div class="production-card-header">
								<h2>Oscar & Partners</h2>
								<p>Professional Insurance Consultants</p>
								<div style="display: flex; justify-content: space-between; font-size: 13px;">
									<span>REG. NO: {{ str_pad($productionCard->id, 4, '0', STR_PAD_LEFT) }}</span>
									<span>BLN: {{ $productionCard->start_date ? $productionCard->start_date->format('m/Y') : '-' }}</span>
									<span>
										@if($productionCard->cardStatus)
											{{ strtoupper($productionCard->cardStatus->name) }}
										@else
											NEW
										@endif
									</span>
								</div>
							</div>

							<!-- Title -->
							<div class="production-card-title">
								KARTU PRODUKSI
							</div>

							<!-- Section 1-3: Info Polis & Tertanggung -->
							<div style="margin: 20px 0;">
								<div class="info-row">
									<span class="section-number">1</span>
									<span class="info-label">No. Polis/ Asuransi</span>
									<span class="info-separator">:</span>
									<span class="info-value">{{ $productionCard->policy->policy_number ?? '-' }}</span>
									<span style="width: 150px; text-align: right;">Asuransi</span>
									<span class="info-separator">:</span>
									<span class="info-value">{{ $productionCard->policy->insurance->name ?? '-' }}</span>
								</div>
								<div class="info-row">
									<span class="section-number">2</span>
									<span class="info-label">Nama Tertanggung</span>
									<span class="info-separator">:</span>
									<span class="info-value">{{ $productionCard->client->name ?? '-' }}</span>
								</div>
								<div class="info-row">
									<span class="section-number">3</span>
									<span class="info-label">Obyek Asuransi</span>
									<span class="info-separator">:</span>
									<span class="info-value">{{ $productionCard->policy->object ?? '-' }}</span>
								</div>
							</div>

							<!-- Section 4-5: Periode & Harga -->
							<div style="margin: 20px 0;">
								<div class="info-row">
									<span class="section-number">4</span>
									<span class="info-label">Periode Pertanggungan</span>
									<span class="info-separator">:</span>
									<span class="info-value">
										{{ $productionCard->start_date ? $productionCard->start_date->format('d/m/Y') : '-' }} 
										s/d 
										{{ $productionCard->end_date ? $productionCard->end_date->format('d/m/Y') : '-' }}
									</span>
								</div>
								<div class="info-row">
									<span class="section-number">5</span>
									<span class="info-label">Harga Pertanggungan</span>
									<span class="info-separator">:</span>
									<span class="info-value">Rp {{ number_format($productionCard->premium_gross, 0, ',', '.') }}</span>
									<span style="width: 150px; text-align: right;">Tarif Premi</span>
									<span class="info-separator">:</span>
									<span class="info-value">{{ number_format($productionCard->commission_pct, 3) }}%</span>
								</div>
							</div>

							<!-- Section 6: Tabel Premi -->
							<div style="margin: 25px 0;">
								<div class="info-row">
									<span class="section-number">6</span>
									<span class="info-label" style="font-weight: bold;">Premi</span>
								</div>
								<table class="table-premi">
									<thead>
										<tr>
											<th>Produksi</th>
											<th>Biaya Polis</th>
											<th>Total</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>Rp {{ number_format($productionCard->nilai_produksi, 0, ',', '.') }}</td>
											<td>Rp {{ number_format($productionCard->admin_fee, 0, ',', '.') }}</td>
											<td>Rp {{ number_format($productionCard->total_premi, 0, ',', '.') }}</td>
										</tr>
									</tbody>
								</table>
							</div>

							<!-- Section 7: Tabel Komisi -->
							<div style="margin: 25px 0;">
								<div class="info-row">
									<span class="section-number">7</span>
									<span class="info-label" style="font-weight: bold;">Komisi</span>
								</div>
								<table class="table-komisi">
									<thead>
										<tr>
											<th>Total</th>
											<th>Pajak</th>
											<th>Agen</th>
											<th>Komisi Bersih</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>Rp {{ number_format($productionCard->komisi_bruto, 0, ',', '.') }}</td>
											<td>Rp {{ number_format($productionCard->nilai_pajak, 0, ',', '.') }}</td>
											<td>Rp {{ number_format($productionCard->komisi_bersih_awal, 0, ',', '.') }}</td>
											<td>Rp {{ number_format($productionCard->sisa_komisi_agen, 0, ',', '.') }}</td>
										</tr>
									</tbody>
								</table>
							</div>

							<!-- Section 8: Keterangan Pembayaran -->
							<div class="payment-section">
								<div class="info-row">
									<span class="section-number">8</span>
									<span class="info-label" style="font-weight: bold;">Keterangan Pembayaran</span>
									<span class="info-separator">:</span>
								</div>

								<div class="row">
									<div class="col-md-6">
										<div class="payment-box">
											<h6>Tagihan ke Nasabah</h6>
											<div class="payment-item">
												<span>- Premi Total</span>
												<span>= Rp {{ number_format($productionCard->total_premi, 0, ',', '.') }}</span>
											</div>
										<div class="payment-item" style="border-bottom: 1px solid #000; padding-bottom: 5px;">
											<span>- Discount/Agen</span>
											<span>= Rp {{ number_format($productionCard->discount ?? 0, 0, ',', '.') }}</span>
										</div>
										<div class="payment-item" style="padding-top: 5px;">
												<span>- Netto Tagihan</span>
												<span>= Rp {{ number_format($productionCard->tagihan_ke_nasabah, 0, ',', '.') }}</span>
											</div>
											<div class="payment-item" style="margin-top: 10px;">
												<span style="font-weight: bold;">Rencana Bayar</span>
												<span></span>
											</div>
										</div>
									</div>

									<div class="col-md-6">
										<div class="payment-box">
											<h6>Pembayaran ke Asuransi</h6>
											<div class="payment-item">
												<span>- Premi Total</span>
												<span>= Rp {{ number_format($productionCard->total_premi, 0, ',', '.') }}</span>
											</div>
										<div class="payment-item" style="border-bottom: 1px solid #000; padding-bottom: 5px;">
											<span>- Komisi</span>
											<span>= Rp {{ number_format($productionCard->komisi_bersih_awal, 0, ',', '.') }}</span>
										</div>
										<div class="payment-item" style="padding-top: 5px;">
												<span>- Netto bayar</span>
												<span>= Rp {{ number_format($productionCard->netto_bayar_asuransi, 0, ',', '.') }}</span>
											</div>
											<div class="payment-item" style="margin-top: 10px;">
												<span style="font-weight: bold;">Rencana Bayar</span>
												<span></span>
											</div>
										</div>
									</div>
								</div>
							</div>

							<!-- Signature Boxes -->
							<div class="signature-boxes">
								<div class="signature-box">
									<p>Semarang,</p>
									<div style="display: flex; justify-content: flex-start; gap: 80px; margin-top: 60px;">
										<p style="margin: 0;">( Bag. Ad )</p>
										<p style="margin: 0;">( Bag Keu )</p>
										<p style="margin: 0;">( Pimpinan )</p>
									</div>
								</div>

								<div class="signature-box">
									<p><strong>Pelunasan Tagihan</strong></p>
									<p>Tgl : {{ $productionCard->insurance_paid_at ? $productionCard->insurance_paid_at->format('d/m/Y') : '' }}</p>
									<p>No.bukti : {{ $productionCard->insurance_evident_number ?? '' }}</p>
								</div>

								<div class="signature-box">
									<p><strong>Lunas Komisi / Ass</strong></p>
									<p>Tgl : </p>
									<p>No.bukti : </p>
								</div>
							</div>

							<!-- Note Section -->
							<div style="margin-top: 20px; border-top: 1px solid #ccc; padding-top: 10px;">
								<p style="margin: 0; font-size: 13px;"><strong>Note :</strong></p>
								<p style="margin: 5px 0 0 20px; font-size: 13px;">
									Status Pembayaran: 
									@if($productionCard->insurancePaymentStatus)
										<span class="badge" style="background-color: {{ $productionCard->insurancePaymentStatus->color }}">
											{{ $productionCard->insurancePaymentStatus->name }}
										</span>
									@else
										-
									@endif
									&nbsp;|&nbsp;
									Tipe Pembayaran: {{ $productionCard->paymentType->name ?? '-' }}
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

@endsection
