<?php

namespace App\Services\CardType;

use LaravelEasyRepository\Service;
use App\Repositories\CardType\CardTypeRepository;

class CardTypeServiceImplement extends Service implements CardTypeService
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
    protected CardTypeRepository $mainRepository;

    public function __construct(CardTypeRepository $mainRepository)
    {
        $this->mainRepository = $mainRepository;
    }

    /**
     * Override delete method to check relationships
     */
    public function delete($id)
    {
        $cardType = $this->mainRepository->find($id);
        
        if (!$cardType) {
            throw new \Exception('Data tidak ditemukan.');
        }

        // Check if card type is used in cards
        if (\App\Models\Card::where('card_type_id', $id)->count() > 0) {
            throw new \Exception('Tidak bisa hapus data, data sudah digunakan di data lain.');
        }

        return $this->mainRepository->delete($id);
    }
}
