<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;
use App\Services\Card\CardService;
use App\Services\Client\ClientService;
use App\Services\Object\ObjectService;
use App\Services\Policy\PolicyService;
use App\Http\Requests\UpdateCardRequest;
use App\Services\CardType\CardTypeService;
use App\Services\Relation\RelationService;
use App\Services\CardStatus\CardStatusService;
use App\Services\PaymentType\PaymentTypeService;
use App\Services\PaymentStatus\PaymentStatusService;

class CardController extends Controller
{
    private $cardService;
    private $clientService;
    private $policyService;
    private $cardStatusService;
    private $cardTypeService;
    private $paymentStatusService;
    private $paymentTypeService;
    private $relationService;
    private $objectService;

    public function __construct(
        CardService $cardService,
        ClientService $clientService,
        PolicyService $policyService,
        CardStatusService $cardStatusService,
        CardTypeService $cardTypeService,
        PaymentStatusService $paymentStatusService,
        PaymentTypeService $paymentTypeService,
        RelationService $relationService,
        ObjectService $objectService
    ) {
        $this->cardService          = $cardService;
        $this->clientService        = $clientService;
        $this->policyService        = $policyService;
        $this->cardStatusService    = $cardStatusService;
        $this->cardTypeService      = $cardTypeService;
        $this->paymentStatusService = $paymentStatusService;
        $this->paymentTypeService   = $paymentTypeService;
        $this->relationService      = $relationService;
        $this->objectService        = $objectService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = [];

        if ($request->has('client_id') && $request->client_id != '') {
            $filters['client_id'] = $request->client_id;
        }

        if ($request->has('card_status_id') && $request->card_status_id != '') {
            $filters['card_status_id'] = $request->card_status_id;
        }

        $cards        = $this->cardService->getAllWithFilter($filters);
        $clients      = $this->clientService->getAllWithRelations();
        $cardStatuses = $this->cardStatusService->all();

        return view('cards.index', compact('cards', 'clients', 'cardStatuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $objects        = $this->objectService->all();
        $clients        = $this->clientService->all();
        $policies       = $this->policyService->allActive();
        $cardTypes      = $this->cardTypeService->all();
        $parentCards    = $this->cardService->getAllParent();
        $paymentTypes   = $this->paymentTypeService->all();
        
        return view('cards.form', compact('objects', 'clients', 'policies', 'cardTypes', 'parentCards', 'paymentTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $card = $this->cardService->create($request->all());
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
        $card = $this->cardService->findWithRelation($productionCard->id);
        return view('cards.detail', compact('card'));
    }

    /**
     * Display the preview of production card.
     */
    public function preview(Card $productionCard)
    {
        $productionCard = $this->cardService->find($productionCard->id);

        return view('cards.preview', compact('productionCard'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Card $productionCard)
    {
        $card           = $this->cardService->findWithRelation($productionCard->id);
        $objects        = $this->objectService->all();
        $clients        = $this->clientService->all();
        $policies       = $this->policyService->allActive();
        $cardTypes      = $this->cardTypeService->all();
        $parentCards    = $this->cardService->getAllParent();

        return view('cards.form', compact('card', 'objects', 'clients', 'policies', 'cardTypes', 'parentCards'));
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
        $this->cardService->delete($productionCard->id);
        return redirect()->route('cards.index')->with('success', 'Berhasil menghapus kartu produksi.');
    }

    /**
     * Display report by relation
     */
    public function reportByRelation(Request $request)
    {
        $filters = [];
        $user = auth()->user();

        // Set filter untuk client
        if ($user->hasRole('client') && $user->client) {
            $filters['relation_id'] = $user->client->relation_id;
        }

        // Set filter untuk owner/admin
        if ($user->hasRole(['owner', 'admin'])) {
            if ($request->has('client_id') && $request->client_id != '') {
                $filters['client_id'] = $request->client_id;
            }

            if ($request->has('relation_id') && $request->relation_id != '') {
                $filters['relation_id'] = $request->relation_id;
            }
        }

        // Get objects dari database (sudah di-filter di repository)
        $objects = $this->objectService->getAllByRelationWithFilter($filters);

        // Group objects by relation
        $objectsByRelation = $objects->groupBy(function ($object) use ($request) {
            $targetPolicy = null;

            // 1. Jika User sedang filter Client tertentu, cari polis milik client itu dulu
            if ($request->filled('client_id')) {
                $targetPolicy = $object->policies->first(function ($policy) use ($request) {
                    return $policy->productionCard && $policy->productionCard->client_id == $request->client_id;
                });
            }

            // 2. Jika tidak ada filter / tidak ketemu, ambil polis TERBARU (berdasarkan tanggal atau ID)
            // Ini untuk mencegah mengambil data history lama yang relasinya mungkin sudah berubah
            if (!$targetPolicy) {
                $targetPolicy = $object->policies->sortByDesc('created_at')->first();
            }

            // 3. Ambil Relation ID dari polis yang terpilih
            if ($targetPolicy && $targetPolicy->productionCard && $targetPolicy->productionCard->client) {
                return $targetPolicy->productionCard->client->relation_id ?? 0;
            }
            
            return 0;
        });

        if ($request->filled('relation_id')) {
            $requestedRelationId = (int) $request->relation_id;
            
            $objectsByRelation = $objectsByRelation->filter(function ($value, $key) use ($requestedRelationId) {
                return $key === $requestedRelationId;
            });
        }

        $relations = $this->relationService->all();
        $clients = $user->hasRole(['owner', 'admin']) ? $this->clientService->getAllWithRelations() : collect();

        return view('cards.report-by-relation', compact('objectsByRelation', 'relations', 'clients'));
    }

    /**
     * Display report by object (owner/admin only)
     */
    public function reportByObject(Request $request)
    {
        // Check if user has owner or admin role
        if (!auth()->user()->hasRole('owner') && !auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $filters = [];

        if ($request->has('object_id') && $request->object_id != '') {
            $filters['object_id'] = $request->object_id;
        }

        $cards = $this->cardService->getAllWithFilter($filters);
        $objects = $this->objectService->allWithRelations();

        $paymentStatuses = $this->paymentStatusService->all();

        return view('cards.report-by-object', compact('cards', 'objects', 'paymentStatuses'));
    }
}
