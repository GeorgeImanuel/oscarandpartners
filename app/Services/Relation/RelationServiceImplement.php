<?php

namespace App\Services\Relation;

use LaravelEasyRepository\Service;
use App\Repositories\Relation\RelationRepository;

class RelationServiceImplement extends Service implements RelationService
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
    protected RelationRepository $mainRepository;

    public function __construct(RelationRepository $mainRepository)
    {
        $this->mainRepository = $mainRepository;
    }

    // Define your custom methods :)
}
