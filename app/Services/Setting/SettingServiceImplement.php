<?php

namespace App\Services\Setting;

use LaravelEasyRepository\Service;
use App\Repositories\Setting\SettingRepository;

class SettingServiceImplement extends Service implements SettingService{

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
     protected SettingRepository $mainRepository;

    public function __construct(SettingRepository $mainRepository)
    {
      $this->mainRepository = $mainRepository;
    }

    // Define your custom methods :)
}
