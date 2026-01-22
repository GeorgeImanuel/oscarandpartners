<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetEvaluationRequest; 
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
}