@extends('layouts.master')

@section('title')Detail Objek Asuransi
@endsection

@push('css')
@endpush

@section('content')
	@component('components.breadcrumb')
	@slot('breadcrumb_title')
	<h3>Detail Objek Asuransi</h3>
	@endslot
	<li class="breadcrumb-item"><a href="{{ route('objects.index') }}">Objek</a></li>
	<li class="breadcrumb-item active">Detail</li>
	@endcomponent

	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="card">
					<div class="card-header pb-0">
						<div class="d-flex justify-content-between align-items-center">
							<h5>Informasi Objek</h5>
							<div>
								<a href="{{ route('objects.edit', $object->id) }}" class="btn btn-warning btn-sm">
									<i class="fa fa-edit"></i> Edit
								</a>
								<a href="{{ route('objects.index') }}" class="btn btn-secondary btn-sm">
									<i class="fa fa-arrow-left"></i> Kembali
								</a>
							</div>
						</div>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-6">
								<table class="table table-borderless">
									<tbody>
										<tr>
											<th>Tipe Objek</th>
											<td>{{ $object->objectType->name ?? '-' }}</td>
										</tr>
										<tr>
											<th>Nama Objek</th>
											<td>{{ $object->name }}</td>
										</tr>
										<tr>
											<th>Alamat</th>
											<td>{{ $object->address }}</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>

				<!-- Parts Section -->
				<div class="card">
					<div class="card-header pb-0">
						<h5>Komponen Objek (Parts)</h5>
					</div>
					<div class="card-body">
						@if($object->objectParts && $object->objectParts->count() > 0)
							<div class="table-responsive">
								<table class="table table-bordered">
									<thead>
										<tr>
											<th width="50">No</th>
											<th>Nama Part</th>
											<th width="200">Nilai (Rp)</th>
										</tr>
									</thead>
									<tbody>
										@php $totalValue = 0; @endphp
										@foreach($object->objectParts as $index => $objectPart)
											@php $totalValue += $objectPart->value; @endphp
											<tr>
												<td>{{ $index + 1 }}</td>
												<td>{{ $objectPart->part->name ?? '-' }}</td>
												<td class="text-end">{{ number_format($objectPart->value, 2, ',', '.') }}</td>
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
