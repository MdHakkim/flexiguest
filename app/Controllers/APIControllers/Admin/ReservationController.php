<?php

namespace App\Controllers\APIControllers\Admin;

use App\Controllers\BaseController;
use App\Models\Reservation;
use App\Models\Documents;
use CodeIgniter\API\ResponseTrait;

class ReservationController extends BaseController
{
    use ResponseTrait;

    private $Reservation;
    private $Documents;

    public function __construct()
    {
        $this->Reservation = new Reservation();
        $this->Documents = new Documents();
    }

    public function getReservationsList()
    {
        $reservsations = $this->Reservation
                                ->select('RESV_ID, RESV_ARRIVAL_DT, RESV_DEPARTURE, RESV_NIGHT, RESV_ADULTS, 
                                        RESV_CHILDREN, RESV_STATUS, RESV_ROOM, 
                                        fc.CUST_ID, fc.CUST_FIRST_NAME, fc.CUST_MIDDLE_NAME, fc.CUST_LAST_NAME, fc.CUST_TITLE, fc.CUST_DOB, 
                                        fc.CUST_ADDRESS_1, fc.CUST_ADDRESS_2, fc.CUST_ADDRESS_3, fc.CUST_COUNTRY, fc.CUST_CITY,
                                        fc.CUST_EMAIL, fc.CUST_MOBILE, fc.CUST_NATIONALITY, fc.CUST_COR, fc.CUST_DOC_NUMBER, fc.CUST_DOC_TYPE, 
                                        fc.CUST_DOC_ISSUE, fc.CUST_DOC_EXPIRY, fc.CUST_GENDER, 
                                        fr.RM_DESC, 
                                        CITY.ctname') 
                                        // fvd.VACC_DETAILS, fvd.VACC_IS_VERIFY, fvd.VACC_ISSUED_COUNTRY')
                                ->join('FLXY_CUSTOMER as fc', 'FLXY_RESERVATION.RESV_NAME = fc.CUST_ID', 'left')
                                ->join('FLXY_ROOM as fr', 'FLXY_RESERVATION.RESV_ROOM = fr.RM_NO', 'left')
                                ->join('CITY', 'fc.CUST_CITY = CITY.id', 'left')
                                // ->join('FLXY_VACCINE_DETAILS as fvd', 'FLXY_RESERVATION.RESV_NAME = fvd.VACC_CUST_ID AND FLXY_RESERVATION.RESV_ID = fvd.VACC_RESV_ID', 'left')
                                // ->join('FLXY_Documents as fd', 'FLXY_RESERVATION.RESV_NAME = fd.DOC_CUST_ID AND FLXY_RESERVATION.RESV_ID = fd.DOC_RESV_ID', 'left')
                                ->findAll();

        $result = responseJson(200, false, ['msg' => "Reservations list"], $reservsations);
        return $this->respond($result);
    }
    
}