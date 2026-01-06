@extends('layouts.master')

@section('title')Laporan Kartu Produksi Per Relasi
@endsection

@push('css')
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
	<style>
		@media print {
			.no-print { display: none; }
			.card { box-shadow: none; border: none; }
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
							<button onclick="window.print()" class="btn btn-primary btn-sm">
								<i class="fa fa-print"></i> Print
							</button>
						</div>
					</div>
					<div class="card-body">
						@if(session('success'))
							<div class="alert alert-success alert-dismissible fade show no-print" role="alert">
								{{ session('success') }}
								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>
						@endif

						@forelse($objectsByRelation as $relationId => $objects)
							@php
								// Cari nama relasi dari ID (cast ke int untuk matching)
								$relationName = $relations->firstWhere('id', (int)$relationId)?->name ?? ($relationId == 0 ? 'Tanpa Relasi' : 'Relasi ID: ' . $relationId);
							@endphp
							<div class="mb-5">
								<h4 class="mb-3">{{ $relationName }}</h4>
								<div class="table-responsive" style="overflow-x: auto;">
									<table class="table table-bordered">
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
														<div class="fw-bold">{{ $object->name ?? '-' }}</div>
														<small class="text-muted">{{ $object->address ?? '-' }}</small>
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
													<td style="white-space: nowrap; vertical-align: top;">
														@if($object->objectPart->isNotEmpty())
															<div style="display: table; width: 100%;">
																{{-- Loop Item --}}
																@foreach($object->objectPart as $part)
																	<div style="display: table-row;">
																		<div style="display: table-cell; text-align: left; padding-bottom: 4px;">
																			{{ $part->part->name }}:
																		</div>
																		<div style="display: table-cell; text-align: right; padding-bottom: 4px; padding-left: 20px;">
																			{{ numberFormat($part->value) }}
																		</div>
																	</div>
																@endforeach
															</div>

															<div style="border-top: 2px solid #000; margin: 8px 0; width: 100%;"></div>

															{{-- Bagian Total --}}
															<div style="display: table; width: 100%;">
																<div style="display: table-row;">
																	<div style="display: table-cell; text-align: right; font-weight: bold; padding-right: 10px;">
																		Total :
																	</div>
																	<div style="display: table-cell; text-align: right; font-weight: bold; padding-left: 20px;">
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
					</div>
				</div>
			</div>
		</div>
	</div>

	@push('scripts')
		<script>
			$(document).ready(function() {
				function buildFilterUrl() {
					var params = [];
					var clientId = $('#clientFilter').val();
					var relationId = $('#relationFilter').val();
					
					if (clientId) {
						params.push('client_id=' + clientId);
					}
					if (relationId) {
						params.push('relation_id=' + relationId);
					}
					
					var url = "{{ route('cards.reportByRelation') }}";
					if (params.length > 0) {
						url += '?' + params.join('&');
					}
					return url;
				}
				
				$('#clientFilter').on('change', function() {
					window.location.href = buildFilterUrl();
				});
				
				$('#relationFilter').on('change', function() {
					window.location.href = buildFilterUrl();
				});
			});
		</script>
	@endpush
@endsection
