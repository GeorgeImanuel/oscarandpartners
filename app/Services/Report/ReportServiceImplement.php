<?php

namespace App\Services\Report;

use LaravelEasyRepository\Service;
use App\Repositories\Payment\PaymentRepository; 
use Carbon\Carbon;

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
}