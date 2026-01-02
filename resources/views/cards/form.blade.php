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
										<option value=""></option>										
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

								<div class="col-md-12" id="parent_card_container" style="display:none;">
								<label class="form-label" for="parent_card_id">Kartu Produksi Sebelumnya</label>
								<select class="form-control basic-single-select2 @error('parent_card_id') is-invalid @enderror" id="parent_card_id" name="parent_card_id">									
									<option value=""></option>									
									@foreach($parentCards as $parentCard)
										<option value="{{ $parentCard->id }}" {{ isset($card) ? ($card->parent_card_id == $parentCard->id ? 'selected' : '') : (old('parent_card_id') == $parentCard->id ? 'selected' : '') }}>
											{{ $parentCard->policy->policy_number }} - {{ $parentCard->policy->object->name	 }}
										</option>
									@endforeach
								</select>
									@error('parent_card_id')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>

								<div class="col-md-12">
									<label class="form-label" for="client_id">Client</label>
									<select class="form-control basic-single-select2 @error('client_id') is-invalid @enderror" id="client_id" name="client_id" required>									
										<option value=""></option>										
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
									<select class="form-control basic-single-select2 @error('policy_id') is-invalid @enderror" id="policy_id" name="policy_id" required>									<option value=""></option>										@foreach($policies as $policy)
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

							{{-- Dynamic Policy Details Component --}}
							<div class="col-md-12 mt-3 mb-3">
								@livewire('policy-details', ['policyId' => isset($card) ? $card->policy_id : old('policy_id')])
							</div>

							{{-- SECTION: HARGA PERTANGGUNGAN --}}
							{{-- <h6 class="mt-3">Harga Pertanggungan</h6> --}}
							<div id="policy-prices" class="row g-3">
								<div class="col-md-12 input-group-solid">
									<label class="form-label" for="object_value">Harga Pertanggungan (Rp)</label>
									<input class="form-control @error('object_value') is-invalid @enderror" id="object_value" name="object_value" type="number" step="0.01" value="{{ isset($card) ? $card->object_value : old('object_value') }}" required readonly/>
									@error('object_value')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>

							</div>

							{{-- SECTION: PERHITUNGAN PREMI --}}
							<h6 class="mt-3 mb-3">Parameter Premi</h6>

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
									<small class="text-muted">Contoh: 0.03 untuk 0.03%</small>
									@error('commision_percentages')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
								<div class="col-md-6">
									<label class="form-label" for="discount">Diskon (Rp)</label>
									<input class="form-control @error('discount') is-invalid @enderror" id="discount" name="discount" type="number" step="0.01" value="{{ isset($card) ? $card->discount : old('discount', 0) }}" />
									<small class="text-muted">Masukkan nilai diskon dalam rupiah</small>
									@error('discount')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>
								
							</div>

							{{-- SECTION: PERHITUNGAN KOMISI --}}
							<h6 class="mt-3">Perhitungan Komisi</h6>
							<div class="row g-3 input-group-solid">
								<div class="col-md-6">
									<label class="form-label" for="production_value">Nilai Produksi (Rp)</label>
									<input class="form-control @error('production_value') is-invalid @enderror" id="production_value" name="production_value" type="number" step="0.01" value="{{ isset($card) ? $card->production_value : old('production_value') }}" readonly />
									@error('production_value')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>

								<div class="col-md-6">
									<label class="form-label" for="premi">Total Premi (Rp)</label>
									<input class="form-control @error('premi') is-invalid @enderror" id="premi" name="premi" type="number" step="0.01" value="{{ isset($card) ? $card->premi : old('premi') }}" readonly />
									@error('premi')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>

								<div class="col-md-6">
									<label class="form-label" for="commision_bruto">Komisi Awal (Rp)</label>
									<input class="form-control @error('commision_bruto') is-invalid @enderror" id="commision_bruto" name="commision_bruto" type="number" step="0.01" value="{{ isset($card) ? $card->commision_bruto : old('commision_bruto') }}" readonly />
									@error('commision_bruto')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>

								<div class="col-md-6">
									<label class="form-label" for="commision_netto">Komisi Bersih (Rp)</label>
									<input class="form-control @error('commision_netto') is-invalid @enderror" id="commision_netto" name="commision_netto" type="number" step="0.01" value="{{ isset($card) ? $card->commision_netto : old('commision_netto') }}" readonly />
									@error('commision_netto')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>

								<div class="col-md-6">
									<label class="form-label" for="tax">Nilai Pajak (Rp)</label>
									<input class="form-control @error('tax_value') is-invalid @enderror" id="tax" name="tax" type="number" step="0.01" value="{{ isset($card) ? $card->tax : old('tax_value') }}" readonly />
									@error('tax_value')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
								</div>

								<div class="col-md-6">
									<label class="form-label" for="insurance_pay">Bayar Asuransi (Rp)</label>
									<input class="form-control @error('insurance_pay') is-invalid @enderror" id="insurance_pay" name="insurance_pay" type="number" step="0.01" value="{{ isset($card) ? $card->insurance_pay : old('insurance_pay') }}" readonly />
									@error('insurance_pay')
										<div class="invalid-feedback">{{ $message }}</div>
									@enderror
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
		<script src="{{ asset('assets/js/formula.js') }}"></script>
		{{-- <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
		<script src="{{ asset('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
		<script src="{{ asset('assets/js/datepicker/date-picker/datepicker.custom.js') }}"></script>
		<script src="{{ asset('assets/js/datepicker/date-time-picker/moment.min.js') }}"></script>
		<script src="{{ asset('assets/js/datepicker/date-time-picker/tempusdominus-bootstrap-4.min.js') }}"></script>
		<script src="{{ asset('assets/js/datepicker/date-time-picker/datetimepicker.custom.js') }}"></script> --}}

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

				// Toggle parent_card_id based on card_status_id
				function toggleParentCard() {
					var cardStatusId = $('#card_status_id').val();
					var parentContainer = $('#parent_card_container');
					var parentSelect = $('#parent_card_id');

					if (cardStatusId == '2') {
						parentContainer.show();
						parentSelect.attr('required', true);
					} else {
						parentContainer.hide();
						parentSelect.removeAttr('required');
						parentSelect.val('').trigger('change'); // Clear selection
					}
				}

				// Initialize on page load
				toggleParentCard();

				// Listen to card_status_id changes
				$('#card_status_id').on('change', toggleParentCard);

				// Listen to policy_id dropdown change and update Livewire component
				$('#policy_id').on('change', function() {
					var policyId = $(this).val();
					// Find the Livewire component and update policyId
					var livewireComponent = Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id'));
					if (livewireComponent) {
						livewireComponent.set('policyId', policyId);
					}
				});
			})(jQuery);

			// Listen for policyLoaded event from Livewire (outside jQuery scope)
			document.addEventListener('livewire:init', () => {
				Livewire.on('policyLoaded', (event) => {
					const objectValue = event.objectValue || event[0]?.objectValue || 0;
					console.log('Object value received:', objectValue);
					if (objectValue > 0) {
						$('#object_value').val(objectValue);
					}
				});
			});

			function update_payment(){
				var object_value         = parseFloat($('#object_value').val()) || 0;
				var premi_percentage     = parseFloat($('#premi_percentage').val()) || 0;
				var policy_fee           = parseFloat($('#policy_fee').val()) || 0;
				var commision_percentage = parseFloat($('#commision_percentage').val()) || 0;
				var tax_percentage       = parseFloat($('#tax_percentage').val()) || 0;
				var discount             = parseFloat($('#discount').val()) || 0;

				const productionValue = production_value(object_value, premi_percentage);
				const premiValue      = premi_value(productionValue, policy_fee);
				const commisionBruto  = commision_bruto(productionValue, commision_percentage);
				const taxValue        = tax_value(commisionBruto, tax_percentage);
				const commisionNetto  = commision_netto(commisionBruto, taxValue, discount);
				const clientPay       = client_pay(premiValue, discount);
				const insurancePay    = insurance_pay(premiValue, taxValue, discount);

				$('#production_value').val(productionValue.toFixed(2));
				$('#premi').val(premiValue.toFixed(2));
				$('#commision_bruto').val(commisionBruto.toFixed(2));			
				$('#commision_netto').val(commisionNetto.toFixed(2));
				$('#tax_value').val(taxValue.toFixed(2));
				$('#insurance_pay').val(insurancePay.toFixed(2));
			}
		</script>
	@endpush

@endsection