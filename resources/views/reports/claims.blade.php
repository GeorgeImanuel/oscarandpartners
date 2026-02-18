@extends('layouts.master')

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <h3>Laporan Klaim Tahunan</h3>
        @endslot
        <li class="breadcrumb-item">Laporan Klaim</a></li>
    @endcomponent

<style>
    /* CSS PRINT (Sama persis dengan referensi kamu) */
    @media print {
        @page { size: landscape; margin: 10mm; }
        table { table-layout: fixed !important; width: 100% !important; font-size: 11px !important; }
        body * { visibility: hidden; }
        #printable-area, #printable-area * { visibility: visible; }
        #printable-area { position: absolute; left: 0; top: 0; width: 100%; }
        th, td { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; padding: 5px !important; }
        .card { border: none !important; box-shadow: none !important; }
        
        /* Sembunyikan elemen non-cetak */
        .no-print { display: none !important; }
    }
</style>

<div class="container-fluid">
    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">Rekapitulasi Klaim (Yearly)</h3>
        </div>
        <div class="card-body">
            
            {{-- FILTER TAHUN (Cukup 1 dropdown) --}}
            <div class="row mb-4 no-print">
                <div class="col-md-3">
                    <label>Pilih Tahun</label>
                    <select id="year_filter" class="form-control">
                        @for($i = date('Y'); $i >= 2020; $i--)
                            <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end gap-2">
                    <button onclick="loadReport()" class="btn btn-primary me-2">
                        <i class="fas fa-search"></i> Tampilkan
                    </button>
                    <button onclick="printTable()" class="btn btn-success">
                        <i class="fas fa-print"></i> Print Tabel
                    </button>
                </div>
            </div>

            {{-- TABEL LAPORAN --}}
            <div id="printable-area" class="table-responsive">
                <div class="d-none d-print-block text-center mb-3">
                    <h3>Laporan Rekapitulasi Klaim Tahun <span id="label_year_print"></span></h3>
                </div>

                <table class="table table-bordered table-striped text-center table-sm" style="font-size: 13px;">
                    <thead class="bg-light font-weight-bold">
                        <tr>
                            <th class="align-middle" style="width: 15%">Bulan</th>
                            <th class="align-middle" style="width: 10%">Jumlah Kasus</th>
                            <th class="align-middle" style="width: 25%">Total Estimasi (Casco + TJH)</th>
                            <th class="align-middle" style="width: 25%">Total Realisasi Bayar</th>
                            <th class="align-middle" style="width: 25%">Outstanding / Sisa</th>
                        </tr>
                    </thead>
                    <tbody id="report-body">
                        <tr><td colspan="5" class="text-center">Silakan klik tombol Tampilkan</td></tr>
                    </tbody>
                    <tfoot id="report-footer" class="font-weight-bold bg-secondary text-white" style="font-size: 13px;">
                        {{-- Footer diisi via JS --}}
                    </tfoot>
                </table>
                
                {{-- Tanda Tangan (Opsional, biasanya diminta di laporan print) --}}
                <div class="d-none d-print-block mt-5">
                    <div class="row">
                        <div class="col-4 text-center">
                            <p>Dibuat Oleh,</p>
                            <br><br><br>
                            <p>( Staff Klaim )</p>
                        </div>
                        <div class="col-4 offset-4 text-center">
                            <p>Mengetahui,</p>
                            <br><br><br>
                            <p>( Kepala Bagian )</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- SCRIPT --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        loadReport(); 
    });

    function formatRupiah(angka) {
        if (!angka) return '0';
        return new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 }).format(angka);
    }

    function loadReport() {
        let year = document.getElementById('year_filter').value;
        
        // Update Label Tahun untuk Print
        let labelPrint = document.getElementById('label_year_print');
        if(labelPrint) labelPrint.innerText = year;

        let tbody = document.getElementById('report-body');
        let tfoot = document.getElementById('report-footer');
        
        tbody.innerHTML = '<tr><td colspan="5" class="text-center py-5">Loading data...</td></tr>';
        tfoot.innerHTML = ''; 

        // Hit ke Route Controller
        fetch(`/reports/claims/data?year=${year}`)
            .then(response => response.json())
            .then(res => {
                if(res.status) {
                    renderTable(res.data);
                } else {
                    tbody.innerHTML = `<tr><td colspan="5" class="text-center text-danger">Gagal memuat data</td></tr>`;
                }
            })
            .catch(err => {
                console.error(err);
                tbody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Terjadi kesalahan sistem.</td></tr>';
            });
    }

    function renderTable(data) {
        let tbody = document.getElementById('report-body');
        let html = '';

        // LOOP 12 BULAN
        data.rows.forEach(row => {
            html += `
                <tr>
                    <td class="text-left font-weight-bold">${row.month_name}</td>
                    <td>${row.total_kasus}</td>
                    <td class="text-right" style="padding-right: 20px;">Rp ${formatRupiah(row.total_estimasi)}</td>
                    <td class="text-right" style="padding-right: 20px;">Rp ${formatRupiah(row.total_bayar)}</td>
                    <td class="text-right" style="padding-right: 20px;">Rp ${formatRupiah(row.outstanding)}</td>
                </tr>
            `;
        });
        tbody.innerHTML = html;

        // FOOTER (TOTAL)
        let footerHtml = `
            <tr>
                <td class="text-center">TOTAL TAHUNAN</td>
                <td class="text-center">${formatRupiah(data.footer.grand_kasus)}</td>
                <td class="text-right" style="padding-right: 20px;">Rp ${formatRupiah(data.footer.grand_estimasi)}</td>
                <td class="text-right" style="padding-right: 20px;">Rp ${formatRupiah(data.footer.grand_bayar)}</td>
                <td class="text-right" style="padding-right: 20px;">Rp ${formatRupiah(data.footer.grand_outstanding)}</td>
            </tr>
        `;
        document.getElementById('report-footer').innerHTML = footerHtml;
    }

    function printTable() {
        // Simpan value select box
        let valYear = document.getElementById('year_filter').value;

        // Clone area print
        let printElement = document.getElementById('printable-area').cloneNode(true);
        let table = printElement.querySelector('table');

        // Suntik Colgroup agar tabel rapi saat diprint (Total 5 Kolom)
        // 100% dibagi 5 tidak rata, kita atur manual biar cantik
        let colgroup = document.createElement('colgroup');
        let widths = ['15%', '10%', '25%', '25%', '25%']; // Total 100%
        
        widths.forEach(w => {
            let col = document.createElement('col');
            col.style.width = w;
            colgroup.appendChild(col);
        });
        
        table.insertBefore(colgroup, table.firstChild);

        // Swap body
        let originalContents = document.body.innerHTML;
        document.body.innerHTML = printElement.outerHTML;

        // Print
        window.print();

        // Restore body & values
        document.body.innerHTML = originalContents;
        document.getElementById('year_filter').value = valYear;
        // Re-bind event listener karena DOM di-reset (opsional, tapi aman dilakukan)
        document.getElementById('year_filter').addEventListener('change', () => {}); 
    }
</script>
@endsection