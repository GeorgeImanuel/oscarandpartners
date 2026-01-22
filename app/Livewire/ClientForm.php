<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\Relation\RelationService;

class ClientForm extends Component
{
    protected $relationService;

    public function boot(
        RelationService $relationService,
    ) {
        $this->relationService = $relationService;
    }

    public function render()
    {
        $relations = $this->relationService->all();
        return view('livewire.client-form', compact('relations'));
    }
}
