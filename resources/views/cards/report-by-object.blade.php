@extends('layouts.master')

@section('title')Laporan Kartu Produksi Per Objek
@endsection

@push('css')
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
@endpush

@section('content')
	@component('components.breadcrumb')
	@slot('breadcrumb_title')
	<h3>Laporan Kartu Produksi Per Objek</h3>
	@endslot
	<li class="breadcrumb-item">Kartu Produksi</li>
	<li class="breadcrumb-item active">Laporan Per Objek</li>
	@endcomponent

	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="card">
					<div class="card-header pb-0">
						<div class="d-flex justify-content-between align-items-center mb-3">
							<div class="d-flex gap-2 align-items-center">
								<label for="objectFilter" class="mb-0 me-2">Filter Objek:</label>
								<select id="objectFilter" class="form-select" style="width: 250px;">
									<option value="">Semua Objek</option>
									@forelse($objects as $object)
										<option value="{{ $object->id }}" {{ request('object_id') == $object->id ? 'selected' : '' }}>
											{{ $object->name }} - {{ $object->keterangan }}
										</option>
									@empty
										<option value="" disabled>Tidak ada data objek</option>
									@endforelse
								</select>
							</div>
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

						<div class="table-responsive" style="overflow-x: auto;">
                            <table class="display" id="basic-1">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">No</th>
                                        <th style="min-width: 120px;">Nomor Polis</th> 
                                        <th style="min-width: 150px;">Nama Tertanggung</th>  
                                        <th style="min-width: 300px;">Keterangan</th>      
                                        <th style="min-width: 120px;">Client</th>
                                        <th>Status</th>
                                        <th style="min-width: 100px;">Tanggal Awal</th>
                                        <th style="min-width: 100px;">Tanggal Akhir</th>
                                        <th style="min-width: 100px;">Premi Bruto</th>
                                        <th>Status Pembayaran</th>
                                        <th style="min-width: 150px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($cards as $index => $card)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            
                                            {{-- Data Nomor Polis --}}
                                            <td>
                                                <div class="fw-bold">{{ $card->policy->policy_number ?? '-' }}</div>
                                            </td>

                                            {{-- Data Nama Objek --}}
                                            <td>{{ $card->policy->object->name ?? '-' }}</td>

                                            {{-- Data Alamat --}}
                                            <td>{{ $card->policy->object->keterangan ?? '-' }}</td>

                                            <td>{{ $card->client->user->name ?? $card->client->id_name ?? '-' }}</td>
                                            <td>
                                                <span class="badge" style="background-color: {{ $card->cardStatus->color ?? '#gray' }}">
                                                    {{ $card->cardStatus->name ?? '-' }}
                                                </span>
                                            </td>
                                            <td data-order="{{ $card->policy->started_date ? $card->policy->started_date->format('Y-m-d') : '' }}">{{ $card->policy->started_date ? $card->policy->started_date->format('d M Y') : '-' }}</td>
                                            <td data-order="{{ $card->policy->ended_date ? $card->policy->ended_date->format('Y-m-d') : '' }}">{{ $card->policy->ended_date ? $card->policy->ended_date->format('d M Y') : '-' }}</td>
                                            
                                            <td class="text-end">
                                                @php
                                                    $curr = $card->policy->currency_code ?? 'IDR';
                                                @endphp
                                                {{ $curr }} {{ number_format($card->premi_value, $curr == 'IDR' ? 0 : 2, ',', '.') }}
                                            </td>
                                            
                                            <td>
                                                @php
                                                    $latestPayment = $card->payments->sortByDesc('created_at')->first();
                                                    $status = $latestPayment ? $latestPayment->paymentStatus : null;
                                                @endphp

                                                <span class="badge" style="background-color: {{ $status->color ?? '#cccccc' }}">
                                                    {{ $status->name ?? 'Belum Bayar' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div style="white-space: nowrap;">
                                                    <a href="{{ route('cards.show', $card->id) }}" class="btn btn-info btn-sm me-1" title="Detail">
                                                        <i class="icon-eye me-2"></i>Detail
                                                    </a>
                                                    <form action="{{ route('cards.destroy', $card->id) }}" method="POST" style="display: inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-danger btn-xs" onClick="confirmDelete(this)"><i class="icon-trash me-2"></i>Delete</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="11" class="text-center">Tidak ada data</td>
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
				$('#objectFilter').on('change', function() {
					var objectId = $(this).val();
					var url = "{{ route('cards.reportByObject') }}";
					if (objectId) {
						url += '?object_id=' + objectId;
					}
					window.location.href = url;
				});
			});
		</script>
	@endpush

@endsection
