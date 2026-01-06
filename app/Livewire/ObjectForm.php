<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ObjectModel;
use App\Services\Object\ObjectService;
use App\Services\ObjectType\ObjectTypeService;
use App\Services\Part\PartService;

class ObjectForm extends Component
{
    protected $objectService;
    protected $objectTypeService;
    protected $partService;

    public function boot(
        ObjectService $objectService,
        ObjectTypeService $objectTypeService,
        PartService $partService
    ) {
        $this->objectService = $objectService;
        $this->objectTypeService = $objectTypeService;
        $this->partService = $partService;
    }

    public function render()
    {
        $objectTypes = $this->objectTypeService->all();
        $parts = $this->partService->all();

        return view('livewire.object-form', [
            'objectTypes' => $objectTypes,
            'parts' => $parts,
        ]);
    }
}
