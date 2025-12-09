@extends('layouts.master')

@section('title')Client List
@endsection

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
@endpush

@section('content')
	@component('components.breadcrumb')
		@slot('breadcrumb_title')
			<h3>Client List</h3>
		@endslot
		<li class="breadcrumb-item">Tables</li>
		<li class="breadcrumb-item">Data Tables</li>
		<li class="breadcrumb-item active">Clients</li>
	@endcomponent
	
	<div class="container-fluid">
	    <div class="row">
	        <!-- Client List Table Starts-->
	        <div class="col-sm-12">
	            <div class="card">
	                <div class="card-header">
	                    <h5>Client Data Table</h5>
	                    <span>List of all registered clients with their contact information and family group.</span>
	                </div>
	                <div class="card-body">
	                    <div class="table-responsive">
	                        <table class="display" id="basic-1">
	                            <thead>
	                                <tr>
	                                    <th>Name</th>
	                                    <th>Phone</th>
	                                    <th>Email</th>
	                                    <th>ID Card</th>
	                                    <th>Group/Family</th>
	                                    <th>Address</th>
	                                </tr>
	                            </thead>
	                            <tbody>
	                                <tr>
	                                    <td>Budi Santoso</td>
	                                    <td>08123456789</td>
	                                    <td>budi@example.com</td>
	                                    <td>3174012345670001</td>
	                                    <td>REL-001 - Keluarga Budi</td>
	                                    <td>Jl. Contoh No. 123, Jakarta</td>
	                                </tr>
	                                <tr>
	                                    <td>Siti Nurhaliza</td>
	                                    <td>08129876543</td>
	                                    <td>siti@example.com</td>
	                                    <td>3273012345670002</td>
	                                    <td>REL-001 - Keluarga Budi</td>
	                                    <td>Jl. Raya No. 456, Bandung</td>
	                                </tr>
	                                <tr>
	                                    <td>Ahmad Wijaya</td>
	                                    <td>08567891234</td>
	                                    <td>ahmad@example.com</td>
	                                    <td>3578012345670003</td>
	                                    <td>REL-002 - Keluarga Ahmad</td>
	                                    <td>Jl. Merdeka No. 789, Surabaya</td>
	                                </tr>
	                                <tr>
	                                    <td>Dewi Kusuma</td>
	                                    <td>08198765432</td>
	                                    <td>dewi@example.com</td>
	                                    <td>3374012345670004</td>
	                                    <td>REL-002 - Keluarga Ahmad</td>
	                                    <td>Jl. Sudirman No. 321, Semarang</td>
	                                </tr>
	                            </tbody>
	                            <tfoot>
	                                <tr>
	                                    <th>Name</th>
	                                    <th>Phone</th>
	                                    <th>Email</th>
	                                    <th>ID Card</th>
	                                    <th>Group/Family</th>
	                                    <th>Address</th>
	                                </tr>
	                            </tfoot>
	                        </table>
	                    </div>
	                </div>
	            </div>
	        </div>
	        <!-- Client List Table Ends-->
	    </div>
	</div>

	
	@push('scripts')
	<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
	@endpush

@endsection
