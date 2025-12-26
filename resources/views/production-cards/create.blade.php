@extends('layouts.master')

@section('title')Create Kartu Produksi
@endsection

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/date-picker.css') }}">
@endpush

@section('content')
	@component('components.breadcrumb')
		@slot('breadcrumb_title')
			<h3>Create Kartu Produksi</h3>
		@endslot
		<li class="breadcrumb-item">Kartu Produksi</li>
        <li class="breadcrumb-item active">Create</li>
	@endcomponent
	
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="card">
					<div class="card-header pb-0">
						<h5>Form Create Kartu Produksi</h5>
					</div>
					<div class="card-body">
						<form action="{{ route('cards.store') }}" method="POST" class="needs-validation" novalidate="">
							@csrf
							
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
											<option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
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
											<option value="{{ $policy->id }}" {{ old('policy_id') == $policy->id ? 'selected' : '' }}>
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
											<option value="{{ $status->id }}" {{ old('card_status_id') == $status->id ? 'selected' : '' }}>
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
										value="{{ old('harga_pertanggungan') }}" 
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
										value="{{ old('periode_mulai') }}"
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
										value="{{ old('periode_akhir') }}"
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
										value="{{ old('tarif_premi') }}" 
										required />
									<small class="text-muted">Contoh: 0.03 untuk 0.03%</small>
									@error('tarif_premi')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
								<div class="col-md-6">
									<label class="form-label" for="biaya_polis">Biaya Polis (Rp)</label>
									<input class="form-control @error('biaya_polis') is-invalid @enderror" 
										id="biaya_polis" 
										name="biaya_polis" 
										type="number" 
										step="0.01"
										value="{{ old('biaya_polis') }}" 
										required />
									<small class="text-muted">Contoh: 40000 untuk biaya administrasi</small>
									@error('biaya_polis')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
							</div><br>
							
							{{-- SECTION: PERHITUNGAN KOMISI --}}
							<h6 class="mb-3">Perhitungan Komisi</h6>
							<div class="row g-3">
								<div class="col-md-6">
									<label class="form-label" for="persentase_komisi">Persentase Komisi (%)</label>
									<input class="form-control @error('persentase_komisi') is-invalid @enderror" 
										id="persentase_komisi" 
										name="persentase_komisi" 
										type="number" 
										step="0.01"
										value="{{ old('persentase_komisi') }}" 
										required />
									<small class="text-muted">Contoh: 15 untuk 15%</small>
									@error('persentase_komisi')
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
										value="{{ old('pajak_komisi') }}" 
										required />
									<small class="text-muted">Contoh: 5 untuk 5% atau 2.5 untuk 2.5%</small>
									@error('pajak_komisi')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
							</div><br>
							
							{{-- SECTION: SUBSIDI & TAGIHAN --}}
							<h6 class="mb-3">Subsidi & Tagihan</h6>
							<div class="row g-3">
								<div class="col-md-12">
									<label class="form-label" for="diskon_dari_komisi">Diskon dari Komisi (Rp) - Subsidi Agen</label>
									<input class="form-control @error('diskon_dari_komisi') is-invalid @enderror" 
										id="diskon_dari_komisi" 
										name="diskon_dari_komisi" 
										type="number" 
										step="0.01"
										value="{{ old('diskon_dari_komisi', 0) }}" />
									<small class="text-muted">Potong komisi sendiri untuk diskon ke nasabah. Kosongkan atau isi 0 jika tidak ada subsidi.</small>
									@error('diskon_dari_komisi')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
							</div><br>
							
							{{-- SECTION: PAYMENT INFO --}}
							<h6 class="mb-3">Informasi Pembayaran</h6>
							<div class="row g-3">
								<div class="col-md-6">
									<label class="form-label" for="insurance_payment_status_id">Status Pembayaran Asuransi</label>
									<select class="form-control @error('insurance_payment_status_id') is-invalid @enderror" 
										id="insurance_payment_status_id" 
									name="insurance_payment_status_id"
									required>
										<option value="">Pilih Status Pembayaran</option>
										@foreach($paymentStatuses as $paymentStatus)
											<option value="{{ $paymentStatus->id }}" {{ old('insurance_payment_status_id') == $paymentStatus->id ? 'selected' : '' }}>
												{{ $paymentStatus->name }}
											</option>
										@endforeach
									</select>
									@error('insurance_payment_status_id')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
								<div class="col-md-6">
									<label class="form-label" for="payment_type_id">Tipe Pembayaran</label>
									<select class="form-control @error('payment_type_id') is-invalid @enderror" 
										id="payment_type_id" 
										name="payment_type_id" 
										required>
										<option value="">Pilih Tipe Pembayaran</option>
										@foreach($paymentTypes as $paymentType)
											<option value="{{ $paymentType->id }}" {{ old('payment_type_id') == $paymentType->id ? 'selected' : '' }}>
												{{ $paymentType->name }}
											</option>
										@endforeach
									</select>
									@error('payment_type_id')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
							</div><br>
							
							<div class="row g-3">
								<div class="col-md-6">
									<label class="form-label" for="insurance_paid_at">Tanggal Bayar Asuransi</label>
									<input class="datepicker-here form-control digits @error('insurance_paid_at') is-invalid @enderror" 
										id="insurance_paid_at" 
										name="insurance_paid_at" 
										type="text" 
										value="{{ old('insurance_paid_at') }}"
										data-language="en" />
									<small class="text-muted">Opsional - isi jika sudah dibayar</small>
									@error('insurance_paid_at')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
								<div class="col-md-6">
									<label class="form-label" for="insurance_evident_number">Nomor Bukti Bayar</label>
									<input class="form-control @error('insurance_evident_number') is-invalid @enderror" 
										id="insurance_evident_number" 
										name="insurance_evident_number" 
										type="text" 
										value="{{ old('insurance_evident_number') }}" />
									<small class="text-muted">Opsional - nomor transaksi/bukti transfer</small>
									@error('insurance_evident_number')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
							</div><br>
							
							<div class="mb-3">
								<button class="btn btn-primary" type="submit">Create</button>
								<a href="{{ route('cards.view') }}" class="btn btn-secondary">Cancel</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	@push('scripts')
	<script src="{{ asset('assets/js/form-validation-custom.js') }}"></script>
	<script src="{{ asset('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.custom.js') }}"></script>
	<script src="{{ asset('assets/js/datepicker/date-time-picker/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker/date-time-picker/tempusdominus-bootstrap-4.min.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker/date-time-picker/datetimepicker.custom.js') }}"></script>
    <script>
        // Add confirmation on submit
        document.querySelector('.needs-validation').addEventListener('submit', function(e) {
            if (this.checkValidity()) {
                if (!confirm('Apakah Anda yakin ingin menyimpan kartu produksi ini?')) {
                    e.preventDefault();
                    e.stopPropagation();
                }
            }
        });
    </script>
	@endpush

@endsection
