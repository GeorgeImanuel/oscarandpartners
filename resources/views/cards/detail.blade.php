@extends('layouts.master')

@section('title')Detail Kartu Produksi
@endsection

@push('css')
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
					<div class="card-header">
						<h5>Detail Kartu Produksi #{{ str_pad($productionCard->id, 4, '0', STR_PAD_LEFT) }}</h5>
					</div>
					<div class="card-body">
						<!-- Action Buttons -->
						<div class="mb-3">
							<div class="d-flex justify-content-between align-items-center">
								<a href="{{ route('cards.index') }}" class="btn btn-secondary btn-sm">
									<i class="fa fa-arrow-left"></i> Kembali
								</a>
								<div>
								<a href="{{ route('cards.preview', $productionCard->id) }}" class="btn btn-success btn-sm">
									<i class="fa fa-file-text"></i> Preview Kartu
								</a>
								<a href="{{ route('cards.edit', $productionCard->id) }}" class="btn btn-warning btn-sm">
										<i class="fa fa-edit"></i> Edit
									</a>
								</div>
							</div>
						</div>

						<!-- Detail Information -->
						<div class="row">
							<!-- Left Column -->
							<div class="col-md-6">
								<table class="table table-borderless">
									<tbody>
										<tr>
											<td width="40%" class="fw-bold">Status Kartu</td>
											<td width="5%">:</td>
											<td>
												@if($productionCard->cardStatus)
													<span class="badge badge-primary">{{ $productionCard->cardStatus->name }}</span>
												@else
													<span class="badge badge-secondary">NEW</span>
												@endif
											</td>
										</tr>
										<tr>
											<td class="fw-bold">No. Polis</td>
											<td>:</td>
											<td>{{ $productionCard->policy->policy_number ?? '-' }}</td>
										</tr>
										<tr>
											<td class="fw-bold">Nama Asuransi</td>
											<td>:</td>
											<td>{{ $productionCard->policy->policyInsurances->firstWhere('is_main', true)?->insurance->name ?? '-' }}</td>
										</tr>
										<tr>
											<td class="fw-bold">Nama Tertanggung</td>
											<td>:</td>
											<td>{{ $productionCard->client->name ?? '-' }}</td>
										</tr>
										<tr>										<td class="fw-bold">Kode Relasi</td>
										<td>:</td>
										<td>
											@if($productionCard->client && $productionCard->client->relation)
												<span class="badge badge-info">{{ $productionCard->client->relation->code }}</span>
											@else
												-
											@endif
										</td>
									</tr>
									<tr>											<td class="fw-bold">Obyek Asuransi</td>
											<td>:</td>
											<td>{{ $productionCard->policy->object ?? '-' }}</td>
										</tr>
										<tr>
											<td class="fw-bold">Periode Awal</td>
											<td>:</td>
											<td>{{ $productionCard->start_date ? $productionCard->start_date->format('d/m/Y') : '-' }}</td>
										</tr>
										<tr>
											<td class="fw-bold">Periode Akhir</td>
											<td>:</td>
											<td>{{ $productionCard->end_date ? $productionCard->end_date->format('d/m/Y') : '-' }}</td>
										</tr>
									</tbody>
								</table>
							</div>

							<!-- Right Column -->
							<div class="col-md-6">
								<table class="table table-borderless">
									<tbody>
										<tr>
											<td width="40%" class="fw-bold">Harga Pertanggungan</td>
											<td width="5%">:</td>
											<td>Rp {{ number_format($productionCard->premium_gross, 0, ',', '.') }}</td>
										</tr>
										<tr>
											<td class="fw-bold">Tarif Premi</td>
											<td>:</td>
											<td>{{ number_format($productionCard->commission_pct, 3) }}%</td>
										</tr>
										<tr>
											<td class="fw-bold">Tipe Pembayaran</td>
											<td>:</td>
											<td>{{ $productionCard->paymentType->name ?? '-' }}</td>
										</tr>
										<tr>
											<td class="fw-bold">Status Pembayaran</td>
											<td>:</td>
											<td>
												@if($productionCard->insurancePaymentStatus)
													<span class="badge" style="background-color: {{ $productionCard->insurancePaymentStatus->color }}">
														{{ $productionCard->insurancePaymentStatus->name }}
													</span>
												@else
													-
												@endif
											</td>
										</tr>
										<tr>
											<td class="fw-bold">Dibuat Oleh</td>
											<td>:</td>
											<td>{{ $productionCard->creator->name ?? '-' }}</td>
										</tr>
										<tr>
											<td class="fw-bold">Tanggal Dibuat</td>
											<td>:</td>
											<td>{{ $productionCard->created_at ? $productionCard->created_at->format('d/m/Y H:i') : '-' }}</td>
										</tr>
										<tr>
											<td class="fw-bold">Terakhir Diubah</td>
											<td>:</td>
											<td>{{ $productionCard->updated_at ? $productionCard->updated_at->format('d/m/Y H:i') : '-' }}</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>

						<hr>

						<!-- Premi Section -->
						<h6 class="mb-3">Informasi Premi</h6>
						<div class="table-responsive">
							<table class="table table-bordered">
								<thead class="table-light">
									<tr>
										<th>Nilai Produksi</th>
										<th>Biaya Admin</th>
										<th>Total Premi</th>
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

						<!-- Komisi Section -->
						<h6 class="mb-3 mt-4">Informasi Komisi</h6>
						<div class="table-responsive">
							<table class="table table-bordered">
								<thead class="table-light">
									<tr>
										<th>Komisi Bruto</th>
										<th>Nilai Pajak</th>
										<th>Komisi Bersih Awal</th>
										<th>Sisa Komisi Agen</th>
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

						<!-- Pembayaran Section -->
						<h6 class="mb-3 mt-4">Informasi Pembayaran</h6>
						<div class="row">
							<div class="col-md-6">
								<div class="card border">
									<div class="card-header bg-light">
										<strong>Tagihan ke Nasabah</strong>
									</div>
									<div class="card-body">
										<table class="table table-sm table-borderless mb-0">
											<tr>
												<td>Total Premi</td>
												<td class="text-end">Rp {{ number_format($productionCard->total_premi, 0, ',', '.') }}</td>
											</tr>
											<tr>
												<td>Discount/Agen</td>
												<td class="text-end">Rp {{ number_format($productionCard->discount ?? 0, 0, ',', '.') }}</td>
											</tr>
											<tr class="border-top">
												<td><strong>Netto Tagihan</strong></td>
												<td class="text-end"><strong>Rp {{ number_format($productionCard->tagihan_ke_nasabah, 0, ',', '.') }}</strong></td>
											</tr>
										</table>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="card border">
									<div class="card-header bg-light">
										<strong>Pembayaran ke Asuransi</strong>
									</div>
									<div class="card-body">
										<table class="table table-sm table-borderless mb-0">
											<tr>
												<td>Total Premi</td>
												<td class="text-end">Rp {{ number_format($productionCard->total_premi, 0, ',', '.') }}</td>
											</tr>
											<tr>
												<td>Komisi</td>
												<td class="text-end">Rp {{ number_format($productionCard->komisi_bersih_awal, 0, ',', '.') }}</td>
											</tr>
											<tr class="border-top">
												<td><strong>Netto Bayar</strong></td>
												<td class="text-end"><strong>Rp {{ number_format($productionCard->netto_bayar_asuransi, 0, ',', '.') }}</strong></td>
											</tr>
										</table>
									</div>
								</div>
							</div>
						</div>

						<!-- Pelunasan Info -->
						<h6 class="mb-3 mt-4">Informasi Pelunasan</h6>
						<div class="row">
							<div class="col-md-6">
								<table class="table table-borderless">
									<tbody>
										<tr>
											<td width="40%" class="fw-bold">Tanggal Pelunasan</td>
											<td width="5%">:</td>
											<td>{{ $productionCard->insurance_paid_at ? $productionCard->insurance_paid_at->format('d/m/Y') : '-' }}</td>
										</tr>
										<tr>
											<td class="fw-bold">No. Bukti</td>
											<td>:</td>
											<td>{{ $productionCard->insurance_evident_number ?? '-' }}</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>

						<!-- Riwayat Pembayaran -->
						<h6 class="mb-3 mt-4">Riwayat Pembayaran</h6>
						@php
						// Generate sample payment data - Contoh Cicilan 3x (Lunas)
						$insuranceCode = $productionCard->policy->policyInsurances->firstWhere('is_main', true)?->insurance->code ?? 'BINTANG';
						$sharePercentage = $productionCard->policy->policyInsurances->firstWhere('is_main', true)?->percentage ?? 50.025;
						
						// Bagi menjadi 3 cicilan
						$totalPremi = $productionCard->premi_amount;
						$cicilan1 = $totalPremi * 0.4; // 40%
						$cicilan2 = $totalPremi * 0.35; // 35%
						$cicilan3 = $totalPremi * 0.25; // 25%
						
						$payments = collect([
							[
								'payment_number' => 1,
								'payment_date' => $productionCard->start_date,
								'insurance_code' => $insuranceCode,
								'share_percentage' => $sharePercentage,
								'premium_commission' => $productionCard->commission_amount * 0.4,
								'policy_fee' => $productionCard->polis_amount * 0.4,
								'commission_amount' => $productionCard->commission_total * 0.4,
								'tax_amount' => $productionCard->tax_amount * 0.4,
								'premi_amount' => $cicilan1,
								'status' => 'paid',
								'notes' => 'Cicilan 1 (40%) - Lunas'
							],
							[
								'payment_number' => 2,
								'payment_date' => \Carbon\Carbon::parse($productionCard->start_date)->addMonths(1),
								'insurance_code' => $insuranceCode,
								'share_percentage' => $sharePercentage,
								'premium_commission' => $productionCard->commission_amount * 0.35,
								'policy_fee' => $productionCard->polis_amount * 0.35,
								'commission_amount' => $productionCard->commission_total * 0.35,
								'tax_amount' => $productionCard->tax_amount * 0.35,
								'premi_amount' => $cicilan2,
								'status' => 'paid',
								'notes' => 'Cicilan 2 (35%) - Lunas'
							],
							[
								'payment_number' => 3,
								'payment_date' => \Carbon\Carbon::parse($productionCard->start_date)->addMonths(2),
								'insurance_code' => $insuranceCode,
								'share_percentage' => $sharePercentage,
								'premium_commission' => $productionCard->commission_amount * 0.25,
								'policy_fee' => $productionCard->polis_amount * 0.25,
								'commission_amount' => $productionCard->commission_total * 0.25,
								'tax_amount' => $productionCard->tax_amount * 0.25,
								'premi_amount' => $cicilan3,
								'status' => 'paid',
								'notes' => 'Cicilan 3 (25%) - Lunas'
							]
						]);
						@endphp
						<div class="table-responsive">
							<table class="table table-bordered table-striped table-hover">
								<thead class="table-light">
									<tr>
										<th class="text-center">Pembayaran Ke-</th>
										<th class="text-center">Tanggal</th>
										@if(!auth()->user()->hasRole('client'))
										<th class="text-center">No. Polis & Pol. Note</th>
										<th class="text-center">Kode Asuransi</th>
										<th class="text-center">Share %</th>
										<th class="text-end">Ko. Premi</th>
										<th class="text-end">Biaya Polis</th>
										<th class="text-end">Komisi</th>
										<th class="text-end">Pajak</th>
										@endif
										<th class="text-end">Premi</th>
										@if(!auth()->user()->hasRole('client'))
										<th class="text-center">Status</th>
										<th>Keterangan</th>
										@endif
									</tr>
								</thead>
								<tbody>
									@forelse($payments as $payment)
									<tr>
										<td class="text-center">{{ $payment['payment_number'] }}</td>
										<td class="text-center">{{ $payment['payment_date'] ? \Carbon\Carbon::parse($payment['payment_date'])->format('d/m/Y') : '-' }}</td>
										@if(!auth()->user()->hasRole('client'))
										<td>{{ $productionCard->policy->policy_number ?? '-' }}</td>
										<td class="text-center">{{ $payment['insurance_code'] ?? '-' }}</td>
										<td class="text-center">{{ number_format($payment['share_percentage'] ?? 0, 2) }}%</td>
										<td class="text-end">{{ number_format($payment['premium_commission'] ?? 0, 0, ',', '.') }}</td>
										<td class="text-end">{{ number_format($payment['policy_fee'] ?? 0, 0, ',', '.') }}</td>
										<td class="text-end">{{ number_format($payment['commission_amount'] ?? 0, 0, ',', '.') }}</td>
										<td class="text-end">{{ number_format($payment['tax_amount'] ?? 0, 0, ',', '.') }}</td>
										@endif
										<td class="text-end"><strong>{{ number_format($payment['premi_amount'] ?? 0, 0, ',', '.') }}</strong></td>
										@if(!auth()->user()->hasRole('client'))
										<td class="text-center">
											@if($payment['status'] == 'paid')
												<span class="badge bg-success">PAID</span>
											@elseif($payment['status'] == 'pending')
												<span class="badge bg-warning">PENDING</span>
											@else
												<span class="badge bg-secondary">UNPAID</span>
											@endif
										</td>
										<td>{{ $payment['notes'] ?? '-' }}</td>
										@endif
									</tr>
									@empty
									<tr>
										<td colspan="{{ auth()->user()->hasRole('client') ? '3' : '13' }}" class="text-center text-muted">
											<em>Belum ada data pembayaran</em>
										</td>
									</tr>
									@endforelse
								</tbody>
								@if($payments->isNotEmpty() && !auth()->user()->hasRole('client'))
								<tfoot class="table-light">
									<tr>
										<th colspan="5" class="text-end">Total:</th>
										<th class="text-end">{{ number_format($payments->sum('premium_commission'), 0, ',', '.') }}</th>
										<th class="text-end">{{ number_format($payments->sum('policy_fee'), 0, ',', '.') }}</th>
										<th class="text-end">{{ number_format($payments->sum('commission_amount'), 0, ',', '.') }}</th>
										<th class="text-end">{{ number_format($payments->sum('tax_amount'), 0, ',', '.') }}</th>
										<th class="text-end"><strong>{{ number_format($payments->sum('premi_amount'), 0, ',', '.') }}</strong></th>
										<th colspan="2"></th>
									</tr>
								</tfoot>
								@endif
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

@endsection
