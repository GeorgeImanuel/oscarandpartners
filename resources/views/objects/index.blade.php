@extends('layouts.master')

@section('title')Objek Asuransi
@endsection

@push('css')
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
@endpush

@section('content')
	@component('components.breadcrumb')
	@slot('breadcrumb_title')
	<h3>Daftar Objek Asuransi</h3>
	@endslot
	<li class="breadcrumb-item">Objek</li>
	<li class="breadcrumb-item active">Daftar</li>
	@endcomponent

	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="card">
					<div class="card-header pb-0">
						<div class="d-flex justify-content-between align-items-center">
							<h5>Objek Asuransi</h5>
							<a href="{{ route('objects.create') }}" class="btn btn-primary">
								<i class="icon-plus me-2"></i>Tambah Objek
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
										<th>Objek Occupation</th>
										<th>Nama</th>
										<th>Keterangan</th>
										<th>Actions</th>
									</tr>
								</thead>
								<tbody>
									@forelse($objects as $index => $object)
										<tr>
											<td>{{ $index + 1 }}</td>
											<td>{{ $object->objectOccupation->name ?? '-' }}</td>
											<td>{{ $object->name }}</td>
											<td>{{ $object->keterangan }}</td>
											<td>
												<div style="white-space: nowrap;">
													<a href="{{ route('objects.show', $object->id) }}" class="btn btn-info btn-sm me-2" title="Detail">
														<i class="fa fa-eye"></i> <span class="fw-medium"><i class="icon-eye me-2"></i>Detail</span>
													</a>
													<a href="{{ route('objects.edit', $object->id) }}" class="btn btn-warning btn-sm me-2" title="Edit">
														<i class="fa fa-edit"></i> <span class="fw-medium"><i class="icon-pencil me-2"></i>Edit</span>
													</a>
													<form action="{{ route('objects.destroy', $object->id) }}" method="POST" style="display: inline">
														@csrf
														@method('DELETE')
														<button type="button" class="btn btn-danger btn-xs" onClick="confirmDelete(this)" title="Hapus">
															<i class="fa fa-trash"></i> <span class="fw-medium"><i class="icon-trash me-2"></i>Delete</span>
														</button>
													</form>
												</div>
											</td>
										</tr>
									@empty
										<tr>
											<td colspan="5" class="text-center">Tidak ada data objek</td>
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
				text: "Data objek akan dihapus permanen!",
				icon: "warning",
				buttons: ["Batal", "Hapus"],
				dangerMode: true,
			}).then((willDelete) => {
				if (willDelete) {
					button.closest('form').submit();
				}
			});
		}
	</script>
@endpush
