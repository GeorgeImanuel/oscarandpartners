@extends('layouts.master')

@section('title')Payments
@endsection

@push('css')
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
@endpush

@section('content')
	@component('components.breadcrumb')
	@slot('breadcrumb_title')
	<h3>Daftar Pembayaran</h3>
	@endslot
	<li class="breadcrumb-item">Pembayaran</li>
	<li class="breadcrumb-item active">Daftar</li>
	@endcomponent

	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table class="display" id="basic-1">
								<thead>
									<tr>
										<th>Periode</th>
	                                    <th>No</th>
	                                    <th>No Polis</th>
	                                    <th>Nama Tertanggung</th>
	                                    <th>Letak Resiko</th>
										<th>TSI</th>
										<th>Keterangan</th>
                                        <th>Jumlah Payment</th>
                                        <th>Tipe Pembayaran</th>
										<th>Actions</th>
									</tr>
								</thead>
								<tbody>
									@forelse($payments as $payment)
										<tr>
                                            <td>{{ $payment->deadline_at ? $payment->deadline_at->format('d-m-Y') : '-' }}</td>
											<td>{{ $loop->iteration }}</td>
											<td>{{ $payment->card?->policy?->policy_number ?? '-' }}</td>
											<td>{{ $payment->insured_name }}</td>
											<td></td>
											<td></td>
                                            <td>{{ $payment->note ?? '-' }}</td>
											<td>{{ number_format($payment->amount ?? 0) }}</td>
											<td>{{ $payment->clientPaymentStatus?->name ?? '-' }}</td>
                                            <td>
												<div style="white-space: nowrap;">
													<a href="{{ route('payments.show', $payment->id) }}" class="btn btn-info btn-sm me-1" title="Detail">
														<i class="fa fa-eye"></i> Detail
													</a>
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
		<script>
			$(document).ready(function() {
				function buildFilterUrl() {
					var params = [];
					var clientId = $('#clientFilter').val();
					var statusId = $('#statusFilter').val();
					
					if (clientId) {
						params.push('client_id=' + clientId);
					}
					if (statusId) {
						params.push('card_status_id=' + statusId);
					}
					
					var url = "{{ route('cards.index') }}";
					if (params.length > 0) {
						url += '?' + params.join('&');
					}
					return url;
				}
				
				$('#clientFilter').on('change', function() {
					window.location.href = buildFilterUrl();
				});
				
				$('#statusFilter').on('change', function() {
					window.location.href = buildFilterUrl();
				});
			});
		</script>
	@endpush

@endsection