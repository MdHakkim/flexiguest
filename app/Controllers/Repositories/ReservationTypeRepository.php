<?php

namespace App\Controllers\Repositories;

use App\Controllers\BaseController;
use App\Models\ReservationType;
use CodeIgniter\API\ResponseTrait;

class ReservationTypeRepository extends BaseController
{
    use ResponseTrait;

    private $ReservationType;

    public function __construct()
    {
        $this->ReservationType = new ReservationType();
    }

    public function reservationTypes()
    {
        return $this->ReservationType->findAll();
    }
}