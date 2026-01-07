<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Payment\PaymentService;
use App\Services\Card\CardService;
use App\Services\User\UserService;
use App\Services\CollectorType\CollectorTypeService;
use App\Services\PaymentStatus\PaymentStatusService;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;

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
    public function index()
    {
        $payments = $this->paymentService->getAllWithRelations();
        return view('payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cards = $this->cardService->getAllWithRelations();
        $users = $this->userService->all();
        $collectorTypes = $this->collectorTypeService->all();
        $paymentStatuses = $this->paymentStatusService->all();
        
        return view('payments.form', compact('cards', 'users', 'collectorTypes', 'paymentStatuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePaymentRequest $request)
    {
        try {
            $payment = $this->paymentService->create($request->validated());
            
            if (!$payment) {
                return redirect()->back()->withInput()->with('error', 'Gagal menambahkan pembayaran.');
            }

            return redirect()->route('payments.index')
                ->with('success', 'Pembayaran berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $payment = $this->paymentService->findOrFail($id);
        return view('payments.detail', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $payment = $this->paymentService->findOrFail($id);
        $cards = $this->cardService->getAllWithRelations();
        $users = $this->userService->all();
        $collectorTypes = $this->collectorTypeService->all();
        $paymentStatuses = $this->paymentStatusService->all();

        return view('payments.form', compact('payment', 'cards', 'users', 'collectorTypes', 'paymentStatuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaymentRequest $request, string $id)
    {
        try {
            $updated = $this->paymentService->update($id, $request->validated());
            
            if (!$updated) {
                return redirect()->back()->withInput()->with('error', 'Gagal mengubah pembayaran.');
            }

            return redirect()->route('payments.index')
                ->with('success', 'Pembayaran berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->paymentService->delete($id);
        return redirect()->route('payments.index')
            ->with('success', 'Pembayaran berhasil dihapus.');
    }
}
