<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UpdateInstallmentsRequest;
use App\Http\Requests\UpdateClientPaymentRequest;
use App\Http\Requests\UpdateInsurancePaymentRequest;
use App\Services\Payment\PaymentService;
use App\Services\Card\CardService;
use App\Services\User\UserService;
use App\Services\PaymentStatus\PaymentStatusService;
use App\Repositories\Payment\PaymentRepositoryImplement;

use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    private $paymentService;
    private $cardService;
    private $userService;
    private $paymentStatusService;

    public function __construct(
        PaymentService $paymentService,
        CardService $cardService,
        UserService $userService,
        PaymentStatusService $paymentStatusService
    ) {
        $this->paymentService = $paymentService;
        $this->cardService = $cardService;
        $this->userService = $userService;
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
        $payment->load('card.policy.objects', 'card.client.user', 'card.paymentType');
        
        // Ambil semua payments dengan card_id yang sama
        $allPayments = $this->paymentService->getByCardId($payment->card_id);
        
        // Get all payment types for dropdown
        $paymentTypes = \App\Models\PaymentType::all();
        
        return view('payments.detail', compact('payment', 'allPayments', 'paymentTypes'));
    }

    /**
     * Update installments for a payment card
     */
    public function updateInstallments(UpdateInstallmentsRequest $request, string $id)
    {
        DB::beginTransaction();

        try {
            // 1. Ambil data payment lama untuk referensi status
            $payment = $this->paymentService->findOrFail($id);
            $cardId = $payment->card_id;
            
            // Get validated data from FormRequest
            $validated = $request->validated();

            // Update card payment type menggunakan service
            $this->cardService->update($cardId, [
                'payment_type_id' => $validated['payment_type_id']
            ]);

            // 2. Siapkan template data dari payment lama
            // Kita simpan status pembayaran lama di sini
            $templateData = [
                'created_by' => $payment->created_by,
                // Pastikan nama kolom sesuai database: 'payment_status_id'
                'payment_status_id' => $payment->payment_status_id, 
            ];

            // Delete existing payments for this card menggunakan service
            $existingPayments = $this->paymentService->getByCardId($cardId);
            foreach ($existingPayments as $existingPayment) {
                $this->paymentService->delete($existingPayment->id);
            }

            // Create new payments based on installments menggunakan service
            foreach ($validated['installments'] as $index => $installment) {
                $this->paymentService->create([
                    'card_id' => $cardId,
                    'sequence' => $index + 1,
                    'amount' => $installment['amount'],
                    'deadline_at' => $installment['due_date'],
                    'created_by' => $templateData['created_by'],
                    // 3. Pakai status dari data lama
                    'payment_status_id' => $templateData['payment_status_id'], 
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Installments berhasil disimpan!',
                'data' => [
                    'total_installments' => count($validated['installments']),
                    'card_id' => $cardId
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateClientPay(UpdateClientPaymentRequest $request, string $id)
    {
        try {
            // Panggil service processClientPayment dengan data yang sudah divalidasi
            $this->paymentService->processClientPayment($id, $request->validated());

            return redirect()->back()->with('success', 'Pembayaran Nasabah berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updateInsurancePay(UpdateInsurancePaymentRequest $request, string $id)
    {
        try {
            // Panggil service processInsurancePayment dengan data yang sudah divalidasi
            $this->paymentService->processInsurancePayment($id, $request->validated());

            return redirect()->back()->with('success', 'Pembayaran Asuransi berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    public function overdue()
    {
        // Mengambil data dari Service (yang sudah difilter di Repository)
        $payments = $this->paymentService->getOverduePayments();

        // Menampilkan ke view payments.overdue
        return view('payments.overdue', compact('payments'));
    }
}
