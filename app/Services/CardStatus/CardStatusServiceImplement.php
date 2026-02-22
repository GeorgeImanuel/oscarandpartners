<?php

namespace App\Services\CardStatus;

use LaravelEasyRepository\Service;
use App\Repositories\CardStatus\CardStatusRepository;

class CardStatusServiceImplement extends Service implements CardStatusService
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
  protected CardStatusRepository $mainRepository;

  public function __construct(CardStatusRepository $mainRepository)
  {
    $this->mainRepository = $mainRepository;
  }

  /**
   * Override delete method to check relationships
   */
  public function delete($id)
  {
      $cardStatus = $this->mainRepository->find($id);
      
      if (!$cardStatus) {
          throw new \Exception('Data tidak ditemukan.');
      }

      // Check if card status is used in cards
      if (\App\Models\Card::where('card_status_id', $id)->count() > 0) {
          throw new \Exception('Tidak bisa hapus data, data sudah digunakan di data lain.');
      }

      return $this->mainRepository->delete($id);
  }
}
