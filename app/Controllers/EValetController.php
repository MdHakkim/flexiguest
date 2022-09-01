<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Controllers\Repositories\EValetRepository;
use App\Controllers\Repositories\ReservationRepository;
use CodeIgniter\API\ResponseTrait;

class EValetController extends BaseController
{
    use ResponseTrait;

    private $ReservationRepository;
    private $EValetRepository;

    public function __construct()
    {
        $this->ReservationRepository = new ReservationRepository();
        $this->EValetRepository = new EValetRepository();
    }

    public function evalet()
    {
        $data['title'] = getMethodName();
        $data['session'] = session();

        $where_condition = "RESV_STATUS = 'Due Pre Check-In' or RESV_STATUS = 'Pre Checked-In' or RESV_STATUS = 'Checked-In'";
        $data['reservations'] = $this->ReservationRepository->allReservations($where_condition);

        return view('frontend/evalet', $data);
    }

    public function allEValet()
    {
        $this->EValetRepository->allEValet();
    }
}