@extends('layouts.master')

@section('title')
    Detail Kartu Produksi
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
                        <h5>Detail Kartu Produksi #{{ str_pad($card->id, 4, '0', STR_PAD_LEFT) }}</h5>
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
                            <!-- Left Column -->
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td width="40%"
                                                class="fw-bold">Status Kartu</td>
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
                                            <td>{{ $card->policy->policyInsurances->firstWhere('is_main', true)?->insurance->name ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Nama Tertanggung</td>
                                            <td>:</td>
                                            <td>{{ $card->client->id_name ?? $card->client->user->name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Kode Relasi</td>
                                            <td>:</td>
                                            <td>
                                                @if ($card->client && $card->client->relation)
                                                    <span class="badge badge-info">{{ $card->client->relation->code }}</span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Obyek Asuransi</td>
                                            <td>:</td>
                                            <td>{{ $card->policy->object->name ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Periode Awal</td>
                                            <td>:</td>
                                            <td>{{ $card->policy->started_date ? $card->policy->started_date->format('d/m/Y') : '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Periode Akhir</td>
                                            <td>:</td>
                                            <td>{{ $card->policy->ended_date ? $card->policy->ended_date->format('d/m/Y') : '-' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Right Column -->
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td width="40%"
                                                class="fw-bold">Harga Pertanggungan</td>
                                            <td width="5%">:</td>
                                            <td>Rp {{ number_format($card->object_value, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Tarif Premi</td>
                                            <td>:</td>
                                            <td>{{ number_format($card->premi_value, 3) }}%</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Tipe Pembayaran</td>
                                            <td>:</td>
                                            <td>{{ $card->paymentType->name ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Status Pembayaran</td>
                                            <td>:</td>
                                            <td>
                                                @if ($card->insurancePaymentStatus)
                                                    <span class="badge"
                                                        style="background-color: {{ $card->insurancePaymentStatus->color }}">
                                                        {{ $card->insurancePaymentStatus->name }}
                                                    </span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Dibuat Oleh</td>
                                            <td>:</td>
                                            <td>{{ $card->creator->name ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Tanggal Dibuat</td>
                                            <td>:</td>
                                            <td>{{ $card->created_at ? $card->created_at->format('d/m/Y H:i') : '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Terakhir Diubah</td>
                                            <td>:</td>
                                            <td>{{ $card->updated_at ? $card->updated_at->format('d/m/Y H:i') : '-' }}</td>
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
                                        <td>Rp {{ number_format($card->production_value, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($card->policy_fee, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($card->commision_bruto, 0, ',', '.') }}</td>
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
                                        <td>Rp {{ number_format($card->commision_bruto, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($card->tax_value, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($card->commision_nettos, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($card->commision_netto, 0, ',', '.') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pembayaran Section -->
                        {{-- <h6 class="mb-3 mt-4">Informasi Pembayaran</h6>
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
												<td class="text-end">Rp {{ number_format($card->total_premi, 0, ',', '.') }}</td>
											</tr>
											<tr>
												<td>Discount/Agen</td>
												<td class="text-end">Rp {{ number_format($card->discount ?? 0, 0, ',', '.') }}</td>
											</tr>
											<tr class="border-top">
												<td><strong>Netto Tagihan</strong></td>
												<td class="text-end"><strong>Rp {{ number_format($card->tagihan_ke_nasabah, 0, ',', '.') }}</strong></td>
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
												<td class="text-end">Rp {{ number_format($card->total_premi, 0, ',', '.') }}</td>
											</tr>
											<tr>
												<td>Komisi</td>
												<td class="text-end">Rp {{ number_format($card->komisi_bersih_awal, 0, ',', '.') }}</td>
											</tr>
											<tr class="border-top">
												<td><strong>Netto Bayar</strong></td>
												<td class="text-end"><strong>Rp {{ number_format($card->netto_bayar_asuransi, 0, ',', '.') }}</strong></td>
											</tr>
										</table>
									</div>
								</div>
							</div>
						</div> --}}

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
                        <h6 class="mb-3 mt-4">Riwayat Pembayaran</h6>
                        {{-- @php
						// Generate sample payment data - Contoh Cicilan 3x (Lunas)
						$insuranceCode = $card->policy->policyInsurances->firstWhere('is_main', true)?->insurance->code ?? 'BINTANG';
						$sharePercentage = $card->policy->policyInsurances->firstWhere('is_main', true)?->percentage ?? 50.025;
						
						// Bagi menjadi 3 cicilan
						$totalPremi = $card->premi_amount;
						$cicilan1 = $totalPremi * 0.4; // 40%
						$cicilan2 = $totalPremi * 0.35; // 35%
						$cicilan3 = $totalPremi * 0.25; // 25%
						
						$payments = collect([
							[
								'payment_number' => 1,
								'payment_date' => $card->start_date,
								'insurance_code' => $insuranceCode,
								'share_percentage' => $sharePercentage,
								'premium_commission' => $card->commission_amount * 0.4,
								'policy_fee' => $card->polis_amount * 0.4,
								'commission_amount' => $card->commission_total * 0.4,
								'tax_amount' => $card->tax_amount * 0.4,
								'premi_amount' => $cicilan1,
								'status' => 'paid',
								'notes' => 'Cicilan 1 (40%) - Lunas'
							],
							[
								'payment_number' => 2,
								'payment_date' => \Carbon\Carbon::parse($card->start_date)->addMonths(1),
								'insurance_code' => $insuranceCode,
								'share_percentage' => $sharePercentage,
								'premium_commission' => $card->commission_amount * 0.35,
								'policy_fee' => $card->polis_amount * 0.35,
								'commission_amount' => $card->commission_total * 0.35,
								'tax_amount' => $card->tax_amount * 0.35,
								'premi_amount' => $cicilan2,
								'status' => 'paid',
								'notes' => 'Cicilan 2 (35%) - Lunas'
							],
							[
								'payment_number' => 3,
								'payment_date' => \Carbon\Carbon::parse($card->start_date)->addMonths(2),
								'insurance_code' => $insuranceCode,
								'share_percentage' => $sharePercentage,
								'premium_commission' => $card->commission_amount * 0.25,
								'policy_fee' => $card->polis_amount * 0.25,
								'commission_amount' => $card->commission_total * 0.25,
								'tax_amount' => $card->tax_amount * 0.25,
								'premi_amount' => $cicilan3,
								'status' => 'paid',
								'notes' => 'Cicilan 3 (25%) - Lunas'
							]
						]);
						@endphp --}}
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center"
                                            width="50px">Angsuran Ke-</th>
                                        <th class="text-center">Jatuh Tempo</th>
                                        <th class="text-center">Asuransi</th>
                                        <th class="text-center">Share</th>
                                        <th class="text-center">Premi</th>
                                        <th class="text-center">Biaya Polis</th>
                                        <th class="text-center">Status Pembayaran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($card->payments as $index => $payment)
                                        <tr>
                                            <td class="text-center">{{ $payment['sequence'] }}</td>
                                            <td class="text-center">{{ $payment['deadline_at'] }}</td>
                                            <td class="text-center">
                                                @foreach ($card->policy->policyInsurances as $policy_insurance)
                                                    {{ $policy_insurance->insurance->name }}<br>
                                                @endforeach
                                            </td>
                                            <td class="text-center">
                                                @foreach ($card->policy->policyInsurances as $policy_insurance)
                                                    {{ $policy_insurance->percentage }}<br>
                                                @endforeach
                                            </td>
											<td class="text-end">
                                                @foreach ($card->policy->policyInsurances as $policy_insurance)
                                                    {{ numberFormat($payment['amount'] * $policy_insurance->percentage,true) }}<br>
													@endforeach
                                                    {{ numberFormat($payment['amount'])}}
                                            </td>
											<td class="text-end">
												@if($payment->sequence == 1) {{ numberFormat($card->policy_fee,true)}} <br><br>@endif
                                                    {{ numberFormat($payment['amount']+ $card->policy_fee)}}
											</td>
                                            <td class="text-center">
                                                <span class="badge" style="background:{{ $payment->paymentStatus->color }}">{{ $payment->paymentStatus->name }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ auth()->user()->hasRole('client') ? '3' : '13' }}"
                                                class="text-center text-muted">
                                                <em>Belum ada data pembayaran</em>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                @if ($card->payments->isNotEmpty() && !auth()->user()->hasRole('client'))
                                    <tfoot class="table-light">
                                        <tr>
                                            <th colspan="4" class="text-end">Total:</th>
                                            <th class="text-end">{{ numberFormat($card->payments->sum('amount'))}}</th>	
                                            <th class="text-end">{{ numberFormat($card->payments->sum('amount')+ $card->policy_fee)}}</th>	
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
