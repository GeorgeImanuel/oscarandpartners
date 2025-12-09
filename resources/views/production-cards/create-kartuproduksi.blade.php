@extends('layouts.master')

@section('title')Validation Forms
@endsection

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/date-picker.css') }}">
@endpush

@section('content')
	@component('components.breadcrumb')
		@slot('breadcrumb_title')
			<h3>Create Forms</h3>
		@endslot
		<li class="breadcrumb-item">Forms</li>
		<li class="breadcrumb-item">Form Controls</li>
        <li class="breadcrumb-item active">Validation Forms</li>
	@endcomponent
	
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="card">
					<div class="card-header pb-0">
						<h5>Create Kartu Produksi</h5>
					</div>
					<div class="card-body">
						<form class="needs-validation" novalidate="">
							<div class="row g-3">
								<div class="col-md-4">
									<label class="form-label" for="validationCustom01">ID</label>
									<input class="form-control" id="validationCustom01" type="text" value="" required="" />
									<div class="invalid-feedback">Please provide a valid ID.</div>
								</div>
								<div class="col-md-4">
									<label class="form-label" for="validationCustom02">Client ID</label>
									<input class="form-control" id="validationCustom01" type="text" value="" required="" />
									<div class="invalid-feedback">Please provide a valid ID.</div>
								</div>
								<div class="col-md-4">
									<label class="form-label" for="validationCustom02">Polis ID</label>
									<input class="form-control" id="validationCustom01" type="text" value="" required="" />
									<div class="invalid-feedback">Please provide a valid ID.</div>
								</div>
							</div><br>
							<div class="row g-3">
								<div class="col-md-4">
									<label class="form-label" for="validationCustom01">Status Kartu ID</label>
									<input class="form-control" id="validationCustom01" type="text" value="" required="" />
									<div class="invalid-feedback">Please provide a valid ID.</div>
								</div>
								<div class="col-md-4">
									<label class="form-label" for="validationCustom02">Parent Card ID</label>
									<input class="form-control" id="validationCustom01" type="text" value="" required="" />
									<div class="invalid-feedback">Please provide a valid ID.</div>
								</div>
							</div><br>
							<div class="row g-3">
								<div class="col-md-4">
									<label class="form-label" for="validationCustom01">Created by</label>
									<input class="form-control" id="validationCustom01" type="text" value="" required="" />
									<div class="invalid-feedback">Please provide a valid data.</div>
								</div>
							</div><br>
							<div class="row g-3">
								<div class="col-md-4">
									<label class="form-label" for="validationCustom01">Deadline Pay</label>
									<input class="form-control" id="validationCustom01" type="text" value="" required="" />
									<div class="invalid-feedback">Please provide a valid data.</div>
								</div>
							</div><br>
							<div class="row g-3">
								<div class="col-md-4">
									<label class="form-label" for="validationCustom01">Started Date</label>
									<input class="datepicker-here form-control digits" type="text" data-language="en"/><i class="fa fa-calendar"></i>
									<div class="invalid-feedback">Please provide a valid ID.</div>
								</div>
								<div class="col-md-4">
									<label class="form-label" for="validationCustom01">Ended Date</label>
									<input class="datepicker-here form-control digits" type="text" data-language="en"/><i class="fa fa-calendar"></i>
									<div class="invalid-feedback">Please provide a valid ID.</div>
								</div>
							</div><br>
							<div class="row g-3">
								<div class="col-md-4">
									<label class="form-label" for="validationCustom01">Commission Precentage</label>
									<input class="form-control" id="validationCustom01" type="text" value="" required="" />
									<div class="invalid-feedback">Please provide a valid ID.</div>
								</div>
								<div class="col-md-4">
									<label class="form-label" for="validationCustom02">Tax Precentage</label>
									<input class="form-control" id="validationCustom01" type="text" value="" required="" />
									<div class="invalid-feedback">Please provide a valid ID.</div>
								</div>
							</div><br>
							<div class="row g-3">
								<div class="col-md-4">
									<label class="form-label" for="validationCustom01">Premium Gross</label>
									<input class="form-control" id="validationCustom01" type="text" value="" required="" />
									<div class="invalid-feedback">Please provide a valid data.</div>
								</div>
							</div><br>
							<div class="row g-3">
								<div class="col-md-4">
									<label class="form-label" for="validationCustom01">Discount</label>
									<input class="form-control" id="validationCustom01" type="text" value="" required="" />
									<div class="invalid-feedback">Please provide a valid data.</div>
								</div>
							</div><br>
							<div class="row g-3">
								<div class="col-md-4">
									<label class="form-label" for="validationCustom01">Admin Fee</label>
									<input class="form-control" id="validationCustom01" type="text" value="" required="" />
									<div class="invalid-feedback">Please provide a valid data.</div>
								</div>
							</div><br>
							<div class="row g-3">
								<div class="col-md-4">
									<label class="form-label" for="validationCustom01">Commission Pct</label>
									<input class="form-control" id="validationCustom01" type="text" value="" required="" />
									<div class="invalid-feedback">Please provide a valid data.</div>
								</div>
							</div><br>
							<div class="row g-3">
								<div class="col-md-4">
									<label class="form-label" for="validationCustom01">Commission Amount</label>
									<input class="form-control" id="validationCustom01" type="text" value="" required="" />
									<div class="invalid-feedback">Please provide a valid data.</div>
								</div>
							</div><br>
							<div class="row g-3">
								<div class="col-md-4">
									<label class="form-label" for="validationCustom01">Net to Insurance</label>
									<input class="form-control" id="validationCustom01" type="text" value="" required="" />
									<div class="invalid-feedback">Please provide a valid data.</div>
								</div>
							</div><br>
							<div class="row g-3">
								<div class="col-md-4">
									<label class="form-label" for="validationCustom01">Premi Amount</label>
									<input class="form-control" id="validationCustom01" type="text" value="" required="" />
									<div class="invalid-feedback">Please provide a valid ID.</div>
								</div>
								<div class="col-md-4">
									<label class="form-label" for="validationCustom02">Polis Amount</label>
									<input class="form-control" id="validationCustom01" type="text" value="" required="" />
									<div class="invalid-feedback">Please provide a valid ID.</div>
								</div>
								<div class="col-md-4">
									<label class="form-label" for="validationCustom02">Premi Total</label>
									<input class="form-control" id="validationCustom01" type="text" value="" required="" />
									<div class="invalid-feedback">Please provide a valid ID.</div>
								</div>
							</div><br>
							<div class="row g-3">
								<div class="col-md-4">
									<label class="form-label" for="validationCustom01">Commission Amount/Komisi Awal</label>
									<input class="form-control" id="validationCustom01" type="text" value="" required="" />
									<div class="invalid-feedback">Please provide a valid ID.</div>
								</div>
								<div class="col-md-4">
									<label class="form-label" for="validationCustom02">Tax Amount/Pajak Awal</label>
									<input class="form-control" id="validationCustom01" type="text" value="" required="" />
									<div class="invalid-feedback">Please provide a valid ID.</div>
								</div>
							</div><br>
							<div class="row g-3">
								<div class="col-md-4">
									<label class="form-label" for="validationCustom01">Discount Amount/Komisi Diskon</label>
									<input class="form-control" id="validationCustom01" type="text" value="" required="" />
									<div class="invalid-feedback">Please provide a valid ID.</div>
								</div>
								<div class="col-md-4">
									<label class="form-label" for="validationCustom02">Commission Total/Komisi Total</label>
									<input class="form-control" id="validationCustom01" type="text" value="" required="" />
									<div class="invalid-feedback">Please provide a valid ID.</div>
								</div>
							</div><br>
							<div class="row g-3">
								<div class="col-md-4">
									<label class="form-label" for="validationCustom01">Insurance Payment Status ID</label>
									<input class="form-control" id="validationCustom01" type="text" value="" required="" />
									<div class="invalid-feedback">Please provide a valid data.</div>
								</div>
							</div><br>
							<div class="row g-3">
								<div class="col-md-4">
									<label class="form-label" for="validationCustom01">Insurance Paid At</label>
									<div class="input-group date" id="dt-minimum" data-target-input="nearest">
                          				<input class="form-control datetimepicker-input digits" type="text" data-target="#dt-minimum">
                          				<div class="input-group-text" data-target="#dt-minimum" data-toggle="datetimepicker"><i class="fa fa-calendar"> </i></div>
                        			</div>
									<div class="invalid-feedback">Please provide a valid data.</div>
								</div>
							</div><br>
							<div class="row g-3">
								<div class="col-md-4">
									<label class="form-label" for="validationCustom01">Insurance Evident Number</label>
									<input class="form-control" id="validationCustom01" type="text" value="" required="" />
									<div class="invalid-feedback">Please provide a valid data.</div>
								</div>
							</div><br>
							<div class="row g-3">
								<div class="col-md-4">
									<label class="form-label" for="validationCustom01">Payment Type ID</label>
									<input class="form-control" id="validationCustom01" type="text" value="" required="" />
									<div class="invalid-feedback">Please provide a valid data.</div>
								</div>
							</div><br>
							<div class="row g-3">
								<div class="col-md-4">
									<label class="form-label" for="validationCustom01">Created At</label>
									<div class="input-group date" id="dt-minimum" data-target-input="nearest">
                          				<input class="form-control datetimepicker-input digits" type="text" data-target="#dt-minimum">
                          				<div class="input-group-text" data-target="#dt-minimum" data-toggle="datetimepicker"><i class="fa fa-calendar"> </i></div>
                        			</div>
									<div class="invalid-feedback">Please provide a valid data.</div>
								</div>
							</div><br>
							<div class="row g-3">
								<div class="col-md-4">
									<label class="form-label" for="validationCustom01">Updated At</label>
									<div class="input-group date" id="dt-minimum" data-target-input="nearest">
                          				<input class="form-control datetimepicker-input digits" type="text" data-target="#dt-minimum">
                          				<div class="input-group-text" data-target="#dt-minimum" data-toggle="datetimepicker"><i class="fa fa-calendar"> </i></div>
                        			</div>
									<div class="invalid-feedback">Please provide a valid data.</div>
								</div>
							</div><br>
							<div class="mb-3">
								<div class="form-check">
								</div>
							</div>
							<button class="btn btn-primary" type="submit">Edit</button>
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
	@endpush

@endsection