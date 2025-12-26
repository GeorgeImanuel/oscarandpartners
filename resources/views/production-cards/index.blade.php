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
	                        <a href="{{ route('cards.create') }}" class="btn btn-primary">
	                            <i class="fa fa-plus"></i> Create
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
										<td>Rp {{ number_format($card->premium_gross, 0, ',', '.') }}</td>
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
												<a href="{{ route('cards.detail', $card->id) }}" 
												   class="btn btn-info btn-xs me-1" 
												   style="padding: 2px 8px; font-size: 11px; display: inline-block;"
												   title="Detail">
													<i class="fa fa-eye"></i> Detail
												</a>
												<a href="{{ route('cards.preview', $card->id) }}" 
												   class="btn btn-success btn-xs me-1" 
												   style="padding: 2px 8px; font-size: 11px; display: inline-block;"
												   title="Preview Kartu">
													<i class="fa fa-file-text"></i> Preview
												</a>
												<a href="{{ route('cards.edit', $card->id) }}" 
												   class="btn btn-warning btn-xs me-1" 
												   style="padding: 2px 8px; font-size: 11px; display: inline-block;"
												   title="Edit">
													<i class="fa fa-edit"></i> Edit
												</a>
												<button type="button" 
														class="btn btn-danger btn-xs" 
														style="padding: 2px 8px; font-size: 11px; display: inline-block;"
														title="Delete"
														onclick="deleteCard({{ $card->id }})">
													<i class="fa fa-trash"></i> Delete
												</button>
											</div>
											<form id="delete-form-{{ $card->id }}" 
												  action="{{ route('cards.destroy', $card->id) }}" 
												  method="POST" 
												  style="display: none;">
												@csrf
												@method('DELETE')
											</form>
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
	<script>
		function deleteCard(id) {
			if (confirm('Yakin ingin menghapus kartu produksi ini?')) {
				document.getElementById('delete-form-' + id).submit();
			}
		}
	</script>
	@endpush

@endsection