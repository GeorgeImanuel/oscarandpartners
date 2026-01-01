@extends('layouts.master')

@section('title')Create Kartu Produksi
@endsection

@push('css')
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/date-picker.css') }}">
@endpush

@section('content')
	@component('components.breadcrumb')
	@slot('breadcrumb_title')
	<h3>{{ isset($card) ? 'Ubah' : 'Tambah' }} Kartu Produksi</h3>
	@endslot
	<li class="breadcrumb-item"><a href="{{ route('cards.index') }}">Kartu Produksi</a></li>
	<li class="breadcrumb-item active">{{ isset($card) ? 'Ubah' : 'Tambah' }}</li>
	@endcomponent

	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="card">
					<form action="{{ isset($card) ? route('cards.update', $card->id) : route('cards.store') }}" method="POST" class="needs-validation" novalidate="">
						@csrf
						@if(isset($card))
							@method('PUT')
						@endif
						<div class="card-body">
							{{-- <h6 class="mt-3">Data Referensi</h6> --}}
							
							<div class="row g-3">
								<div class="col-md-12">
									<label class="form-label" for="card_status_id">Tipe Kartu</label>
									<select class="form-control basic-single-select2 @error('card_status_id') is-invalid @enderror" id="card_status_id" name="card_status_id" required>
										@foreach($cardStatuses as $status)
											<option value="{{ $status->id }}" {{ isset($card) ? ($card->card_status_id == $status->id ? 'selected' : '') : (old('card_status_id') == $status->id ? 'selected' : '') }}>
												{{ $status->name }}
											</option>
										@endforeach
									</select>
									@error('card_status_id')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>

								<div class="col-md-12" style="display:none;" id="parent_card_id ">
									<label class="form-label" >Kartu Produksi Sebelumnya</label>
									<select class="form-control basic-single-select2 @error('parent_card_id') is-invalid @enderror" name="parent_card_id" required>
										@foreach($parentCards as $parentCard)
											<option value="{{ $parentCard->id }}" {{ isset($card) ? ($card->parent_card_id == $parentCard->id ? 'selected' : '') : (old('parent_card_id') == $parentCard->id ? 'selected' : '') }}>
												{{ $parentCard->card_number }} - {{ $parentCard->client->name }}
											</option>
										@endforeach
									</select>
									@error('policy_id')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>

								<div class="col-md-12">
									<label class="form-label" for="client_id">Client</label>
									<select class="form-control basic-single-select2 @error('client_id') is-invalid @enderror" id="client_id" name="client_id" required>
										@foreach($clients as $client)
											<option value="{{ $client->id }}" {{ isset($card) ? ($card->client_id == $client->id ? 'selected' : '') : (old('client_id') == $client->id ? 'selected' : '') }}>
												{{ $client->id_name }}
											</option>
										@endforeach
									</select>
									@error('client_id')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
								
								<div class="col-md-12">
									<label class="form-label" for="policy_id">Policy</label>
									<select class="form-control basic-single-select2 @error('policy_id') is-invalid @enderror" id="policy_id" name="policy_id" required>
										@foreach($policies as $policy)
											<option value="{{ $policy->id }}" {{ isset($card) ? ($card->policy_id == $policy->id ? 'selected' : '') : (old('policy_id') == $policy->id ? 'selected' : '') }}>
												{{ $policy->policy_number }} - {{ $policy->object->name }}
											</option>
										@endforeach
									</select>
									@error('policy_id')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
							</div>

							{{-- SECTION: HARGA PERTANGGUNGAN --}}
							<h6 class="mt-3">Harga Pertanggungan</h6>


							{{-- SECTION: PERHITUNGAN PREMI --}}
							<h6 class="mt-3">Perhitungan Premi</h6>
							<div class="row g-3">
								<div class="col-md-6">
									<label class="form-label" for="premi_percentages">Presentase Premi (%)</label>
									<input class="form-control @error('premi_percentages') is-invalid @enderror" id="premi_percentages" name="premi_percentages" type="number" step="0.001" value="{{ isset($card) ? $card->premi_percentages : old('premi_percentages') }}" required />
									<small class="text-muted">Contoh: 0.03 untuk 0.03%</small>
									@error('premi_percentages')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
								<div class="col-md-6">
									<label class="form-label" for="tax_percentages">Persentase Pajak (%)</label>
									<input class="form-control @error('tax_percentages') is-invalid @enderror" id="tax_percentages" name="tax_percentages" type="number" step="0.001" value="{{ isset($card) ? $card->tax_percentages : old('tax_percentages') }}" required />
									<small class="text-muted">Contoh: 0.03 untuk 0.03%</small>
									@error('tax_percentages')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
								<div class="col-md-6">
									<label class="form-label" for="commision_percentages">Presentasi Komisi (%)</label>
									<input class="form-control @error('commision_percentages') is-invalid @enderror" id="commision_percentages" name="commision_percentages" type="number" step="0.01" value="{{ isset($card) ? $card->commision_percentages : old('commision_percentages') }}" required />
									@error('commision_percentages')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>

								<div class="col-md-6">
									<label class="form-label" for="biaya_polis">Komisi Agen</label>
									<input class="form-control @error('biaya_polis') is-invalid @enderror" id="biaya_polis" name="biaya_polis" type="number" step="0.01" value="{{ old('biaya_polis') }}" required />
									<small class="text-muted">Contoh: 40000 untuk biaya administrasi</small>
									@error('biaya_polis')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
							</div>

							{{-- SECTION: PERHITUNGAN KOMISI --}}
							<h6 class="mt-3">Perhitungan Komisi</h6>
							<div class="row g-3">
								<div class="col-md-6">
									<label class="form-label" for="persentase_komisi">Persentase Komisi (%)</label>
									<input class="form-control @error('persentase_komisi') is-invalid @enderror" id="persentase_komisi" name="persentase_komisi" type="number" step="0.01" value="{{ old('persentase_komisi') }}" required />
									<small class="text-muted">Contoh: 15 untuk 15%</small>
									@error('persentase_komisi')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
								<div class="col-md-6">
									<label class="form-label" for="pajak_komisi">Pajak Komisi (%) - PPh 23</label>
									<input class="form-control @error('pajak_komisi') is-invalid @enderror" id="pajak_komisi" name="pajak_komisi" type="number" step="0.01" value="{{ old('pajak_komisi') }}" required />
									<small class="text-muted">Contoh: 5 untuk 5% atau 2.5 untuk 2.5%</small>
									@error('pajak_komisi')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
								<div class="row g-3">
									<div class="col-md-12">
										<label class="form-label" for="diskon_dari_komisi">Diskon dari Komisi (Rp) - Subsidi Agen</label>
										<input class="form-control @error('diskon_dari_komisi') is-invalid @enderror" id="diskon_dari_komisi" name="diskon_dari_komisi" type="number" step="0.01" value="{{ old('diskon_dari_komisi', 0) }}" />
										<small class="text-muted">Potong komisi sendiri untuk diskon ke nasabah. Kosongkan atau isi 0 jika tidak ada subsidi.</small>
										@error('diskon_dari_komisi')
											<div class="invalid-feedback">{{ $message }}</div>
										@enderror
									</div>
								</div>
							</div>

							{{-- SECTION: SUBSIDI & TAGIHAN --}}
							<h6 class="mt-3">Subsidi & Tagihan</h6>

						</div>
						<div class="card-footer	">
							<a href="{{ route('cards.index') }}" class="btn btn-light">Kembali</a>
							<button class="btn btn-primary float-end" type="submit">Simpan</button>
						</div>
				</div>
				</form>
			</div>
		</div>
	</div>

	@push('css')
		<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.css') }}">
	@endpush

	@push('scripts')
		<script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
		<script src="{{ asset('assets/js/form-validation.js') }}"></script>
		<script src="{{ asset('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
		<script src="{{ asset('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
		<script src="{{ asset('assets/js/datepicker/date-picker/datepicker.custom.js') }}"></script>
		<script src="{{ asset('assets/js/datepicker/date-time-picker/moment.min.js') }}"></script>
		<script src="{{ asset('assets/js/datepicker/date-time-picker/tempusdominus-bootstrap-4.min.js') }}"></script>
		<script src="{{ asset('assets/js/datepicker/date-time-picker/datetimepicker.custom.js') }}"></script>

		<script>
			initializeFormValidation({
				formsSelector: '.needs-validation'
			});
			(function($) {
            "use strict";
            $(".basic-single-select2").select2({
				placeholder: "Pilih ",
				allowClear: true
			});
			})(jQuery);
		</script>
	@endpush

@endsection