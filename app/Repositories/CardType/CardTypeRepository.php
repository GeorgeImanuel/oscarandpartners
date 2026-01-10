<?php

namespace App\Repositories\CardType;

use LaravelEasyRepository\Repository;

interface CardTypeRepository extends Repository
{
    public function getNewId();
    public function getRenewalId();
    public function getEndorsId();
}
