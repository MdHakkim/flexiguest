<?php

namespace App\Controllers;

use App\Controllers\Repositories\CheckInOutRepository;
use App\Controllers\Repositories\ConciergeRepository;
use App\Controllers\Repositories\LaundryAmenitiesRepository;
use App\Controllers\Repositories\ReservationRepository;
use App\Controllers\Repositories\TransportRequestRepository;
use CodeIgniter\API\ResponseTrait;
use App\Models\Reservation;
use App\Models\ShareReservations;

class CheckInOutController extends BaseController
{

    use ResponseTrait;

    private $DB;
    private $CheckInOutRepository;
    private $ReservationRepository;
    private $LaundryAmenitiesRepository;
    private $TransportRequestRepository;
    private $ConciergeRepository;
    private $Reservation;
    private $ShareReservations;

    public function __construct()
    {
        $this->DB = \Config\Database::connect();
        $this->CheckInOutRepository = new CheckInOutRepository();
        $this->ReservationRepository = new ReservationRepository();
        $this->LaundryAmenitiesRepository = new LaundryAmenitiesRepository();
        $this->TransportRequestRepository = new TransportRequestRepository();
        $this->ConciergeRepository = new ConciergeRepository();
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

        $reservation['branding_logo'] = brandingLogo();

        $options = new \Dompdf\Options();
        $options->setIsRemoteEnabled(true);

        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml(view('Templates/ReservationInvoiceTemplate', ['reservation' => $reservation]));
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $file_name = "assets/reservation-invoices/RES{$reservation['RESV_ID']}-Invoice.pdf";
        file_put_contents($file_name, $dompdf->output());

        $reservation['RESV_STATUS'] = 'Checked-Out';
        $this->Reservation->save($reservation);

        return $this->respond(responseJson(200, false, ['msg' => 'Reservation checked-out successfully.'], ['invoice' => base_url($file_name)]));
    }

    /** ---------------------------API--------------------------- */
    public function makeCheckoutRequest($reservation_id)
    {
        $user = $this->request->user;
        $customer_id = $user['USR_CUST_ID'];

        // pending Laundry Amenities
        $where_condition = "LAO_RESERVATION_ID = $reservation_id and LAO_CUSTOMER_ID = $customer_id and LAO_PAYMENT_STATUS = 'UnPaid'";
        $pending_payments = $this->LaundryAmenitiesRepository->getLAOrders($where_condition);
        if (!empty($pending_payments))
            return $this->respond(responseJson(202, true, ['msg' => 'Please clear payments of all laundry amenities orders.']));

        // pending Transport requests
        $where_condition = "TR_RESERVATION_ID = $reservation_id and TR_CUSTOMER_ID = $customer_id and TR_PAYMENT_STATUS = 'UnPaid'";
        $pending_requests = $this->TransportRequestRepository->getTransportRequests($where_condition);
        if (!empty($pending_requests))
            return $this->respond(responseJson(202, true, ['msg' => 'Please clear payments of all transport requests.']));

        // pending Concierge requests
        $where_condition = "CR_RESERVATION_ID = $reservation_id and CR_CUSTOMER_ID = $customer_id and CR_PAYMENT_STATUS = 'UnPaid'";
        $pending_concierge_requests = $this->ConciergeRepository->getConciergeRequests($where_condition);
        if (!empty($pending_concierge_requests))
            return $this->respond(responseJson(202, true, ['msg' => 'Please clear payments of all concierge requests.']));

        $reservation = $this->ReservationRepository->reservationById($reservation_id);
        
        $result = $this->CheckInOutRepository->makeCheckoutRequest($user, $reservation);
        if($result['SUCCESS'] == 200)
            $this->ReservationRepository->updateReservation(['RESV_STATUS' => 'Checked-Out-Requested'], "RESV_ID = $reservation_id");

        return $this->respond($result);
    }
}
