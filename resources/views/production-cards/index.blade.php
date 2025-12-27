@extends('layouts.master')

@section('title')Kartu Produksi
@endsection

@push('css')
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
@endpush

@section('content')
	@component('components.breadcrumb')
	@slot('breadcrumb_title')
	<h3>Daftar Kartu Produksi</h3>
	@endslot
	<li class="breadcrumb-item">Kartu Produksi</li>
	<li class="breadcrumb-item active">Daftar</li>
	@endcomponent

	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="card">
					<div class="card-header pb-0">
						<div class="d-flex justify-content-between align-items-center">
							<h5>Kartu Produksi</h5>
							<a href="{{ route('production-cards.create') }}" class="btn btn-primary">
								<i class="fa fa-plus"></i> Tambah Kartu Produksi
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
	                                    <th>Client</th>
	                                    <th>Polis</th>
	                                    <th>Status</th>
	                                    <th>Tanggal Awal</th>
										<th>Tanggal Akhir</th>
										<th>Premi Bruto</th>
										<th>Status Pembayaran</th>
										<th>Actions</th>
									</tr>
								</thead>
								<tbody>
									@forelse($cards as $index => $card)
										<tr>
											<td>{{ $index + 1 }}</td>
											<td>{{ $card->client->name ?? '-' }}</td>
											<td>{{ $card->policy->policy_number ?? '-' }}</td>
											<td>
												<span class="badge" style="background-color: {{ $card->cardStatus->color ?? '#gray' }}">
													{{ $card->cardStatus->name ?? '-' }}
												</span>
											</td>
											<td>{{ $card->start_date ? $card->start_date->format('d/m/Y') : '-' }}</td>
											<td>{{ $card->end_date ? $card->end_date->format('d/m/Y') : '-' }}</td>
											<td>{{ numberFormat($card->premium_gross) }}</td>
											<td>
												@if($card->insurancePaymentStatus)
													<span class="badge" style="background-color: {{ $card->insurancePaymentStatus->color }}">
														{{ $card->insurancePaymentStatus->name }}
													</span>
												@else
													-
												@endif
											</td>
											<td>
												<div style="white-space: nowrap;">
													<a href="{{ route('production-cards.show', $card->id) }}" class="btn btn-info btn-sm me-1" title="Detail">
														<i class="fa fa-eye"></i> Detail
													</a>
													<a href="{{ route('production-cards.edit', $card->id) }}" class="btn btn-warning btn-sm me-1" title="Edit">
														<i class="fa fa-edit"></i> Edit
													</a>
													<form action="{{ route('production-cards.destroy', $card->id) }}" method="POST" style="display: inline">
														@csrf
														@method('DELETE')
														<button type="button" class="btn btn-danger btn-sm" onClick="confirmDelete(this)">Delete</button>
													</form>
												</div>
											</td>
										</tr>
									@empty
										<tr>
											<td colspan="9" class="text-center">Tidak ada data</td>
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
	@push('scripts')
		<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
		<script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
	@endpush

@endsection