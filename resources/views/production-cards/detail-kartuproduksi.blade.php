@extends('layouts.master')

@section('title')Sample Page

@endsection

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
        <h3>Sample Page</h3>
        @endslot
        <li class="breadcrumb-item">Pages</li>
        <li class="breadcrumb-item active">Sample Page</li>
    @endcomponent
  
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>Detail Kartu Produksi</h5>
                    </div>
                    <div class="card-body">
                        <p>REG. NO :</p>
                        <p>BLN :</p>
                        <p>Jenis :</p>
                        <p>1. No. Polis/Asuransi :</p>
                        <p>2. Asuransi :</p>
                        <p>3. Nama Tertanggung :</p>
                        <p>4. Obyek Asuransi :</p>
                        <p>5. Periode Pertanggungan :</p>
                        <p>6. Harga Pertanggungan :</p>
                        <p>7. Premi :</p>
                        <div class="table-responsive">
	                        <table class="table">
	                            <thead>
								    <tr>
									    <th scope="col">Tarif Premi</th>
									    <th scope="col">TJH</th>
									    <th scope="col">Disc</th>
								    </tr>
							    </thead>
							    <tbody>
								    <tr>
									    <td></td>
									    <td>Rp</td>
									    <td>Rp</td>
								    </tr>
								</tbody>
	                        </table>
	                    </div>
                        <div class="table-responsive">
	                        <table class="table">
	                            <thead>
								    <tr>
									    <th scope="col">Produksi</th>
									    <th scope="col">Biaya Polis</th>
									    <th scope="col">Total</th>
								    </tr>
							    </thead>
							    <tbody>
								    <tr>
									    <td>Rp</td>
									    <td>Rp</td>
									    <td>Rp</td>
								    </tr>
								</tbody>
	                        </table>
	                    </div><br><br>
                        <p>8. Komisi :</p>
                        <div class="table-responsive">
	                        <table class="table">
	                            <thead>
								    <tr>
									    <th scope="col">Total</th>
									    <th scope="col">Pajak</th>
									    <th scope="col">Disc</th>
                                        <th scope="col">Komisi Bersih</th>
								    </tr>
							    </thead>
							    <tbody>
								    <tr>
									    <td>Rp</td>
									    <td>Rp</td>
									    <td></td>
                                        <td>Rp</td>
								    </tr>
								</tbody>
	                        </table>
	                    </div><br><br>
                        <p>9. Keterangan Pembayaran :</p>
                        <p><b>Tagihan ke Nasabah :</b></p>
                        <div class="table-responsive">
	                        <table class="table">
	                            <thead>
								    <tr>
									    <th scope="col">Premi Total</th>
									    <th scope="col">Discount/Agen</th>
									    <th scope="col">Netto Tagihan</th>
								    </tr>
							    </thead>
							    <tbody>
								    <tr>
									    <td>Rp</td>
									    <td>Rp</td>
                                        <th>Rp</th>
								    </tr>
								</tbody>
	                        </table>
	                    </div><br><br>
                        <p><b>Pembayaran ke Asuransi :</b></p>
                        <div class="table-responsive">
	                        <table class="table">
	                            <thead>
								    <tr>
									    <th scope="col">Premi Total</th>
									    <th scope="col">Komisi</th>
									    <th scope="col">Netto Bayar</th>
								    </tr>
							    </thead>
							    <tbody>
								    <tr>
									    <td>Rp</td>
									    <td>Rp</td>
                                        <th>Rp</th>
								    </tr>
								</tbody>
	                        </table>
	                    </div><br><br>
                        <p>10. Pelunasan Tagihan</p>
                        <p>Tanggal :</p>
                        <p>No. Bukti :</p>
                        <p>11. Lunas Komisi/Ass</p>
                        <p>Tanggal :</p>
                        <p>No. Bukti :</p><br><br><br>
                        <p>Note :</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

  
    @push('scripts')
	<script src="{{asset('assets/js/bootstrap/popper.min.js')}}"></script>
    <script src="{{asset('assets/js/bootstrap/bootstrap.min.js')}}"></script>
	@endpush

@endsection