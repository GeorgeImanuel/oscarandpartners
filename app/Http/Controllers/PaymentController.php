<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UpdateInstallmentsRequest;
use App\Services\Payment\PaymentService;
use App\Services\Card\CardService;
use App\Services\User\UserService;
use App\Services\CollectorType\CollectorTypeService;
use App\Services\PaymentStatus\PaymentStatusService;
use App\Repositories\Payment\PaymentRepositoryImplement;

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
        $payment->load('productionCard.policy.object', 'productionCard.client.user', 'productionCard.paymentType');
        
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
        try {
            $payment = $this->paymentService->findOrFail($id);
            $cardId = $payment->card_id;
            
            // Get validated data from FormRequest
            $validated = $request->validated();

            // Update card payment type menggunakan service
            $this->cardService->update($cardId, [
                'payment_type_id' => $validated['payment_type_id']
            ]);

            // Ambil data dari payment existing sebagai template
            $templateData = [
                'created_by' => $payment->created_by,
                'client_payment_status_id' => $payment->client_payment_status_id ?? 1,
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
                    'client_payment_status_id' => $templateData['client_payment_status_id'],
                    'note' => '',
                ]);
            }

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

    // Di PaymentController.php, ganti isi function overdue sementara:

// app/Http/Controllers/PaymentController.php

    public function overdue()
    {
        // Mengambil data dari Service (yang sudah difilter di Repository)
        $payments = $this->paymentService->getOverduePayments();

        // Menampilkan ke view payments.overdue
        return view('payments.overdue', compact('payments'));
    }
}
