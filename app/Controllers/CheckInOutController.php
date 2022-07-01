<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Models\Reservation;
use App\Models\ShareReservations;

class CheckInOutController extends BaseController
{

    use ResponseTrait;

    private $DB;
    private $Reservation;
    private $ShareReservations;

    public function __construct()
    {
        $this->DB = \Config\Database::connect();
        $this->Reservation = new Reservation();
        $this->ShareReservations = new ShareReservations();
    }

    public function checkOut($reservation_id)
    {
        $reservation = $this->Reservation
                            ->select('FLXY_RESERVATION.*, 
                                    fc.CUST_ID, fc.CUST_FIRST_NAME, fc.CUST_MIDDLE_NAME, fc.CUST_LAST_NAME, 
                                    fc.CUST_ADDRESS_1, fc.CUST_ADDRESS_2, fc.CUST_ADDRESS_3,
                                    fc.CUST_COUNTRY, fc.CUST_STATE, fc.CUST_CITY,
                                    co.cname as COUNTRY_NAME,
                                    st.sname as STATE_NAME,
                                    ci.ctname as CITY_NAME,
                                    fr.RM_DESC')
                            ->join('FLXY_CUSTOMER as fc', 'FLXY_RESERVATION.RESV_NAME = fc.CUST_ID', 'left')
                            ->join('COUNTRY as co', 'fc.CUST_COUNTRY = co.iso2', 'left')
                            ->join('STATE as st', 'fc.CUST_STATE = st.state_code', 'left')
                            ->join('CITY as ci', 'fc.CUST_CITY = ci.id', 'left')
                            ->join('FLXY_ROOM as fr', 'FLXY_RESERVATION.RESV_ROOM = fr.RM_NO', 'left')
                            ->where('RESV_ID', $reservation_id)->first();

        if ($reservation['RESV_STATUS'] == 'Checked-Out')
            return $this->respond(responseJson(202, false, ['msg' => 'This reservation is already checked-out.']));
        
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml(view('Templates/ReservationInvoiceTemplate', ['reservation' => $reservation]));
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        $file_name = "assets/reservation-invoices/RES{$reservation['RESV_ID']}-Invoice.pdf";
        file_put_contents($file_name, $dompdf->output());

        $reservation['RESV_STATUS'] = 'Checked-Out';
        $this->Reservation->save($reservation);

        return $this->respond(responseJson(200, false, ['msg' => 'Reservation checked-out successfully.'], ['invoice' => base_url($file_name)]));
    }
}