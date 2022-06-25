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
        $reservation = $this->Reservation->find($reservation_id);

        if($reservation['RESV_NAME'] != $customer_id)
            return $this->respond(responseJson(200, false, ['msg' => 'Invalid request.']));

        if ($reservation['RESV_STATUS'] == 'Checked-Out')
            return $this->respond(responseJson(200, false, ['msg' => 'This reservation is already checked-out.']));

        if ($reservation['RESV_STATUS'] == 'Checked-Out-Requested')
            return $this->respond(responseJson(200, false, ['msg' => 'Check-Out already requested.']));

        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml(view('includes/InvoiceTemplate', $reservation));
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        $file_name = "assets/invoices/RES{$reservation['RESV_ID']}-Invoice.pdf";
        file_put_contents($file_name, $dompdf->output()); 
        // $dompdf->stream("RES{$reservation['RESV_ID']}-Invoice.pdf");

        // $reservation['RESV_STATUS'] = 'Checked-Out-Requested';
        // $this->Reservation->save($reservation);

        return $this->respond(responseJson(200, false, ['msg' => 'Checkout request has been submitted successfully.'], base_url($file_name)));
    }
}
