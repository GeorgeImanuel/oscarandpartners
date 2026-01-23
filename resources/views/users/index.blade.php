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
	                    <div class="card-header-right">
	                        <a href="{{ route('users.create') }}" class="btn btn-primary">Add User</a>
	                    </div>
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
	                                    <th>Status</th>
	                                    <th>Actions</th>
	                                </tr>
	                            </thead>
	                            <tbody>
	                                @forelse($users as $user)
	                                <tr>
	                                    <td>{{ $user->username }}</td>
	                                    <td>{{ $user->name }}</td>
	                                    <td>{{ $user->email }}</td>
	                                    <td>
	                                        @if($user->hasRole('admin'))
	                                            <span class="badge badge-primary">ADMIN</span>
	                                        @elseif($user->hasRole('finance'))
	                                            <span class="badge badge-info">FINANCE</span>
	                                        @endif
	                                    </td>
	                                    <td>
	                                        @if($user->is_active)
	                                            <span class="badge badge-success">Active</span>
	                                        @else
	                                            <span class="badge badge-warning">Inactive</span>
	                                        @endif
	                                    </td>
	                                    <td>
	                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning"><i class="icon-pencil me-2"></i>Edit</a>
	                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline-block;">
	                                            @csrf
	                                            @method('DELETE')
	                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?')"><i class="icon-trash me-2"></i>Delete</button>
	                                        </form>
	                                    </td>
	                                </tr>
	                                @empty
	                                <tr>
	                                    <td colspan="6" class="text-center">No users found</td>
	                                </tr>
	                                @endforelse
	                            </tbody>
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
