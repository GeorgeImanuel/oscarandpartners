@extends('layouts.master')

@section('title')Laporan Kartu Produksi Per Relasi
@endsection

@push('css')
	<link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.1/css/buttons.bootstrap5.css">
    
    <style>
        /* Style tambahan agar tombol print terlihat rapi */
        .dt-buttons {
            margin-bottom: 15px;
        }
        @media print {
            .no-print { display: none !important; }
        }
    </style>
@endpush

@section('content')
	@component('components.breadcrumb')
	@slot('breadcrumb_title')
	<h3>Laporan Kartu Produksi Per Relasi</h3>
	@endslot
	<li class="breadcrumb-item">Kartu Produksi</li>
	<li class="breadcrumb-item active">Laporan Per Relasi</li>
	@endcomponent

	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="card">
					<div class="card-header pb-0 no-print">
						<div class="d-flex justify-content-between align-items-center mb-3">
							<div class="d-flex gap-2">
								@if(auth()->user()->hasRole(['owner', 'admin']))
									<div class="d-flex gap-2 align-items-center">
										<label for="clientFilter" class="mb-0 me-2">Filter Client:</label>
										<select id="clientFilter" class="form-select" style="width: 250px;">
											<option value="">Semua Client</option>
											@forelse($clients as $client)
												<option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
													{{ $client->user->name ?? $client->id_name ?? 'No Name' }}
												</option>
											@empty
												<option value="" disabled>Tidak ada data client</option>
											@endforelse
										</select>
									</div>
									
									<div class="d-flex gap-2 align-items-center">
										<label for="relationFilter" class="mb-0 me-2">Filter Relasi:</label>
										<select id="relationFilter" class="form-select" style="width: 250px;">
											<option value="">Semua Relasi</option>
											@forelse($relations as $relation)
												<option value="{{ $relation->id }}" {{ request('relation_id') == $relation->id ? 'selected' : '' }}>
													{{ $relation->name }}
												</option>
											@empty
												<option value="" disabled>Tidak ada data relasi</option>
											@endforelse
										</select>
									</div>
								@endif
							</div>
					</div>
					<div class="card-body">
						@if(session('success'))
							<div class="alert alert-success alert-dismissible fade show no-print" role="alert">
								{{ session('success') }}
								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>
						@endif

						@if(!request('client_id') && !request('relation_id'))
                            
                            {{-- Tampilan jika belum memilih filter --}}
                            <div class="alert alert-light text-center border py-5">
                                <i class="fa fa-filter fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Data belum ditampilkan</h5>
                                <p class="mb-0">Silakan pilih <strong>Client</strong> atau <strong>Relasi</strong> pada filter di atas untuk melihat laporan.</p>
                            </div>

                        @else
							@forelse($objectsByRelation as $relationId => $objects)
								@php
									$relationName = $relations->firstWhere('id', (int)$relationId)?->name ?? ($relationId == 0 ? 'Tanpa Relasi' : 'Relasi ID: ' . $relationId);
									$client = request('client_id') ? $clients->firstWhere('id', request('client_id')) : $objects->first()?->client;
        							$clientName = $client?->user?->name ?? $client?->id_name ?? 'No Name';
								@endphp
								<div class="mb-5">
									<h4 class="mb-3">{{ $relationName }}</h4>
									<div class="table-responsive" style="overflow-x: auto;">
										<table class="table table-bordered table-print" style="width:100%" data-title="Daftar Polis - {{ $clientName }}" data-client="{{ $clientName }}" data-relation="{{ $relationName }}">
											<thead>
												<tr>
													<th style="width: 3%;">No</th>
													<th style="width: 15%;">Nomor Polis</th>
													<th style="width: 22%;">Nama Pertanggungan<br>Alamat Risiko</th>
													<th style="width: 12%;">Objek/Okupasi</th>
													<th style="width: 8%;">Coverage</th>
													<th style="width: 12%;">Periode</th>
													<th style="width: 18%;">Total Harga Bagian</th>
												</tr>
											</thead>
											<tbody>
												@foreach($objects as $index => $object)
													@php
														$objectPartTotal = $object->objectPart->sum('value');
														$policiesCount = $object->policies->count();
													@endphp
													<tr>
														<td style="vertical-align: top;">{{ $index + 1 }}</td>
														<td style="vertical-align: top;">
															@if($policiesCount > 0)
																@foreach($object->policies as $policyIndex => $policy)
																	@php
																		$mainInsurance = $policy->mainInsurance->first();
																	@endphp
																	<div @if($policyIndex < $policiesCount - 1) class="mb-3 pb-3" style="border-bottom: 1px dashed #ddd;" @endif>
																		<div class="fw-bold">{{ $policy->policy_number ?? '-' }}</div>
																		<small class="text-muted">{{ $mainInsurance?->name ?? '-' }}</small>
																	</div>
																@endforeach
															@else
																<div>-</div>
															@endif
														</td>
														<td style="vertical-align: top;">
														{{ $object->name ?? '-' }} / {{ $object->address ?? '-' }}
														</td>
														<td style="vertical-align: top;">{{ $object->objectType?->name ?? '-' }}</td>
														<td style="vertical-align: top;">
															@if($policiesCount > 0)
																@foreach($object->policies as $policyIndex => $policy)
																	<div @if($policyIndex < $policiesCount - 1) class="mb-3 pb-3" style="border-bottom: 1px dashed #ddd;" @endif>
																		{{ $policy->coverage?->code ?? '-' }}
																	</div>
																@endforeach
															@else
																<div>-</div>
															@endif
														</td>
														<td style="vertical-align: top;">
															@if($policiesCount > 0)
																@foreach($object->policies as $policyIndex => $policy)
																	<div @if($policyIndex < $policiesCount - 1) class="mb-3 pb-3" style="border-bottom: 1px dashed #ddd;" @endif>
																		<div>{{ $policy->started_date ? $policy->started_date->format('d/m/Y') : '-' }}</div>
																		<div>{{ $policy->ended_date ? $policy->ended_date->format('d/m/Y') : '-' }}</div>
																	</div>
																@endforeach
															@else
																<div>-</div>
															@endif
														</td>
														<td style="white-space: normal; vertical-align: top; text-align: right;">
															@if($object->objectPart->isNotEmpty())
																<div style="display: table; width: 100%; font-size: 0.85em;">
																	{{-- Loop Item --}}
																	@foreach($object->objectPart as $part)
																		<div style="display: table-row;">
																			<div style="display: table-cell; text-align: left; padding-bottom: 3px; padding-right: 20px;">
																				{{ $part->part->name }}:
																			</div>
																			<div style="display: table-cell; text-align: right; padding-bottom: 3px;">
																				{{ numberFormat($part->value) }}
																			</div>
																		</div>
																	@endforeach
																</div>

																<div style="border-top: 1px solid #000; margin: 6px 0; width: 100%;"></div>

																{{-- Bagian Total --}}
																<div style="display: table; width: 100%; font-size: 0.85em;">
																	<div style="display: table-row;">
																		<div style="display: table-cell; text-align: left; padding-right: 20px;">
																			Total :
																		</div>
																		<div style="display: table-cell; text-align: right;">
																			{{ numberFormat($objectPartTotal) }}
																		</div>
																	</div>
																</div>
															@else
																<div class="text-center">-</div>
															@endif
														</td>
													</tr>
												@endforeach
											</tbody>
										</table>
									</div>
								</div>
							@empty
								<div class="alert alert-info">
									Tidak ada data objek pertanggungan
								</div>
							@endforelse
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>

	@push('scripts')
		<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
		<script src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>
		<script src="https://cdn.datatables.net/2.0.3/js/dataTables.bootstrap5.js"></script>
		
		<script src="https://cdn.datatables.net/buttons/3.0.1/js/dataTables.buttons.js"></script>
		<script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.bootstrap5.js"></script>
		<script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.print.min.js"></script>

		<script>
			$(document).ready(function() {
				function buildFilterUrl() {
					var params = [];
					var clientId = $('#clientFilter').val();
					var relationId = $('#relationFilter').val();
					
					if (clientId) params.push('client_id=' + clientId);
					if (relationId) params.push('relation_id=' + relationId);
					
					var url = "{{ route('cards.reportByRelation') }}";
					if (params.length > 0) url += '?' + params.join('&');
					return url;
				}
				
				$('#clientFilter, #relationFilter').on('change', function() {
					window.location.href = buildFilterUrl();
				});

				
				$('.table-print').each(function() {
					var clientName = $(this).data('client');
					var relationName = $(this).data('relation');
					var printTitle = $(this).data('title');

					new DataTable(this, {
						paging: false, 
						searching: false, 
						info: false,     
						
						layout: {
							topStart: {
								buttons: [
									{
										extend: 'print',
										text: '<i class="fa fa-print"></i> Print Tabel Ini',
										className: 'btn btn-primary btn-sm mb-2',
										
										title: 'Laporan Kartu Produksi - ' + clientName,
										messageTop: function() {
											return '<div style="text-align: left; margin-bottom: 15px;">' +
												'<h6 style="margin:0; font-weight:bold;">Relasi: ' + relationName + '</h6>' +
												'<small class="text-muted">Dicetak pada: ' + new Date().toLocaleString('id-ID') + '</small>' +
												'</div>';
										},

										autoPrint: true,

										exportOptions: {
											columns: ':visible',
											stripHtml: false, 
											format: {
												body: function (data, row, column, node) {
													return node.innerHTML; 
												}
											}
										},

										customize: function (win) {
											$(win.document.body).css('-webkit-print-color-adjust', 'exact');
											
											$(win.document.body).css('font-family', 'inherit'); 

											$(win.document.body).find('table')
												.addClass('table table-bordered') 
												.css('width', '100%') 
												.css('font-size', 'inherit') 
												.removeClass('dataTable'); 
											
											
										}
									}
								]
							}
						}
					});
				});
			});
		</script>
	@endpush
@endsection
