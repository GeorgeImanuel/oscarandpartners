<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Policy;
use App\Models\CardStatus;
use App\Models\PaymentType;
use App\Models\PaymentStatus;
use App\Models\Card;
use App\Services\Client\ClientService;
use App\Services\Policy\PolicyService;
use App\Services\CardStatus\CardStatusService;
use App\Services\PaymentType\PaymentTypeService;
use App\Http\Requests\StoreCardRequest;
use App\Http\Requests\UpdateCardRequest;
use App\Services\PaymentStatus\PaymentStatusService;
use App\Services\Card\CardService;

class CardController extends Controller
{
    private $productionCardService;
    private $clientService;
    private $policyService;
    private $cardStatusService;
    private $paymentStatusService;
    private $paymentTypeService;

    public function __construct(
        CardService $productionCardService,
        ClientService $clientService,
        PolicyService $policyService,
        CardStatusService $cardStatusService,
        PaymentStatusService $paymentStatusService,
        PaymentTypeService $paymentTypeService
    ) {
        $this->productionCardService = $productionCardService;
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
        $cards = $this->productionCardService->getAllWithRelations();
        return view('cards.index', compact('cards'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients               = $this->clientService->all();
        $policies              = $this->policyService->getFreePolicies();
        $cardStatuses          = $this->cardStatusService->all();
        $parentCards = $this->productionCardService->getAllParent();

        return view('cards.form', compact('clients', 'policies', 'cardStatuses','parentCards'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCardRequest $request)
    {
        $card = $this->productionCardService->createCard($request->all());

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
            'policy.insurance',
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
        $updated = $this->productionCardService->updateCard($productionCard->id, $request->all());

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
        $this->productionCardService->deleteCard($productionCard->id);
        return redirect()->route('cards.index')->with('success', 'Berhasil menghapus kartu produksi.');
    }
}
