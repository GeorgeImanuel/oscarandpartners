<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Client;
use App\Models\Policy;
use App\Models\CardStatus;
use App\Models\PaymentType;
use Illuminate\Http\Request;
use App\Models\PaymentStatus;
use App\Services\Card\CardService;
use App\Services\Client\ClientService;
use App\Services\Policy\PolicyService;
use App\Http\Requests\StoreCardRequest;
use App\Http\Requests\UpdateCardRequest;
use App\Services\CardStatus\CardStatusService;
use App\Services\PaymentType\PaymentTypeService;
use App\Services\PaymentStatus\PaymentStatusService;

class CardController extends Controller
{
    private $cardService;
    private $clientService;
    private $policyService;
    private $cardStatusService;
    private $paymentStatusService;
    private $paymentTypeService;

    public function __construct(
        CardService $cardService,
        ClientService $clientService,
        PolicyService $policyService,
        CardStatusService $cardStatusService,
        PaymentStatusService $paymentStatusService,
        PaymentTypeService $paymentTypeService
    ) {
        $this->cardService = $cardService;
        $this->clientService = $clientService;
        $this->policyService = $policyService;
        $this->cardStatusService = $cardStatusService;
        $this->paymentStatusService = $paymentStatusService;
        $this->paymentTypeService = $paymentTypeService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // auth()->user()->hasRole('client');
        // ['client_id' => $client_id];
        $cards = $this->cardService->getAllWithRelations();
        return view('cards.index', compact('cards'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients      = $this->clientService->all();
        $policies     = $this->policyService->getAllFreePolicies();
        $cardStatuses = $this->cardStatusService->all();
        $parentCards  = $this->cardService->getAllParent();

        return view('cards.form', compact('clients', 'policies', 'cardStatuses','parentCards'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return $request->all();
        $card = $this->cardService->createCard($request->all());

        if (!$card) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambah kartu produksi.');
        }

        return redirect()->route('cards.index')->with('success', 'Berhasil menambah kartu produksi.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Card $productionCard)
    {
        $productionCard->load([
            'client.relation',
            'policy.insurances',
            'cardStatus',
            'insurancePaymentStatus',
            'paymentType',
            'creator'
        ]);

        return view('cards.detail', compact('productionCard'));
    }

    /**
     * Display the preview of production card.
     */
    public function preview(Card $productionCard)
    {
        $productionCard->load([
            'client.relation',
            'policy.insurance',
            'cardStatus',
            'insurancePaymentStatus',
            'paymentType',
            'creator'
        ]);

        return view('cards.preview', compact('productionCard'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Card $productionCard)
    {
        $clients = Client::all();
        $policies = Policy::all();
        $cardStatuses = CardStatus::all();
        $paymentStatuses = PaymentStatus::all();
        $paymentTypes = PaymentType::all();

        return view('cards.edit', compact('productionCard', 'clients', 'policies', 'cardStatuses', 'paymentStatuses', 'paymentTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCardRequest $request, Card $productionCard)
    {
        $updated = $this->cardService->updateCard($productionCard->id, $request->all());

        if (!$updated) {
            return redirect()->back()->withInput()->with('error', 'Gagal mengubah kartu produksi.');
        }

        return redirect()->route('cards.index')->with('success', 'Berhasil mengubah kartu produksi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Card $productionCard)
    {
        $this->cardService->deleteCard($productionCard->id);
        return redirect()->route('cards.index')->with('success', 'Berhasil menghapus kartu produksi.');
    }
}
