<?php

namespace App\Controllers\APIControllers\Guest;

use App\Controllers\BaseController;
use App\Controllers\Repositories\ConciergeRepository;
use App\Controllers\Repositories\LaundryAmenitiesRepository;
use App\Models\Documents;
use App\Models\Reservation;
use App\Models\VaccineDetail;
use CodeIgniter\API\ResponseTrait;

class ProfileController extends BaseController
{
    use ResponseTrait;

    private $Documents;
    private $VaccineDetail;
    private $Reservation;
    private $LaundryAmenitiesRepository;
    private $ConciergeRepository;

    public function __construct()
    {
        $this->Documents = new Documents();
        $this->VaccineDetail = new VaccineDetail();
        $this->Reservation = new Reservation();
        $this->LaundryAmenitiesRepository = new LaundryAmenitiesRepository();
        $this->ConciergeRepository = new ConciergeRepository();
    }

    public function allDocuments() 
    {
        $customer_id = $this->request->user['USR_CUST_ID'];
        
        $identity_documents = [];
        $documents = $this->Documents->where('DOC_CUST_ID', $customer_id)->where('DOC_FILE_TYPE', 'PROOF')->findAll();
        foreach($documents as $index => $document){
            $attachments = [];
            if ($document['DOC_FILE_PATH']) {
                $attachments = explode(",", $document['DOC_FILE_PATH']);

                foreach ($attachments as $j => $attachment) {
                    $name = getOriginalFileName($attachment);
                    $url = base_url("assets/Uploads/UserDocuments/proof/$attachment");

                    $attachment_array = explode(".", $attachment);
                    $type = getFileType(end($attachment_array));

                    $identity_documents[] = [
                        'resv_id' => $document['DOC_RESV_ID'],
                        'name' => $name, 
                        'url' => $url, 
                        'type' => $type,
                        'category' => 'documents',
                    ];
                }
            }
        }

        $vaccine_documents = [];
        $vaccine_details = $this->VaccineDetail->where('VACC_CUST_ID', $customer_id)->findAll();
        foreach($vaccine_details as $index => $vaccine_detail){
            $attachments = [];
            if ($vaccine_detail['VACC_FILE_PATH']) {
                $attachments = explode(",", $vaccine_detail['VACC_FILE_PATH']);

                foreach ($attachments as $j => $attachment) {
                    $name = getOriginalFileName($attachment);
                    $url = base_url("assets/Uploads/UserDocuments/vaccination/$attachment");

                    $attachment_array = explode(".", $attachment);
                    $type = getFileType(end($attachment_array));

                    $vaccine_documents[] = [
                        'resv_id' => $vaccine_detail['VACC_RESV_ID'],
                        'name' => $name, 
                        'url' => $url, 
                        'type' => $type,
                        'category' => 'vaccine',
                    ];
                }
            }
        }

        $invoices = [];
        $registation_cards = [];
        $reservations = $this->Reservation->where('RESV_NAME', $customer_id)->findAll();
        foreach($reservations as $reservation) {
            $folderPath = "assets/reservation-invoices/RES{$reservation['RESV_ID']}-Invoice.pdf";
            if (file_exists($folderPath)) {
                $invoices[] = [
                    'resv_id' => $reservation['RESV_ID'],
                    'name' => "RES{$reservation['RESV_ID']}-Invoice.pdf",
                    'url' => base_url($folderPath),
                    'type' => 'pdf',
                    'category' => 'invoice'
                ];
            }

            $folderPath = "assets/reservation-registration-cards/RES{$reservation['RESV_ID']}-RC.pdf";
            if (file_exists($folderPath)) {
                $registation_cards[] = [
                    'resv_id' => $reservation['RESV_ID'],
                    'name' => "RES{$reservation['RESV_ID']}-RC.pdf",
                    'url' => base_url($folderPath),
                    'type' => 'pdf',
                    'category' => 'registrationCard'
                ];
            }
        }

        $orders = $this->LaundryAmenitiesRepository->getLAOrders("LAO_CUSTOMER_ID = $customer_id and LAO_PAYMENT_STATUS = 'Paid'");
        foreach($orders as $order) {
            $folderPath = "assets/laundry-amenities-invoices/LAO{$order['LAO_ID']}-Invoice.pdf";
            if (file_exists($folderPath)) {
                $invoices[] = [
                    'resv_id' => $order['LAO_RESERVATION_ID'],
                    'name' => "LAO{$order['LAO_ID']}-Invoice.pdf",
                    'url' => base_url($folderPath),
                    'type' => 'pdf',
                    'category' => 'invoice'
                ];
            }
        }

        $concierge_requests = $this->ConciergeRepository->getConciergeRequests("CR_CUSTOMER_ID = $customer_id and CR_PAYMENT_STATUS = 'Paid'");
        foreach($concierge_requests as $concierge_request) {
            $folderPath = "assets/invoices/concierge-invoices/CR{$concierge_request['CR_ID']}-Invoice.pdf";
            if (file_exists($folderPath)) {
                $invoices[] = [
                    'resv_id' => $concierge_request['CR_RESERVATION_ID'],
                    'name' => "CR{$concierge_request['CR_ID']}-Invoice.pdf",
                    'url' => base_url($folderPath),
                    'type' => 'pdf',
                    'category' => 'invoice'
                ];
            }
        }
        
        $output = array_merge($identity_documents, $vaccine_documents, $invoices, $registation_cards);
        return $this->respond(responseJson(200, false, ['msg' => 'All Documents'], $output));
    }
}