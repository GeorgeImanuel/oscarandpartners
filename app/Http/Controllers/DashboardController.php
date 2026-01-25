<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Payment\PaymentRepository;
use App\Repositories\Card\CardRepository;
use App\Repositories\Policy\PolicyRepository; 
use App\Models\Client;
use App\Models\Card;
use App\Models\Payment;
use App\Models\Policy; // <--- JANGAN LUPA TAMBAHKAN INI BOY
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    protected $paymentRepository;

    public function __construct(PaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function index()
    {
        // 1. WELCOME MESSAGE (User Data)
        $user = auth()->user();

        // 2. JUMLAH CLIENT
        $totalClients = Client::count();

        // 3. POLICY ACTIVE (Total Polis Aktif)
        // Pastikan 'policy_status_id' ada di tabel policies. 
        // Biasanya status aktif = 1 (sesuaikan dengan config kamu)
        $activePolicies = Policy::whereDate('ended_date', '>=', date('Y-m-d'))->count();
        // 4. LIST OVERDUE (Payment Belum Terbayarkan)
        $overduePayments = Payment::with(['card.client', 'card.policy'])
            ->whereDate('deadline_at', '<', date('Y-m-d')) // Sudah lewat tanggal
            ->where('payment_status_id', config('constants.payment_status.pending')) // Belum lunas
            ->latest()
            ->take(5) 
            ->get();

            
        $labels = [];      // <--- We define this variable
        $incomeData = [];
        $komisiData = [];

        // Fetch raw data for current and previous year to be safe
        $years = [date('Y'), date('Y', strtotime('-1 year'))];
        $rawData = $this->paymentRepository->getMonthlySumByYears($years);

        // Loop backwards 11 months to now (12 months total)
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $month = $date->month;
            $year = $date->year;

            // Add label (e.g., "Jan 26")
            $labels[] = $date->format('M y'); 

            // Find matching data in collection
            $found = $rawData->first(function($item) use ($month, $year) {
                return $item->month == $month && $item->year == $year;
            });

            $incomeData[] = $found ? (int)$found->total_premi : 0;
            $komisiData[] = $found ? (int)$found->total_komisi : 0;
        }

        // Hitung Total Balance setahun
        $totalBalance = array_sum($incomeData);

        // Return ke view (pastikan nama view-nya 'dashboard.index' kalau di folder dashboard)
        return view('dashboard', compact(
            'user', 
            'totalClients', 
            'activePolicies', 
            'overduePayments', 
            'incomeData', 
            'komisiData',
            'labels',
            'totalBalance'
        ));
    }
}