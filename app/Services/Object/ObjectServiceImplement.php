<?php

namespace App\Services\Object;

use LaravelEasyRepository\Service;
use App\Repositories\Object\ObjectRepository;
use App\Repositories\ObjectType\ObjectTypeRepository;
use App\Repositories\Part\PartRepository;
use App\Models\ObjectPart;

class ObjectServiceImplement extends Service implements ObjectService
{

    /**
     * set title message api for CRUD
     * @param string $title
     */
    protected string $title = "";
    /**
     * uncomment this to override the default message
     * protected string $create_message = "";
     * protected string $update_message = "";
     * protected string $delete_message = "";
     */

    /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
    protected ObjectRepository $mainRepository;
    protected ObjectTypeRepository $objectTypeRepository;
    protected PartRepository $partRepository;

    public function __construct(
        ObjectRepository $mainRepository,
        ObjectTypeRepository $objectTypeRepository,
        PartRepository $partRepository
    ) {
        $this->mainRepository = $mainRepository;
        $this->objectTypeRepository = $objectTypeRepository;
        $this->partRepository = $partRepository;
    }

    /**
     * Get all objects with relations
     */
    public function allWithRelations()
    {
        return $this->mainRepository->getAllWithRelations();
    }

    /**
     * Create object with parts
     */
    public function createWithParts(array $data)
    {
        // Handle new object type
        if (!empty($data['object_type_name'])) {
            $objectType = $this->objectTypeRepository->firstOrCreate(
                ['name' => $data['object_type_name']]
            );
            $data['object_type_id'] = $objectType->id;
        }

        $object = $this->mainRepository->create([
            'object_type_id' => $data['object_type_id'],
            'name' => $data['name'],
            'address' => $data['address'],
        ]);

        // Save parts if provided
        if (isset($data['part_id']) && is_array($data['part_id'])) {
            foreach ($data['part_id'] as $index => $partId) {
                $partName = $data['part_name'][$index] ?? null;
                $partValue = $data['part_value'][$index] ?? 0;

                // If part_id is not null, use part_id; else use part_name
                if (!empty($partId) && is_numeric($partId) && intval($partId) > 0) {
                    $finalPartId = intval($partId);
                } else {
                    $part = $this->partRepository->firstOrCreate(['name' => $partName]);
                    $finalPartId = $part->id;
                }

                ObjectPart::create([
                    'object_id' => $object->id,
                    'part_id' => $finalPartId,
                    'value' => floatval($partValue),
                ]);
            }
        }

        return $object;
    }

    /**
     * Update object with parts
     */
    public function updateWithParts($id, array $data)
    {
        $object = $this->mainRepository->find($id);

        if (!$object) {
            return false;
        }

        // Handle new object type
        if (!empty($data['object_type_name'])) {
            $objectType = $this->objectTypeRepository->firstOrCreate(
                ['name' => $data['object_type_name']]
            );
            $data['object_type_id'] = $objectType->id;
        }

        $updated = $this->mainRepository->update($id, [
            'object_type_id' => $data['object_type_id'],
            'name' => $data['name'],
            'address' => $data['address'],
        ]);

        // Delete existing parts and re-create
        ObjectPart::where('object_id', $id)->delete();

        if (isset($data['part_id']) && is_array($data['part_id'])) {
            foreach ($data['part_id'] as $index => $partId) {
                $partName = $data['part_name'][$index] ?? null;
                $partValue = $data['part_value'][$index] ?? 0;

                // If part_id is not null, use part_id; else use part_name
                if (!empty($partId) && is_numeric($partId) && intval($partId) > 0) {
                    $finalPartId = intval($partId);
                } else {
                    $part = $this->partRepository->firstOrCreate(['name' => $partName]);
                    $finalPartId = $part->id;
                }

                ObjectPart::create([
                    'object_id' => $id,
                    'part_id' => $finalPartId,
                    'value' => floatval($partValue),
                ]);
            }
        }

        return $updated;
    }

    /**
     * Get all objects by relation with filter
     */
    public function getAllByRelationWithFilter(array $filters = [])
    {
        return $this->mainRepository->getAllByRelationWithFilter($filters);
    }
}
