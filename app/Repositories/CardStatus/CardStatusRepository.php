<?php

namespace App\Repositories\CardStatus;

use LaravelEasyRepository\Repository;

interface CardStatusRepository extends Repository
{
    public function getActiveId();
    public function getGressPeriodId();
    public function getOverDeadlineId();
}
