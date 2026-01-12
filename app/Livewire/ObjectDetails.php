<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ObjectModel;
use App\Services\Object\ObjectService;

class ObjectDetails extends Component
{
    public $objectId;
    public $object = null;
    public $notFound = false;

    protected $objectService;

    public function boot(ObjectService $objectService)
    {
        $this->objectService = $objectService;
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

    public function handleObjectIdChanged($objectId)
    {
        $this->objectId = $objectId;
        $this->loadObject();
    }

    public function loadObject()
    {
        if ($this->objectId) {
            $this->object = $this->objectService->find($this->objectId);
            // If object not found, do nothing
            if (!$this->object) {
                $this->notFound = true;
                return;
            }

            // Dispatch event with object value
            $this->dispatch('objectLoaded', object: $this->object);
        } else {
            $this->object = null;
        }
    }

    public function render()
    {
        return view('livewire.object-details');
    }
}
