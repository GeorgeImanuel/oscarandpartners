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
											{{ $object->name ?? 'No Name' }}
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
                                        <th>No</th>
                                        <th>Nomor Polis</th> <th>Nama Objek</th>  <th>Alamat</th>      <th>Client</th>
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
                                            
                                            {{-- Data Nomor Polis --}}
                                            <td>
                                                <div class="fw-bold">{{ $card->policy->policy_number ?? '-' }}</div>
                                            </td>

                                            {{-- Data Nama Objek --}}
                                            <td>{{ $card->policy->object->name ?? '-' }}</td>

                                            {{-- Data Alamat --}}
                                            <td>{{ $card->policy->object->address ?? '-' }}</td>

                                            <td>{{ $card->client->user->name ?? $card->client->id_name ?? '-' }}</td>
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
                                                    <a href="{{ route('cards.show', $card->id) }}" class="btn btn-info btn-sm me-1" title="Detail">
                                                        <i class="fa fa-eye"></i> Detail
                                                    </a>
                                                    <a href="{{ route('cards.edit', $card->id) }}" class="btn btn-warning btn-sm me-1" title="Edit">
                                                        <i class="fa fa-edit"></i> Edit
                                                    </a>
                                                    <form action="{{ route('cards.destroy', $card->id) }}" method="POST" style="display: inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-danger btn-sm" onClick="confirmDelete(this)">Delete</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            {{-- Colspan diupdate jadi 11 karena jumlah kolom bertambah --}}
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
