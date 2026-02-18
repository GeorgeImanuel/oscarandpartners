<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetEvaluationRequest; 
use App\Http\Requests\GetClaimsRequest;
use App\Services\Report\ReportService;
use Illuminate\Http\JsonResponse;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function evaluation(GetEvaluationRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $report = $this->reportService->generateEvaluationReport(
            (int) $validated['year_current'],
            (int) $validated['year_compare']
        );

        return response()->json([
            'status' => true,
            'message' => 'Laporan Evaluasi Produksi berhasil dibuat.',
            'data' => $report
        ]);
    }

    public function index()
    {
        // Kita hanya return view, datanya nanti diambil via JS (AJAX/Fetch)
        return view('reports.evaluation');
    }

    public function indexClaims()
    {
        return view('reports.claims');
    }

    // 2. Ambil Data JSON (Dipanggil via Fetch/AJAX)
    public function claims(GetClaimsRequest $request): JsonResponse
    {
        $validated = $request->validated();

        // Panggil logic di service
        $report = $this->reportService->generateClaimsReport(
            (int) $validated['year']
        );

        return response()->json([
            'status' => true,
            'message' => 'Laporan Klaim berhasil diambil.',
            'data' => $report
        ]);
    }
}