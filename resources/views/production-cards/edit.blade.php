@extends('layouts.master')

@section('title')Edit Kartu Produksi
@endsection

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/date-picker.css') }}">
@endpush

@section('content')
	@component('components.breadcrumb')
		@slot('breadcrumb_title')
			<h3>Edit Kartu Produksi</h3>
		@endslot
		<li class="breadcrumb-item">Kartu Produksi</li>
        <li class="breadcrumb-item active">Edit</li>
	@endcomponent
	
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="card">
					<div class="card-header pb-0">
						<h5>Form Edit Kartu Produksi</h5>
					</div>
					<div class="card-body">
						<form action="{{ route('cards.update', $productionCard->id) }}" method="POST" class="needs-validation" novalidate="">
							@csrf
							@method('PUT')
							
							{{-- SECTION: Dropdown dari Table --}}
							<h6 class="mb-3">Data Referensi</h6>
							<div class="row g-3">
								<div class="col-md-4">
									<label class="form-label" for="client_id">Client</label>
									<select class="form-control @error('client_id') is-invalid @enderror" 
										id="client_id" 
										name="client_id" 
										required>
										<option value="">Pilih Client</option>
										@foreach($clients as $client)
											<option value="{{ $client->id }}" 
												{{ (old('client_id', $productionCard->client_id) == $client->id) ? 'selected' : '' }}>
												{{ $client->name }}
											</option>
										@endforeach
									</select>
									@error('client_id')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
								<div class="col-md-4">
									<label class="form-label" for="policy_id">Policy</label>
									<select class="form-control @error('policy_id') is-invalid @enderror" 
										id="policy_id" 
										name="policy_id" 
										required>
										<option value="">Pilih Policy</option>
										@foreach($policies as $policy)
											<option value="{{ $policy->id }}" 
												{{ (old('policy_id', $productionCard->policy_id) == $policy->id) ? 'selected' : '' }}>
												{{ $policy->policy_number }} - {{ $policy->object }}
											</option>
										@endforeach
									</select>
									@error('policy_id')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
								<div class="col-md-4">
									<label class="form-label" for="card_status_id">Status Kartu</label>
									<select class="form-control @error('card_status_id') is-invalid @enderror" 
										id="card_status_id" 
										name="card_status_id" 
										required>
										<option value="">Pilih Status</option>
										@foreach($cardStatuses as $status)
											<option value="{{ $status->id }}" 
												{{ (old('card_status_id', $productionCard->card_status_id) == $status->id) ? 'selected' : '' }}>
												{{ $status->name }}
											</option>
										@endforeach
									</select>
									@error('card_status_id')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
							</div><br>
							
							{{-- SECTION: HARGA PERTANGGUNGAN --}}
							<h6 class="mb-3">Harga Pertanggungan</h6>
							<div class="row g-3">
								<div class="col-md-12">
									<label class="form-label" for="harga_pertanggungan">Harga Pertanggungan (Rp)</label>
									<input class="form-control @error('harga_pertanggungan') is-invalid @enderror" 
										id="harga_pertanggungan" 
										name="harga_pertanggungan" 
										type="number" 
										step="0.01"
										value="{{ old('harga_pertanggungan', $productionCard->premium_gross) }}" 
										required />
									<small class="text-muted">Contoh: 1500000000 untuk 1.5 Miliar</small>
									@error('harga_pertanggungan')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
							</div><br>
							
							<div class="row g-3">
								<div class="col-md-6">
									<label class="form-label" for="periode_mulai">Periode Mulai</label>
									<input class="datepicker-here form-control digits @error('periode_mulai') is-invalid @enderror" 
										id="periode_mulai" 
										name="periode_mulai" 
										type="text" 
										value="{{ old('periode_mulai', $productionCard->start_date ? $productionCard->start_date->format('Y-m-d') : '') }}"
										data-language="en" 
										required />
									@error('periode_mulai')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
								<div class="col-md-6">
									<label class="form-label" for="periode_akhir">Periode Akhir</label>
									<input class="datepicker-here form-control digits @error('periode_akhir') is-invalid @enderror" 
										id="periode_akhir" 
										name="periode_akhir" 
										type="text" 
										value="{{ old('periode_akhir', $productionCard->end_date ? $productionCard->end_date->format('Y-m-d') : '') }}"
										data-language="en" 
										required />
									@error('periode_akhir')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
							</div><br>
							
							{{-- SECTION: PERHITUNGAN PREMI --}}
							<h6 class="mb-3">Perhitungan Premi</h6>
							<div class="row g-3">
								<div class="col-md-6">
									<label class="form-label" for="tarif_premi">Tarif Premi (%)</label>
									<input class="form-control @error('tarif_premi') is-invalid @enderror" 
										id="tarif_premi" 
										name="tarif_premi" 
										type="number" 
										step="0.001"
										value="{{ old('tarif_premi', $productionCard->commission_pct) }}" 
										required />
									<small class="text-muted">Contoh: 0.03 untuk 0.03%</small>
									@error('tarif_premi')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
								<div class="col-md-6">
									<label class="form-label" for="biaya_admin">Biaya Admin (Rp)</label>
									<input class="form-control @error('biaya_admin') is-invalid @enderror" 
										id="biaya_admin" 
										name="biaya_admin" 
										type="number" 
										step="0.01"
										value="{{ old('biaya_admin', $productionCard->admin_fee) }}" 
										required />
									@error('biaya_admin')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
							</div><br>
							
							<div class="row g-3">
								<div class="col-md-6">
									<label class="form-label" for="discount">Discount/Agen (Rp)</label>
									<input class="form-control @error('discount') is-invalid @enderror" 
										id="discount" 
										name="discount" 
										type="number" 
										step="0.01"
										value="{{ old('discount', $productionCard->discount ?? 0) }}" />
									<small class="text-muted">Opsional</small>
									@error('discount')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
								<div class="col-md-6">
									<label class="form-label" for="pajak_komisi">Pajak Komisi (%) - PPh 23</label>
									<input class="form-control @error('pajak_komisi') is-invalid @enderror" 
										id="pajak_komisi" 
										name="pajak_komisi" 
										type="number" 
										step="0.01"
										value="{{ old('pajak_komisi', $productionCard->tax_percentage) }}" 
										required />
									<small class="text-muted">Contoh: 2 untuk 2%</small>
									@error('pajak_komisi')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
							</div><br>
							
							{{-- SECTION: STATUS PEMBAYARAN --}}
							<h6 class="mb-3">Status Pembayaran</h6>
							<div class="row g-3">
								<div class="col-md-4">
									<label class="form-label" for="insurance_payment_status_id">Status Pembayaran Asuransi</label>
									<select class="form-control @error('insurance_payment_status_id') is-invalid @enderror" 
										id="insurance_payment_status_id" 
										name="insurance_payment_status_id">
										<option value="">Pilih Status</option>
										@foreach($paymentStatuses as $status)
											<option value="{{ $status->id }}" 
												{{ (old('insurance_payment_status_id', $productionCard->insurance_payment_status_id) == $status->id) ? 'selected' : '' }}>
												{{ $status->name }}
											</option>
										@endforeach
									</select>
									@error('insurance_payment_status_id')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
								<div class="col-md-4">
									<label class="form-label" for="payment_type_id">Tipe Pembayaran</label>
									<select class="form-control @error('payment_type_id') is-invalid @enderror" 
										id="payment_type_id" 
										name="payment_type_id">
										<option value="">Pilih Tipe</option>
										@foreach($paymentTypes as $type)
											<option value="{{ $type->id }}" 
												{{ (old('payment_type_id', $productionCard->payment_type_id) == $type->id) ? 'selected' : '' }}>
												{{ $type->name }}
											</option>
										@endforeach
									</select>
									@error('payment_type_id')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
								<div class="col-md-4">
									<label class="form-label" for="insurance_paid_at">Tanggal Pelunasan</label>
									<input class="datepicker-here form-control digits @error('insurance_paid_at') is-invalid @enderror" 
										id="insurance_paid_at" 
										name="insurance_paid_at" 
										type="text" 
										value="{{ old('insurance_paid_at', $productionCard->insurance_paid_at ? $productionCard->insurance_paid_at->format('Y-m-d') : '') }}"
										data-language="en" />
									<small class="text-muted">Opsional</small>
									@error('insurance_paid_at')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
							</div><br>
							
							<div class="row g-3">
								<div class="col-md-12">
									<label class="form-label" for="insurance_evident_number">No. Bukti Pelunasan</label>
									<input class="form-control @error('insurance_evident_number') is-invalid @enderror" 
										id="insurance_evident_number" 
										name="insurance_evident_number" 
										type="text" 
										value="{{ old('insurance_evident_number', $productionCard->insurance_evident_number) }}" />
									<small class="text-muted">Opsional</small>
									@error('insurance_evident_number')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
							</div><br>
							
							<div class="mb-3">
								<button class="btn btn-primary" type="submit">Update</button>
								<a href="{{ route('cards.detail', $productionCard->id) }}" class="btn btn-secondary">Kembali</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
<script src="{{ asset('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
<script src="{{ asset('assets/js/datepicker/date-picker/datepicker.custom.js') }}"></script>
<script src="{{ asset('assets/js/form-validation-custom.js') }}"></script>
<script>
// Add confirmation on form submit
document.querySelector('.needs-validation').addEventListener('submit', function(e) {
	if (!this.checkValidity()) {
		e.preventDefault();
		e.stopPropagation();
		return false;
	}
	
	if (!confirm('Apakah Anda yakin ingin mengupdate data kartu produksi ini?')) {
		e.preventDefault();
		return false;
	}
});
</script>
@endpush