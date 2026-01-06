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
use App\Services\Object\ObjectService;
use App\Services\Relation\RelationService;
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
    private $relationService;
    private $objectService;

    public function __construct(
        CardService $cardService,
        ClientService $clientService,
        PolicyService $policyService,
        CardStatusService $cardStatusService,
        PaymentStatusService $paymentStatusService,
        PaymentTypeService $paymentTypeService,
        RelationService $relationService,
        ObjectService $objectService
    ) {
        $this->cardService = $cardService;
        $this->clientService = $clientService;
        $this->policyService = $policyService;
        $this->cardStatusService = $cardStatusService;
        $this->paymentStatusService = $paymentStatusService;
        $this->paymentTypeService = $paymentTypeService;
        $this->relationService = $relationService;
        $this->objectService = $objectService;
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
        
        $cards = $this->cardService->getAllWithFilter($filters);
        $clients = $this->clientService->getAllWithRelations();
        $cardStatuses = $this->cardStatusService->all();
        
        return view('cards.index', compact('cards', 'clients', 'cardStatuses'));
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
        $this->cardService->deleteCard($productionCard->id);
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
        
        if ($user->hasRole('client') && $user->client) {
            $filters['relation_id'] = $user->client->relation_id;
        }
        
        if ($user->hasRole(['owner', 'admin'])) {
            if ($request->has('client_id') && $request->client_id != '') {
                $filters['client_id'] = $request->client_id;
            }
            
            if ($request->has('relation_id') && $request->relation_id != '') {
                $filters['relation_id'] = $request->relation_id;
            }
        }
        
        $objects = $this->objectService->getAllByRelationWithFilter($filters);
        
        $hasActiveFilter = isset($filters['client_id']) || isset($filters['relation_id']);
        
        $objects->each(function($object) use ($filters, $user, $hasActiveFilter) {
            $object->setRelation('policies', $object->policies->filter(function($policy) use ($filters, $user, $hasActiveFilter) {
                if (!$hasActiveFilter && !($user->hasRole('client') && $user->client)) {
                    return true;
                }
                
                if (!$policy->productionCard || !$policy->productionCard->client) {
                    return false;
                }
                
                $client = $policy->productionCard->client;
                
                if ($user->hasRole('client') && $user->client) {
                    if ($client->relation_id != $user->client->relation_id) {
                        return false;
                    }
                }
                
                if (isset($filters['client_id']) && $client->id != $filters['client_id']) {
                    return false;
                }
                
                if (isset($filters['relation_id']) && $client->relation_id != $filters['relation_id']) {
                    return false;
                }
                
                return true;
            })->values());
        });
        
        $objects = $objects->filter(function($object) {
            return $object->policies->isNotEmpty();
        });
        
        $objectsByRelation = $objects->groupBy(function($object) {
            $firstPolicy = $object->policies->first();
            if ($firstPolicy && $firstPolicy->productionCard && $firstPolicy->productionCard->client && $firstPolicy->productionCard->client->relation_id) {
                return $firstPolicy->productionCard->client->relation_id;
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
        $objects = $this->objectService->getAllWithRelations();
        
        return view('cards.report-by-object', compact('cards', 'objects'));
    }

}