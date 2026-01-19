@extends('layouts.master')

@section('title')Payments Overdue
@endsection

@push('css')
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
@endpush

@section('content')
	@component('components.breadcrumb')
	@slot('breadcrumb_title')
	<h3>Daftar Tunggakan (Overdue)</h3>
	@endslot
	<li class="breadcrumb-item">Pembayaran</li>
	<li class="breadcrumb-item active">Overdue</li>
	@endcomponent

	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="card">
					<div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="text-danger"><i class="fa fa-warning"></i> Data Jatuh Tempo Hari Ini: {{ date('d-m-Y') }}</h5>
						<button class="btn btn-primary" onclick="printTableOnly()"><i class="fa fa-print"></i> Print</button>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="display" id="basic-1">
								<thead>
									<tr>
										<th>Deadline</th>
	                                    <th>No</th>
	                                    <th>No Polis</th>
	                                    <th>Nama Tertanggung</th>
	                                    <th>Letak Resiko</th>
										<th>TSI</th>
										<th>Keterangan</th>
                                        <th>Jumlah Tagihan</th>
                                        <th>Tipe Pembayaran</th>
										<th>Actions</th>
									</tr>
								</thead>
								<tbody>
									@forelse($payments as $payment)
										<tr>
                                            {{-- Memberikan warna merah jika sudah lewat deadline --}}
                                            <td class="text-danger fw-bold">
                                                {{ $payment->deadline_at ? $payment->deadline_at->format('d-m-Y') : '-' }}
                                            </td>
											<td>{{ $loop->iteration }}</td>
											<td>{{ $payment->card?->policy?->policy_number ?? '-' }}</td>
											<td>{{ $payment->insured_name }}</td>
											<td>{{ $payment->card?->policy?->object?->address ?? '-' }}</td>
											<td>{{ number_format($payment->card?->object_value ?? 0, 2, ',', '.') }}</td>
                                            <td>{{ $payment->note ?? '-' }}</td>
											<td>{{ number_format($payment->amount ?? 0, 2, ',', '.') }}</td>
											<td>{{ $payment->card?->paymentType?->name ?? '-' }}</td>
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
											<td colspan="10" class="text-center">Tidak ada tunggakan pembayaran</td>
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
			function printTableOnly() {
				// 1. Ambil HTML Tabel (gunakan ID basic-1 sesuai template)
				var tableElement = document.getElementById('basic-1');
				var tableHTML = tableElement.outerHTML;
				
				// Ganti class agar sesuai CSS print manual
				tableHTML = tableHTML.replace(/class="[^"]*"/, 'class="table-manual"');

				// 2. Buat Jendela Baru (Popup)
				var win = window.open('', '', 'height=700,width=900');

				// 3. Tulis HTML Lengkap ke Jendela Baru
				win.document.write('<html><head><title>Laporan Pembayaran</title>');
				
				// Inject CSS langsung agar terbawa
				win.document.write('<style>');
				win.document.write('body { font-family: Arial, sans-serif; padding: 20px; }');
				win.document.write('.table-manual { width: 100%; border-collapse: collapse; font-size: 10px; }');
				win.document.write('.table-manual th, .table-manual td { border: 1px solid #000; padding: 4px; vertical-align: middle; }');
				win.document.write('.table-manual thead th { background-color: #d1d1d1 !important; font-weight: bold; text-align: center; -webkit-print-color-adjust: exact; }');
				win.document.write('.bg-total { background-color: #e9ecef !important; font-weight: bold; -webkit-print-color-adjust: exact; }');
				win.document.write('.text-end { text-align: right; }');
				win.document.write('.text-center { text-align: center; }');
				win.document.write('.fw-bold { font-weight: bold; }');
				win.document.write('@page { size: landscape; margin: 10mm; }');
				// Sembunyikan kolom aksi (kolom terakhir)
				win.document.write('.table-manual th:last-child, .table-manual td:last-child { display: none; }');
				win.document.write('</style>');
				win.document.write('</head><body>');

				// Header Laporan
				win.document.write('<div style="text-align:center; margin-bottom:20px;">');
				win.document.write('<h3 style="margin:0;">LAPORAN DAFTAR PEMBAYARAN</h3>');
				win.document.write('</div>');

				// Isi Tabel
				win.document.write(tableHTML);

				win.document.write('</body></html>');

				// 4. Eksekusi Print
				win.document.close();
				win.focus();
				setTimeout(function() {
					win.print();
					win.close(); // Otomatis tutup setelah print (opsional)
				}, 500);
			}

			// resources/views/payments/overdue.blade.php

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
					
					// GANTI BAGIAN INI: dari 'cards.index' atau 'cards.overdue' 
					// menjadi 'payments.overdue'
					var url = "{{ route('payments.overdue') }}"; 
					
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