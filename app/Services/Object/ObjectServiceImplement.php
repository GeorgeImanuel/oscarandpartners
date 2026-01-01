<?php

namespace App\Services\Object;

use LaravelEasyRepository\Service;
use App\Repositories\Object\ObjectRepository;
use App\Models\ObjectType;
use App\Models\Part;
use App\Models\ObjectPart;

class ObjectServiceImplement extends Service implements ObjectService{

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

    public function __construct(ObjectRepository $mainRepository)
    {
      $this->mainRepository = $mainRepository;
    }

    /**
     * Get all objects with relations
     */
    public function getAllWithRelations()
    {
        return $this->mainRepository->getAllWithRelations();
    }

    /**
     * Get all object types
     */
    public function getObjectTypes()
    {
        return ObjectType::all();
    }

    /**
     * Get all parts
     */
    public function getParts()
    {
        return Part::all();
    }

    /**
     * Validate if object type name or part names already exist
     * Returns error message if duplicate found, null if valid
     */
    public function validateDuplicates(array $data): ?string
    {
        // Check if object_type_name already exists
        if (!empty($data['object_type_name'])) {
            $existingType = ObjectType::where('name', $data['object_type_name'])->first();
            if ($existingType) {
                return 'Tipe objek ' . htmlspecialchars($data['object_type_name'], ENT_QUOTES, 'UTF-8') . ' sudah ada. Silakan pilih dari daftar yang tersedia.';
            }
        }

        // Check if any part_name already exists
        if (isset($data['part_name']) && is_array($data['part_name'])) {
            foreach ($data['part_name'] as $index => $partName) {
                $partId = $data['part_id'][$index] ?? '';
                // Only check if user is adding new part (not selecting existing)
                if (empty($partId) && !empty($partName) && trim($partName) !== '') {
                    $existingPart = Part::where('name', $partName)->first();
                    if ($existingPart) {
                        return 'Part ' . htmlspecialchars($partName, ENT_QUOTES, 'UTF-8') . ' sudah ada. Silakan pilih dari daftar yang tersedia.';
                    }
                }
            }
        }

        return null; // No duplicates found
    }

    /**
     * Create object with parts
     */
    public function createWithParts(array $data)
    {
        // Handle new object type
        if (!empty($data['object_type_name'])) {
            $objectType = ObjectType::firstOrCreate(
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
            \Log::info('Processing parts', ['part_ids' => $data['part_id'], 'part_names' => $data['part_name'] ?? [], 'part_values' => $data['part_value'] ?? []]);
            
            foreach ($data['part_id'] as $index => $partId) {
                // Trim and clean part_id
                $partId = is_string($partId) ? trim($partId) : $partId;
                $partName = isset($data['part_name'][$index]) ? trim($data['part_name'][$index]) : '';
                $partValue = isset($data['part_value'][$index]) ? trim($data['part_value'][$index]) : '';
                $finalPartId = null;
                
                \Log::info("Processing part index {$index}", ['part_id' => $partId, 'part_name' => $partName, 'part_value' => $partValue]);
                
                // If part_id is selected from dropdown (must be numeric and > 0)
                if (!empty($partId) && $partId !== '' && $partId !== '0' && is_numeric($partId) && intval($partId) > 0) {
                    $finalPartId = intval($partId);
                    \Log::info("Using existing part", ['part_id' => $finalPartId]);
                }
                // If part_name is provided (new part) - only if part_id is empty or not numeric
                elseif ((empty($partId) || !is_numeric($partId)) && !empty($partName) && $partName !== '') {
                    $part = Part::firstOrCreate(['name' => $partName]);
                    $finalPartId = $part->id;
                    \Log::info("Created/found new part", ['part_name' => $partName, 'part_id' => $finalPartId]);
                }
                
                // Only insert if we have a valid part_id
                if ($finalPartId && is_numeric($finalPartId) && intval($finalPartId) > 0) {
                    // Sanitize value - ensure it's a valid number
                    $sanitizedValue = 0;
                    if (!empty($partValue) && is_numeric($partValue)) {
                        $sanitizedValue = floatval($partValue);
                        // Ensure value is within reasonable range
                        if ($sanitizedValue < 0) {
                            $sanitizedValue = 0;
                        }
                        if ($sanitizedValue > 999999999999999999) {
                            $sanitizedValue = 999999999999999999;
                        }
                    }
                    
                    ObjectPart::create([
                        'object_id' => $object->id,
                        'part_id' => intval($finalPartId),
                        'value' => $sanitizedValue,
                    ]);
                    \Log::info("ObjectPart created", ['object_id' => $object->id, 'part_id' => $finalPartId, 'value' => $sanitizedValue]);
                }
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
            $objectType = ObjectType::firstOrCreate(
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
                // Trim and clean part_id
                $partId = is_string($partId) ? trim($partId) : $partId;
                $partName = isset($data['part_name'][$index]) ? trim($data['part_name'][$index]) : '';
                $partValue = isset($data['part_value'][$index]) ? trim($data['part_value'][$index]) : '';
                $finalPartId = null;
                
                // If part_id is selected from dropdown (must be numeric and > 0)
                if (!empty($partId) && $partId !== '' && $partId !== '0' && is_numeric($partId) && intval($partId) > 0) {
                    $finalPartId = intval($partId);
                }
                // If part_name is provided (new part) - only if part_id is empty or not numeric
                elseif ((empty($partId) || !is_numeric($partId)) && !empty($partName) && $partName !== '') {
                    $part = Part::firstOrCreate(['name' => $partName]);
                    $finalPartId = $part->id;
                }
                
                // Only insert if we have a valid part_id
                if ($finalPartId && is_numeric($finalPartId) && intval($finalPartId) > 0) {
                    // Sanitize value - ensure it's a valid number
                    $sanitizedValue = 0;
                    if (!empty($partValue) && is_numeric($partValue)) {
                        $sanitizedValue = floatval($partValue);
                        // Ensure value is within reasonable range
                        if ($sanitizedValue < 0) {
                            $sanitizedValue = 0;
                        }
                        if ($sanitizedValue > 999999999999999999) {
                            $sanitizedValue = 999999999999999999;
                        }
                    }
                    
                    ObjectPart::create([
                        'object_id' => $id,
                        'part_id' => intval($finalPartId),
                        'value' => $sanitizedValue,
                    ]);
                }
            }
        }

        return $updated;
    }
}
