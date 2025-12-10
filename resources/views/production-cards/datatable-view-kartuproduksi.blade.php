@extends('layouts.master')

@section('title')Basic Init

@endsection

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
@endpush

@section('content')
	@component('components.breadcrumb')
		@slot('breadcrumb_title')
			<h3>Daftar kartu Produksi</h3>
		@endslot
		<li class="breadcrumb-item active">Daftar Tabel Kartu Produksi</li>
	@endcomponent
	
	<div class="container-fluid">
	    <div class="row">
	        <!-- Zero Configuration  Starts-->
	        <div class="col-sm-12">
	            <div class="card">
	                <div class="card-header">
	                    <h5>Kartu Produksi</h5>
	                </div>
	                <div class="card-body">
	                    <div class="table-responsive">
	                        <table class="display" id="basic-1">
	                            <thead>
	                                <tr>
	                                    <th>ID</th>
	                                    <th>Client ID</th>
	                                    <th>Policy ID</th>
	                                    <th>Card Status ID</th>
	                                    <th>Created By</th>
	                                    <th>Start Date</th>
										<th>End Date</th>
										<th>Created At</th>
										<th>Updated At</th>
	                                </tr>
	                            </thead>
								<tbody>
									<tr>
	                                    <td>22</td>
	                                    <td>02</td>
	                                    <td>coba</td>
	                                    <td>1111</td>
	                                    <td>333</td>
	                                    <td>byr</td>
										<td>done</td>
										<td>13/12/2025 12:39</td>
										<td>13/01/2026 10:07</td>
	                                </tr>
								</tbody>
	                        </table>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>

	
	@push('scripts')
	<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
	@endpush

@endsection