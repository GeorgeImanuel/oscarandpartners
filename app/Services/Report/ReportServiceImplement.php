<?php

namespace App\Services\Report;

use LaravelEasyRepository\Service;
use App\Repositories\Payment\PaymentRepository; 
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportServiceImplement extends Service implements ReportService
{
    protected $paymentRepository;

    public function __construct(PaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function generateEvaluationReport(int $yearCurrent, int $yearCompare): array
    {
        // 1. Ambil Data dari Repo (Sudah di-join & di-sum di query)
        $raw = $this->paymentRepository->getMonthlySumByYears([$yearCurrent, $yearCompare]);

        $dataRows = [];

        // Variable Penampung Akumulasi
        $accPremiCurrent = 0; $accPremiCompare = 0;
        $accKomisiCurrent = 0; $accKomisiCompare = 0;

        // 2. Loop 12 Bulan
        for ($m = 1; $m <= 12; $m++) {
            
            // Ambil data bulan $m tahun Current & Compare
            $rowCurrent = $raw->where('year', $yearCurrent)->where('month', $m)->first();
            $rowCompare = $raw->where('year', $yearCompare)->where('month', $m)->first();

            // Ambil value (pakai floatval biar aman dari null)
            $premiCur = floatval($rowCurrent->total_premi ?? 0);
            $premiCom = floatval($rowCompare->total_premi ?? 0);
            
            $komisiCur = floatval($rowCurrent->total_komisi ?? 0);
            $komisiCom = floatval($rowCompare->total_komisi ?? 0);

            // Hitung Akumulasi (Running Total)
            $accPremiCurrent += $premiCur;
            $accPremiCompare += $premiCom;
            $accKomisiCurrent += $komisiCur;
            $accKomisiCompare += $komisiCom;

            // Hitung Growth (Cegah Division by Zero)
            $growthPremi = ($accPremiCompare > 0) 
                ? (($accPremiCurrent - $accPremiCompare) / $accPremiCompare) * 100 
                : ($accPremiCurrent > 0 ? 100 : 0);

            $growthKomisi = ($accKomisiCompare > 0) 
                ? (($accKomisiCurrent - $accKomisiCompare) / $accKomisiCompare) * 100 
                : ($accKomisiCurrent > 0 ? 100 : 0);

            // Susun Data
            $dataRows[] = [
                'month_name' => Carbon::create()->month($m)->translatedFormat('F'),
                
                // Data Bulanan
                'premi_compare' => $premiCom,
                'premi_current' => $premiCur,
                'komisi_compare' => $komisiCom,
                'komisi_current' => $komisiCur,
                
                // Data Akumulasi
                'accumulated_premi_compare' => $accPremiCompare,
                'accumulated_premi_current' => $accPremiCurrent,
                'growth_premi' => number_format($growthPremi, 2) . '%',
                'growth_premi_val' => $growthPremi, // Untuk warna merah/hijau di frontend

                'accumulated_komisi_compare' => $accKomisiCompare,
                'accumulated_komisi_current' => $accKomisiCurrent,
                'growth_komisi' => number_format($growthKomisi, 2) . '%',
                'growth_komisi_val' => $growthKomisi,
            ];
        }

        // 3. Return Data Lengkap dengan Footer
        $totalGrowthPremi = ($accPremiCompare > 0) ? (($accPremiCurrent - $accPremiCompare)/$accPremiCompare)*100 : 0;
        $totalGrowthKomisi = ($accKomisiCompare > 0) ? (($accKomisiCurrent - $accKomisiCompare)/$accKomisiCompare)*100 : 0;

        return [
            'rows' => $dataRows,
            'footer' => [
                'total_premi_current' => $accPremiCurrent,
                'total_premi_compare' => $accPremiCompare,
                'total_komisi_current' => $accKomisiCurrent,
                'total_komisi_compare' => $accKomisiCompare,
                'total_growth_premi' => number_format($totalGrowthPremi, 2) . '%',
                'total_growth_komisi' => number_format($totalGrowthKomisi, 2) . '%',
            ]
        ];
    }

    public function generateClaimsReport(int $year): array
    {
        // 1. Definisikan nama bulan
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        // 2. Query Aggregate Database
        // Menggunakan incident_date sebagai acuan bulan
        $claims = DB::table('claims')
            ->selectRaw('
                MONTH(incident_date) as month, 
                COUNT(id) as total_kasus,
                SUM(COALESCE(casco, 0) + COALESCE(tjh, 0)) as total_estimasi,
                SUM(COALESCE(amount, 0)) as total_bayar
            ')
            ->whereYear('incident_date', $year)
            ->groupBy('month')
            ->get()
            ->keyBy('month'); 

        $rows = [];
        $footer = [
            'grand_kasus' => 0,
            'grand_estimasi' => 0,
            'grand_bayar' => 0,
            'grand_outstanding' => 0
        ];

        // 3. Mapping Data 1-12 Bulan (Agar bulan kosong tetap muncul angka 0)
        foreach ($months as $num => $name) {
            // Ambil data dari query, jika null (bulan kosong) set default 0
            $data = $claims->get($num);

            $kasus = $data ? $data->total_kasus : 0;
            $estimasi = $data ? $data->total_estimasi : 0;
            $bayar = $data ? $data->total_bayar : 0;
            
            // Logika Outstanding: Estimasi - Bayar
            // Jika bayar masih 0, outstanding = estimasi penuh
            $outstanding = $estimasi - $bayar;

            // Masukkan ke rows
            $rows[] = [
                'month_name' => $name,
                'total_kasus' => number_format($kasus, 0, ',', '.'), // Format ribuan untuk qty
                'total_estimasi' => $estimasi, // Biarkan raw number agar JS bisa format Rupiah
                'total_bayar' => $bayar,
                'outstanding' => $outstanding
            ];

            // Akumulasi Footer
            $footer['grand_kasus'] += $kasus;
            $footer['grand_estimasi'] += $estimasi;
            $footer['grand_bayar'] += $bayar;
            $footer['grand_outstanding'] += $outstanding;
        }

        return [
            'rows' => $rows,
            'footer' => $footer
        ];
    }
}