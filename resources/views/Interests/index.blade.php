@extends('layouts.master')

@section('title')Objek Interest
@endsection

@push('css')
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
@endpush

@section('content')
	@component('components.breadcrumb')
	@slot('breadcrumb_title')
	<h3>Daftar Objek Interest</h3>
	@endslot
	<li class="breadcrumb-item">Interest</li>
	<li class="breadcrumb-item active">Daftar</li>
	@endcomponent

	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="card">
					<div class="card-header pb-0">
						<div class="d-flex justify-content-between align-items-center">
							<h5>Objek Interest</h5>
							<a href="{{ route('interests.create') }}" class="btn btn-primary">
								<i class="fa fa-plus"></i> Tambah Interest
							</a>
						</div>
					</div>
					<div class="card-body">
						@if(session('success'))
							<div class="alert alert-success alert-dismissible fade show" role="alert">
								{{ session('success') }}
								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>
						@endif

						@if(session('error'))
							<div class="alert alert-danger alert-dismissible fade show" role="alert">
								{{ session('error') }}
								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>
						@endif

						<div class="table-responsive">
							<table class="display" id="basic-1">
								<thead>
									<tr>
										<th>No</th>
										<th>Nama Interest</th>
										<th>Actions</th>
									</tr>
								</thead>
								<tbody>
									@forelse($interests as $index => $interest)
										<tr>
											<td>{{ $index + 1 }}</td>
											<td>{{ $interest->name }}</td>
											<td>
												<div style="white-space: nowrap;">
													<a href="{{ route('interests.edit', $interest->id) }}" class="btn btn-warning btn-sm me-2" title="Edit">
														<i class="fa fa-edit"></i> <span class="fw-medium">Edit</span>
													</a>
													<form action="{{ route('interests.destroy', $interest->id) }}" method="POST" style="display: inline">
														@csrf
														@method('DELETE')
														<button type="button" class="btn btn-danger btn-sm" onClick="confirmDelete(this)" title="Hapus">
															<i class="fa fa-trash"></i> <span class="fw-medium">Delete</span>
														</button>
													</form>
												</div>
											</td>
										</tr>
									@empty
										<tr>
											<td colspan="3" class="text-center">Tidak ada data interest</td>
										</tr>
									@endforelse
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@push('scripts')
	<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
	<script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
	<script>
		function confirmDelete(button) {
			swal({
				title: "Apakah Anda yakin?",
				text: "Data interest yang dihapus tidak dapat dikembalikan!",
				icon: "warning",
				buttons: {
					cancel: {
						text: "Batal",
						value: null,
						visible: true,
						className: "",
						closeModal: true,
					},
					confirm: {
						text: "Ya, Hapus!",
						value: true,
						visible: true,
						className: "btn-danger",
						closeModal: true
					}
				},
				dangerMode: true,
			})
			.then((willDelete) => {
				if (willDelete) {
					button.closest('form').submit();
				}
			});
		}
	</script>
@endpush
