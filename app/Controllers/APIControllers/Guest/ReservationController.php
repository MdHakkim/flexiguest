<?php

namespace App\Controllers\APIControllers\Guest;

use App\Controllers\BaseController;
use App\Models\Reservation;
use CodeIgniter\API\ResponseTrait;

class ReservationController extends BaseController
{
    use ResponseTrait;

    private $Reservation;

    public function __construct()
    {
        $this->Reservation = new Reservation();
    }

    public function makeCheckoutRequest($reservation_id)
    {
        $customer_id = $this->request->user['USR_CUST_ID'];

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

        if ($reservation['RESV_NAME'] != $customer_id)
            return $this->respond(responseJson(404, false, ['msg' => 'Invalid reservation.']));

        if ($reservation['RESV_STATUS'] == 'Checked-Out')
            return $this->respond(responseJson(202, false, ['msg' => 'This reservation is already checked-out.']));

        if ($reservation['RESV_STATUS'] == 'Checked-Out-Requested')
            return $this->respond(responseJson(202, false, ['msg' => 'Check-Out already requested.']));

        $reservation['branding_logo'] = brandingLogo();

        $options = new \Dompdf\Options();
        $options->setIsRemoteEnabled(true);

        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml(view('Templates/ReservationInvoiceTemplate', ['reservation' => $reservation]));
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $file_name = "assets/reservation-invoices/RES{$reservation['RESV_ID']}-Invoice.pdf";
        file_put_contents($file_name, $dompdf->output());

        $reservation['RESV_STATUS'] = 'Checked-Out-Requested';
        $this->Reservation->save($reservation);

        return $this->respond(responseJson(200, false, ['msg' => 'Checkout request has been submitted successfully.'], ['invoice' => base_url($file_name)]));
    }
}
