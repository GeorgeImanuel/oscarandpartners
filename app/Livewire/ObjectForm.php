<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ObjectModel;
use App\Services\Object\ObjectService;
use App\Services\ObjectOccupation\ObjectOccupationService;
use App\Services\Interest\InterestService;

class ObjectForm extends Component
{
    public $objectId;
    public $object = null;
    public $isDisabled = false;
    
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

    public function mount($objectId = null)
    {
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

        return view('livewire.object-form', [
            'objectOccupations' => $objectOccupations,
            'interests' => $interests,
        ]);
    }
}
