<?php

namespace App\Services\Report;

use LaravelEasyRepository\BaseService;

interface ReportService extends BaseService
{
    public function generateEvaluationReport(int $yearCurrent, int $yearCompare): array;
}