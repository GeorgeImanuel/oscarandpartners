<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;
use App\Models\CollectorType;
use App\Services\Card\CardService;
use App\Services\Client\ClientService;
use App\Services\Object\ObjectService;
use App\Services\Policy\PolicyService;
use App\Http\Requests\UpdateCardRequest;
use App\Services\CardType\CardTypeService;
use App\Services\Relation\RelationService;
use App\Services\CardStatus\CardStatusService;
use App\Services\PaymentType\PaymentTypeService;
use App\Services\CollectorType\CollectorTypeService;
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
    private $collectorTypeService;

    public function __construct(
        CardService $cardService,
        ClientService $clientService,
        PolicyService $policyService,
        CardStatusService $cardStatusService,
        CardTypeService $cardTypeService,
        PaymentStatusService $paymentStatusService,
        PaymentTypeService $paymentTypeService,
        RelationService $relationService,
        ObjectService $objectService,
        CollectorTypeService $collectorTypeService
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
        $this->collectorTypeService = $collectorTypeService;
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

        // return $cards;

        return view('cards.index', compact('cards', 'clients', 'cardStatuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $objects        = $this->objectService->all();
        $clients        = $this->clientService->all();
        $policies       = $this->policyService->allActivePolicies();
        $cardTypes      = $this->cardTypeService->all();
        $parentCards    = $this->cardService->getAllParent();
        $collectorTypes = $this->collectorTypeService->all();
        // return $objects;
        return view('cards.form', compact('objects', 'clients', 'policies', 'cardTypes', 'parentCards','collectorTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return $request->all();
        $card = $this->cardService->create($request->all());
        // return $card;
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
        $productionCard = $this->cardService->find($productionCard->id);

        return view('cards.detail', compact('productionCard'));
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
        $clients = $this->clientService->all();
        $policies = $this->policyService->all();
        $cardStatuses = $this->cardStatusService->all();
        $paymentStatuses = $this->paymentStatusService->all();
        $paymentTypes = $this->paymentTypeService->all();

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
        $this->cardService->delete($productionCard->id);
        return redirect()->route('cards.index')->with('success', 'Berhasil menghapus kartu produksi.');
    }

    public function thisMonth()
    {
        $currentDate = now();
        $month = $currentDate->format('F');
        $year = $currentDate->year;

        $data = [
            'started_date' => $currentDate->copy()->startOfMonth()->format('Y-m-d'),
            'ended_date' => $currentDate->copy()->endOfMonth()->format('Y-m-d'),
        ];

        $cards = $this->cardService->getAllWithFilter($data);

        return view('cards.monthly', compact('cards', 'month', 'year'));
    }

    public function nextMonth()
    {
        $nextMonthDate = now()->addMonth();
        $month = $nextMonthDate->format('F');
        $year = $nextMonthDate->year;

        $data = [
            'started_date' => $nextMonthDate->copy()->startOfMonth()->format('Y-m-d'),
            'ended_date' => $nextMonthDate->copy()->endOfMonth()->format('Y-m-d'),
        ];

        $cards = $this->cardService->getAllWithFilter($data);

        return view('cards.monthly', compact('cards', 'month', 'year'));
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
        $objectsByRelation = $objects->groupBy(function ($object) {
            // Ambil relation_id dari policy pertama
            $firstPolicy = $object->policies->first();
            if ($firstPolicy && $firstPolicy->productionCard && $firstPolicy->productionCard->client) {
                return $firstPolicy->productionCard->client->relation_id ?? 0;
            }
            return 0;
        });

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

        return view('cards.report-by-object', compact('cards', 'objects'));
    }
}
