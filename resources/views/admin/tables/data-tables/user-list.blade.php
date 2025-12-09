@extends('layouts.master')

@section('title')User List
@endsection

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
@endpush

@section('content')
	@component('components.breadcrumb')
		@slot('breadcrumb_title')
			<h3>User List</h3>
		@endslot
		<li class="breadcrumb-item">Tables</li>
		<li class="breadcrumb-item">Data Tables</li>
		<li class="breadcrumb-item active">Users</li>
	@endcomponent
	
	<div class="container-fluid">
	    <div class="row">
	        <!-- User List Table Starts-->
	        <div class="col-sm-12">
	            <div class="card">
	                <div class="card-header">
	                    <h5>User Data Table</h5>
	                    <span>List of all system users (Admin and Finance roles only).</span>
	                </div>
	                <div class="card-body">
	                    <div class="table-responsive">
	                        <table class="display" id="basic-1">
	                            <thead>
	                                <tr>
	                                    <th>Username</th>
	                                    <th>Full Name</th>
	                                    <th>Email</th>
	                                    <th>Role</th>
	                                    <th>Phone</th>
	                                    <th>Status</th>
	                                </tr>
	                            </thead>
	                            <tbody>
	                                <tr>
	                                    <td>admin001</td>
	                                    <td>Ahmad Hidayat</td>
	                                    <td>ahmad.hidayat@company.com</td>
	                                    <td><span class="badge badge-primary">ADMIN</span></td>
	                                    <td>08123456001</td>
	                                    <td><span class="badge badge-success">Active</span></td>
	                                </tr>
	                                <tr>
	                                    <td>admin002</td>
	                                    <td>Siti Rahmawati</td>
	                                    <td>siti.rahmawati@company.com</td>
	                                    <td><span class="badge badge-primary">ADMIN</span></td>
	                                    <td>08123456002</td>
	                                    <td><span class="badge badge-success">Active</span></td>
	                                </tr>
	                                <tr>
	                                    <td>finance001</td>
	                                    <td>Bambang Suryanto</td>
	                                    <td>bambang.suryanto@company.com</td>
	                                    <td><span class="badge badge-info">FINANCE</span></td>
	                                    <td>08123456003</td>
	                                    <td><span class="badge badge-success">Active</span></td>
	                                </tr>
	                                <tr>
	                                    <td>admin003</td>
	                                    <td>Dewi Lestari</td>
	                                    <td>dewi.lestari@company.com</td>
	                                    <td><span class="badge badge-primary">ADMIN</span></td>
	                                    <td>08123456004</td>
	                                    <td><span class="badge badge-success">Active</span></td>
	                                </tr>
	                                <tr>
	                                    <td>finance002</td>
	                                    <td>Eko Prasetyo</td>
	                                    <td>eko.prasetyo@company.com</td>
	                                    <td><span class="badge badge-info">FINANCE</span></td>
	                                    <td>08123456005</td>
	                                    <td><span class="badge badge-success">Active</span></td>
	                                </tr>
	                                <tr>
	                                    <td>admin004</td>
	                                    <td>Rina Wijayanti</td>
	                                    <td>rina.wijayanti@company.com</td>
	                                    <td><span class="badge badge-primary">ADMIN</span></td>
	                                    <td>08123456006</td>
	                                    <td><span class="badge badge-success">Active</span></td>
	                                </tr>
	                                <tr>
	                                    <td>finance003</td>
	                                    <td>Maya Sari</td>
	                                    <td>maya.sari@company.com</td>
	                                    <td><span class="badge badge-info">FINANCE</span></td>
	                                    <td>08123456008</td>
	                                    <td><span class="badge badge-success">Active</span></td>
	                                </tr>
	                                <tr>
	                                    <td>finance004</td>
	                                    <td>Nina Rahayu</td>
	                                    <td>nina.rahayu@company.com</td>
	                                    <td><span class="badge badge-info">FINANCE</span></td>
	                                    <td>08123456010</td>
	                                    <td><span class="badge badge-success">Active</span></td>
	                                </tr>
	                            </tbody>
	                            <tfoot>
	                                <tr>
	                                    <th>Username</th>
	                                    <th>Full Name</th>
	                                    <th>Email</th>
	                                    <th>Role</th>
	                                    <th>Phone</th>
	                                    <th>Status</th>
	                                </tr>
	                            </tfoot>
	                        </table>
	                    </div>
	                </div>
	            </div>
	        </div>
	        <!-- User List Table Ends-->
	    </div>
	</div>

	
	@push('scripts')
	<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
	@endpush

@endsection
