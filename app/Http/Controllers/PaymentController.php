<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Payment\PaymentService;
use App\Services\Card\CardService;
use App\Services\User\UserService;
use App\Services\CollectorType\CollectorTypeService;
use App\Services\PaymentStatus\PaymentStatusService;

class PaymentController extends Controller
{
    private $paymentService;
    private $cardService;
    private $userService;
    private $collectorTypeService;
    private $paymentStatusService;

    public function __construct(
        PaymentService $paymentService,
        CardService $cardService,
        UserService $userService,
        CollectorTypeService $collectorTypeService,
        PaymentStatusService $paymentStatusService
    ) {
        $this->paymentService = $paymentService;
        $this->cardService = $cardService;
        $this->userService = $userService;
        $this->collectorTypeService = $collectorTypeService;
        $this->paymentStatusService = $paymentStatusService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index() {
        $payments = $this->paymentService->getAllWithRelations();
        
        // DEBUG: Melihat struktur data payment pertama
        if($payments->count() > 0) {
            ([
                'payment_id' => $payments->first()->id,
                'card_data' => $payments->first()->card,
                'policy_data' => $payments->first()->card?->policy
            ]);
        }
        
        return view('payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(){}

    /**
     * Store a newly created resource in storage.
     */
    public function store($request){}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $payment = $this->paymentService->findOrFail($id);
        return view('payments.detail', compact('payment'));
    }
}
