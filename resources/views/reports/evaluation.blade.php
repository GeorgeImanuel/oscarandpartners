@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">Evaluasi Produksi (Year on Year)</h3>
        </div>
        <div class="card-body">
            
            {{-- FILTER TAHUN --}}
            <div class="row mb-4">
                <div class="col-md-3">
                    <label>Tahun Berjalan (Current)</label>
                    <select id="year_current" class="form-control">
                        @for($i = date('Y'); $i >= 2020; $i--)
                            <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Tahun Pembanding (Compare)</label>
                    <select id="year_compare" class="form-control">
                        @for($i = date('Y'); $i >= 2020; $i--)
                            <option value="{{ $i }}" {{ $i == (date('Y')-1) ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button onclick="loadReport()" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Tampilkan
                    </button>
                </div>
            </div>

            {{-- TABEL LAPORAN --}}
            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center table-sm" style="font-size: 12px;">
                    <thead class="bg-light font-weight-bold">
                        {{-- Header Baris 1 --}}
                        <tr>
                            <th rowspan="2" class="align-middle">Bulan</th>
                            <th colspan="2" class="align-middle">Premi</th>
                            <th colspan="2" class="align-middle">Komisi</th>
                            <th colspan="3" class="align-middle" style="background-color: #e2e3e5">Akumulasi Premi</th>
                            <th colspan="3" class="align-middle" style="background-color: #d1ecf1">Akumulasi Komisi</th>
                        </tr>
                        {{-- Header Baris 2 (Tahun dinamis via JS) --}}
                        <tr>
                            {{-- Premi --}}
                            <th class="th-year-compare">2024</th>
                            <th class="th-year-current">2025</th>
                            {{-- Komisi --}}
                            <th class="th-year-compare">2024</th>
                            <th class="th-year-current">2025</th>
                            
                            {{-- Akumulasi Premi --}}
                            <th class="th-year-compare" style="background-color: #e2e3e5">2024</th>
                            <th class="th-year-current" style="background-color: #e2e3e5">2025</th>
                            <th style="background-color: #e2e3e5">% Growth</th>

                            {{-- Akumulasi Komisi --}}
                            <th class="th-year-compare" style="background-color: #d1ecf1">2024</th>
                            <th class="th-year-current" style="background-color: #d1ecf1">2025</th>
                            <th style="background-color: #d1ecf1">% Growth</th>
                        </tr>
                    </thead>
                    <tbody id="report-body">
                        <tr><td colspan="12" class="text-center">Silakan klik tombol Tampilkan</td></tr>
                    </tbody>
                    <tfoot id="report-footer" class="font-weight-bold bg-secondary text-white" style="font-size: 13px;">
                        {{-- Footer diisi via JS --}}
                    </tfoot>
                </table>
            </div>

        </div>
    </div>
</div>

{{-- SCRIPT --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        loadReport(); // Load otomatis saat buka halaman
    });

    function formatRupiah(angka) {
        // Pastikan angka valid, jika null/undefined jadi 0
        if (!angka) return '0';
        return new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 }).format(angka);
    }

    function loadReport() {
        let yearCurrent = document.getElementById('year_current').value;
        let yearCompare = document.getElementById('year_compare').value;

        // Update Header Tahun di Tabel
        document.querySelectorAll('.th-year-current').forEach(el => el.innerText = yearCurrent);
        document.querySelectorAll('.th-year-compare').forEach(el => el.innerText = yearCompare);

        // Loading State
        let tbody = document.getElementById('report-body');
        let tfoot = document.getElementById('report-footer');
        
        tbody.innerHTML = '<tr><td colspan="12" class="text-center py-5">Loading data...</td></tr>';
        tfoot.innerHTML = ''; // Kosongkan footer saat loading

        // Panggil Route Web (bukan /api/ jika rutenya ada di web.php)
        fetch(`/reports/evaluation/data?year_current=${yearCurrent}&year_compare=${yearCompare}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(res => {
                if(res.status) {
                    renderTable(res.data);
                } else {
                    tbody.innerHTML = `<tr><td colspan="12" class="text-center text-danger">${res.message || 'Gagal memuat data'}</td></tr>`;
                }
            })
            .catch(err => {
                console.error(err);
                tbody.innerHTML = '<tr><td colspan="12" class="text-center text-danger">Terjadi kesalahan saat memuat data.</td></tr>';
            });
    }

    function renderTable(data) {
        let tbody = document.getElementById('report-body');
        let html = '';

        // LOOP 12 BULAN (rows)
        data.rows.forEach(row => {
            // Tentukan warna growth (Hijau kalau >= 0, Merah kalau < 0)
            let colorPremi = parseFloat(row.growth_premi_val) >= 0 ? 'text-success' : 'text-danger';
            let colorKomisi = parseFloat(row.growth_komisi_val) >= 0 ? 'text-success' : 'text-danger';

            html += `
                <tr>
                    <td class="text-left font-weight-bold">${row.month_name}</td>
                    
                    {{-- Premi Bulanan --}}
                    <td>${formatRupiah(row.premi_compare)}</td>
                    <td>${formatRupiah(row.premi_current)}</td>
                    
                    {{-- Komisi Bulanan --}}
                    <td>${formatRupiah(row.komisi_compare)}</td>
                    <td>${formatRupiah(row.komisi_current)}</td>
                    
                    {{-- Akumulasi Premi --}}
                    <td style="background-color: #f8f9fa">${formatRupiah(row.accumulated_premi_compare)}</td>
                    <td style="background-color: #f8f9fa">${formatRupiah(row.accumulated_premi_current)}</td>
                    <td class="${colorPremi} font-weight-bold" style="background-color: #f8f9fa">${row.growth_premi}</td>
                    
                    {{-- Akumulasi Komisi --}}
                    <td style="background-color: #eafcfc">${formatRupiah(row.accumulated_komisi_compare)}</td>
                    <td style="background-color: #eafcfc">${formatRupiah(row.accumulated_komisi_current)}</td>
                    <td class="${colorKomisi} font-weight-bold" style="background-color: #eafcfc">${row.growth_komisi}</td>
                </tr>
            `;
        });
        tbody.innerHTML = html;

        // RENDER FOOTER (TOTAL)
        // Cek warna growth total
        let totalGrowthPremiVal = parseFloat(data.footer.total_growth_premi.replace('%',''));
        let totalGrowthKomisiVal = parseFloat(data.footer.total_growth_komisi.replace('%',''));

        let colorTotalPremi = totalGrowthPremiVal >= 0 ? 'text-white' : 'text-danger bg-warning'; 
        let colorTotalKomisi = totalGrowthKomisiVal >= 0 ? 'text-white' : 'text-danger bg-warning';

        let footerHtml = `
            <tr>
                <td class="text-left">TOTAL</td>
                
                {{-- Total Premi Bulanan (Sebenarnya sama dengan Akumulasi akhir tahun) --}}
                <td colspan="2" class="text-center bg-dark text-white">
                    Current: Rp ${formatRupiah(data.footer.total_premi_current)}
                </td>
                
                {{-- Total Komisi Bulanan --}}
                <td colspan="2" class="text-center bg-dark text-white">
                    Current: Rp ${formatRupiah(data.footer.total_komisi_current)}
                </td>
                
                {{-- Total Akumulasi Premi --}}
                <td>Rp ${formatRupiah(data.footer.total_premi_compare)}</td>
                <td>Rp ${formatRupiah(data.footer.total_premi_current)}</td>
                <td class="${colorTotalPremi}">${data.footer.total_growth_premi}</td>

                 {{-- Total Akumulasi Komisi --}}
                <td>Rp ${formatRupiah(data.footer.total_komisi_compare)}</td>
                <td>Rp ${formatRupiah(data.footer.total_komisi_current)}</td>
                <td class="${colorTotalKomisi}">${data.footer.total_growth_komisi}</td>
            </tr>
        `;
        document.getElementById('report-footer').innerHTML = footerHtml;
    }
</script>
@endsection