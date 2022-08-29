<?php

namespace App\Controllers\APIControllers;

use App\Controllers\BaseController;
use App\Controllers\Repositories\ReservationRepository;
use CodeIgniter\API\ResponseTrait;

class ReservationController extends BaseController
{
    use ResponseTrait;

    private $ReservationRepository;

    public function __construct()
    {
        $this->ReservationRepository = new ReservationRepository();
    }

    public function reservationById()
    {
        $reservation_id = $this->request->getVar('reservation_id');

        $result = $this->ReservationRepository->reservationById($reservation_id);

        if (empty($result))
            return $this->respond(responseJson(200, false, ['msg' => 'No reservation found']));

        return $this->respond(responseJson(200, false, ['msg' => 'Reservation details'], $result));
    }
}
