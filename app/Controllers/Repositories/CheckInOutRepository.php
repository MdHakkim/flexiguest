<?php

namespace App\Controllers\Repositories;

use App\Controllers\BaseController;
use App\Models\Reservation;
use CodeIgniter\API\ResponseTrait;

class CheckInOutRepository extends BaseController
{
    use ResponseTrait;

    private $Reservation;

    public function __construct()
    {
        $this->Reservation = new Reservation();
    }

    public function makeCheckoutRequest($user, $reservation)
    {
        $customer_id = $user['USR_CUST_ID'];

        if ($reservation['RESV_NAME'] != $customer_id)
            return responseJson(404, true, ['msg' => 'Invalid reservation.']);

        if ($reservation['RESV_STATUS'] == 'Checked-Out')
            return responseJson(202, true, ['msg' => 'This reservation is already checked-out.']);

        if ($reservation['RESV_STATUS'] == 'Checked-Out-Requested')
            return responseJson(202, true, ['msg' => 'Check-Out already requested.']);

        $reservation['branding_logo'] = brandingLogo();

        $options = new \Dompdf\Options();
        $options->setIsRemoteEnabled(true);

        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml(view('Templates/ReservationInvoiceTemplate', ['reservation' => $reservation]));
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $file_name = "assets/reservation-invoices/RES{$reservation['RESV_ID']}-Invoice.pdf";
        file_put_contents($file_name, $dompdf->output());

        return responseJson(200, false, ['msg' => 'Checkout request has been submitted successfully.'], ['invoice' => base_url($file_name), 'reservation_charges' => $reservation['RESV_RATE']]);
    }
}
