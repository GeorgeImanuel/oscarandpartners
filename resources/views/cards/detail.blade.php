@extends('layouts.master')

@section('title')
    Detail Kartu Produksi
@endsection

@push('css')
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
                                            <td>{{ $currency }} {{ number_format($card->tjh_value,  $currency == 'Rp' ? 0 : 2, ',', '.') }}</td>
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
