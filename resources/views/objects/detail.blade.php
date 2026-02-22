@extends('layouts.master')

@section('title')Detail Obyek Asuransi
@endsection

@push('css')
@endpush

@section('content')
	<div class="d-flex justify-content-between align-items-center">
		@component('components.breadcrumb')
			@slot('breadcrumb_title')
			<h3>Detail Obyek Asuransi</h3>
			@endslot
			<li class="breadcrumb-item"><a href="{{ route('objects.index') }}">Obyek</a></li>
			<li class="breadcrumb-item active">Detail</li>
		@endcomponent
		<div class="d-flex gap-2">
			<a href="{{ route('objects.index') }}" class="btn btn-info btn-sm">
				<i class="fa fa-arrow-left"></i> Kembali
			</a>
			<a href="{{ route('objects.edit', $object->id) }}" class="btn btn-warning btn-sm">
				<i class="fa fa-edit"></i> Edit
			</a>
		</div>
	</div>

	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="card">
					<div class="card-header pb-0">
						<div class="d-flex justify-content-between align-items-center">
							<h5>Informasi Obyek</h5>
						</div>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-6">
								<table class="table table-borderless">
									<tbody>
										<tr>
											<th>Obyek Okupasi</th>
											<td>{{ $object->objectOccupation->name ?? '-' }}</td>
										</tr>
										<tr>
											<th>Nama Obyek</th>
											<td>{{ $object->name }}</td>
										</tr>
										<tr>
                                            <th>Nilai TJH</th>
                                            <td class="fw-bold">Rp {{ number_format($object->tjh ?? 0, 2, ',', '.') }}</td>
                                        </tr>
										<tr>
											<th>Keterangan</th>
											<td>{{ $object->keterangan }}</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>

				<!-- Interests Section -->
				<div class="card">
					<div class="card-header pb-0">
						<h5>Komponen Obyek (Interests)</h5>
					</div>
					<div class="card-body">
					@if($object->objectInterests && $object->objectInterests->count() > 0)
						<div class="table-responsive">
							<table class="table table-bordered">
								<thead>
									<tr>
										<th width="50">No</th>
										<th>Nama Interest</th>
										<th width="200">Nilai</th>
									</tr>
								</thead>
								<tbody>
									@php $totalValue = 0; @endphp
									@foreach($object->objectInterests as $index => $objectInterest)
										@php $totalValue += $objectInterest->value; @endphp
										<tr>
											<td>{{ $index + 1 }}</td>
											<td>{{ $objectInterest->interest->name ?? '-' }}</td>
											<td class="text-end">{{ number_format($objectInterest->value, 2, ',', '.') }}</td>
											</tr>
										@endforeach
									</tbody>
									<tfoot>
										<tr class="table-primary">
											<th colspan="2" class="text-end">Total Nilai Objek:</th>
											<th class="text-end">{{ number_format($totalValue, 2, ',', '.') }}</th>
										</tr>
									</tfoot>
								</table>
							</div>
						@else
							<div class="alert alert-info">
								<i class="fa fa-info-circle"></i> Belum ada komponen part untuk objek ini
							</div>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@push('scripts')
@endpush
