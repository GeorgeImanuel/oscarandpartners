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
	                                    <th>No Reg</th>
	                                    <th>No Polis</th>
	                                    <th>Nama</th>
	                                    <th>Tagihan Netto</th>
	                                    <th>Komisi Bersih</th>
	                                    <th>Status</th>
										<th>Aksi</th>
	                                </tr>
	                            </thead>
								<tbody>
									<tr>
	                                    <td>01</td>
	                                    <td>02</td>
	                                    <td>coba</td>
	                                    <td>1111</td>
	                                    <td>333</td>
	                                    <td>byr</td>
										<td>done</td>
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