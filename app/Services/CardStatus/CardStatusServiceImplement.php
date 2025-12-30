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

  // Define your custom methods :)
}
