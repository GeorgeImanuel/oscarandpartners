<?php

namespace App\Http\Controllers;

use App\Models\ProductionCard;
use App\Models\Client;
use App\Models\Policy;
use App\Models\CardStatus;
use App\Models\PaymentStatus;
use App\Models\PaymentType;
use App\Services\ProductionCard\ProductionCardService;
use App\Http\Requests\StoreProductionCardRequest;
use App\Http\Requests\UpdateProductionCardRequest;

class ProductionCardController extends Controller
{
    private $productionCardService;

    public function __construct(ProductionCardService $productionCardService)
    {
        $this->productionCardService = $productionCardService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cards = $this->productionCardService->getAllWithRelations();
        return view('production-cards.index', compact('cards'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::all();
        $policies = Policy::all();
        $cardStatuses = CardStatus::all();
        $paymentStatuses = PaymentStatus::all();
        $paymentTypes = PaymentType::all();
        
        return view('production-cards.create', compact('clients', 'policies', 'cardStatuses', 'paymentStatuses', 'paymentTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductionCardRequest $request)
    {
        $card = $this->productionCardService->createCard($request->all());
        
        if (!$card) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambah kartu produksi.');
        }
        
        return redirect()->route('cards.view')->with('success', 'Berhasil menambah kartu produksi.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductionCard $productionCard)
    {
        $productionCard->load([
            'client',
            'policy.insurance',
            'cardStatus',
            'insurancePaymentStatus',
            'paymentType',
            'creator'
        ]);
        
        return view('production-cards.detail', compact('productionCard'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductionCard $productionCard)
    {
        return view('production-cards.edit', compact('productionCard'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductionCardRequest $request, ProductionCard $productionCard)
    {
        $updated = $this->productionCardService->updateCard($productionCard->id, $request->all());
        
        if (!$updated) {
            return redirect()->back()->withInput()->with('error', 'Gagal mengubah kartu produksi.');
        }
        
        return redirect()->route('cards.view')->with('success', 'Berhasil mengubah kartu produksi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductionCard $productionCard)
    {
        $this->productionCardService->deleteCard($productionCard->id);
        return redirect()->route('cards.view')->with('success', 'Berhasil menghapus kartu produksi.');
    }
}
