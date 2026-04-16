<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ObjectModel;
use App\Services\Object\ObjectService;
use App\Services\ObjectOccupation\ObjectOccupationService;
use App\Services\Interest\InterestService;
use App\Services\Coverage\CoverageService;

class ObjectForm extends Component
{
    public $objectId;
    public $object = null;
    public $isDisabled = false;
    public $policyCoverageExists = false;
    
    protected $objectService;
    protected $objectOccupationService;
    protected $interestService;
    protected $coverageService;

    public function boot(
        ObjectService $objectService,
        ObjectOccupationService $objectOccupationService,
        InterestService $interestService,
        CoverageService $coverageService
    ) {
        $this->objectService = $objectService;
        $this->objectOccupationService = $objectOccupationService;
        $this->interestService = $interestService;
        $this->coverageService = $coverageService;
    }

    public function mount($objectId = null, $isDisabled = false, $policyCoverageExists = false)
    {
        $this->isDisabled = $isDisabled;
        $this->policyCoverageExists = (bool) $policyCoverageExists;

        if ($objectId) {
            $this->objectId = $objectId;
            $this->loadObject();
        }
    }

    public function updatedObjectId($value)
    {
        $this->loadObject();
    }

    public function loadObject()
    {
        if ($this->objectId) {
            $this->object = $this->objectService->find($this->objectId);
        } else {
            $this->object = null;
        }       
        $this->dispatch(
            'objectLoaded',
        );
    }

    public function render()
    {
        $objectOccupations = $this->objectOccupationService->all();
        $interests = $this->interestService->all();
        $coverages = $this->coverageService->all();

        return view('livewire.object-form', [
            'objectOccupations' => $objectOccupations,
            'interests' => $interests,
            'coverages' => $coverages,
        ]);
    }
}
