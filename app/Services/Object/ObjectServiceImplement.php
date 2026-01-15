<?php

namespace App\Services\Object;

use LaravelEasyRepository\Service;
use App\Repositories\Object\ObjectRepository;
use App\Repositories\ObjectOccupation\ObjectOccupationRepository;
use App\Repositories\Interest\InterestRepository;
use App\Models\ObjectInterest;
use Illuminate\Validation\Rules\In;

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
    protected ObjectOccupationRepository $objectOccupationRepository;
    protected InterestRepository $interestRepository;

    public function __construct(
        ObjectRepository $mainRepository,
        ObjectOccupationRepository $objectOccupationRepository,
        InterestRepository $interestRepository
    ) {
        $this->mainRepository = $mainRepository;
        $this->objectOccupationRepository = $objectOccupationRepository;
        $this->interestRepository = $interestRepository;
    }

    /**
     * Get all objects with relations
     */
    public function allWithRelations()
    {
        return $this->mainRepository->getAllWithRelations();
    }

    /**
     * Create object with interests
     */
    public function createWithInterests(array $data)
    {
        // Handle new object occupation
        if (!empty($data['object_occupation_name'])) {
            $objectOccupation = $this->objectOccupationRepository->firstOrCreate(
                ['name' => $data['object_occupation_name']]
            );
            $data['object_occupation_id'] = $objectOccupation->id;
        }

        $object = $this->mainRepository->create([
            'object_occupation_id' => $data['object_occupation_id'],
            'name' => $data['name'],
            'address' => $data['address'],
        ]);

        // Save interests if provided
        if (isset($data['interest_id']) && is_array($data['interest_id'])) {
            foreach ($data['interest_id'] as $index => $interestId) {
                $interestName = $data['interest_name'][$index] ?? null;
                $interestValue = $data['interest_value'][$index] ?? 0;

                // If interest_id is not null, use interest_id; else use interest_name
                if (!empty($interestId) && is_numeric($interestId) && intval($interestId) > 0) {
                    $finalInterestId = intval($interestId);
                } else {
                    $interest = $this->interestRepository->firstOrCreate(['name' => $interestName]);
                    $finalInterestId = $interest->id;
                }

                ObjectInterest::create([
                    'object_id' => $object->id,
                    'interest_id' => $finalInterestId,
                    'value' => floatval($interestValue),
                ]);
            }
        }

        return $object;
    }

    /**
     * Update object with interests
     */
    public function updateWithInterests($id, array $data)
    {
        $object = $this->mainRepository->find($id);

        if (!$object) {
            return false;
        }

        // Handle new object occupation
        if (!empty($data['object_occupation_name'])) {
            $objectOccupation = $this->objectOccupationRepository->firstOrCreate(
                ['name' => $data['object_occupation_name']]
            );
            $data['object_occupation_id'] = $objectOccupation->id;
        }

        $updated = $this->mainRepository->update($id, [
            'object_occupation_id' => $data['object_occupation_id'],
            'name' => $data['name'],
            'address' => $data['address'],
        ]);

        // Delete existing interests and re-create
        ObjectInterest::where('object_id', $id)->delete();

        if (isset($data['interest_id']) && is_array($data['interest_id'])) {
            foreach ($data['interest_id'] as $index => $interestId) {
                $interestName = $data['interest_name'][$index] ?? null;
                $interestValue = $data['interest_value'][$index] ?? 0;

                // If interest_id is not null, use interest_id; else use interest_name
                if (!empty($interestId) && is_numeric($interestId) && intval($interestId) > 0) {
                    $finalInterestId = intval($interestId);
                } else {
                    $interest = $this->interestRepository->firstOrCreate(['name' => $interestName]);
                    $finalInterestId = $interest->id;
                }

                ObjectInterest::create([
                    'object_id' => $id,
                    'interest_id' => $finalInterestId,
                    'value' => floatval($interestValue),
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
