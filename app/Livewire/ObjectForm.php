<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ObjectModel;
use App\Services\Object\ObjectService;
use App\Services\ObjectOccupation\ObjectOccupationService;
use App\Services\Interest\InterestService;

class ObjectForm extends Component
{
    protected $objectService;
    protected $objectOccupationService;
    protected $interestService;

    public function boot(
        ObjectService $objectService,
        ObjectOccupationService $objectOccupationService,
        InterestService $interestService
    ) {
        $this->objectService = $objectService;
        $this->objectOccupationService = $objectOccupationService;
        $this->interestService = $interestService;
    }

    public function render()
    {
        $objectOccupations = $this->objectOccupationService->all();
        $interests = $this->interestService->all();

        return view('livewire.object-form', [
            'objectOccupations' => $objectOccupations,
            'interests' => $interests,
        ]);
    }
}
