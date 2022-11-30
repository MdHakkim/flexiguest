<?php

namespace App\Controllers\APIControllers\Guest;

use App\Controllers\BaseController;
use App\Controllers\Repositories\ConciergeRepository;
use App\Controllers\Repositories\LaundryAmenitiesRepository;
use App\Controllers\Repositories\RestaurantRepository;
use App\Controllers\Repositories\TransportRequestRepository;
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
    private $TransportRequestRepository;
    private $RestaurantRepository;

    public function __construct()
    {
        $this->Documents = new Documents();
        $this->VaccineDetail = new VaccineDetail();
        $this->Reservation = new Reservation();
        $this->LaundryAmenitiesRepository = new LaundryAmenitiesRepository();
        $this->ConciergeRepository = new ConciergeRepository();
        $this->TransportRequestRepository = new TransportRequestRepository();
        $this->RestaurantRepository = new RestaurantRepository();
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

        $invoices = $receipts = $registation_cards = [];
        
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

        $orders = $this->LaundryAmenitiesRepository->getLAOrders("LAO_CUSTOMER_ID = $customer_id");
        foreach($orders as $order) {
            $folderPath = "assets/invoices/laundry-amenities-invoices/LAO{$order['LAO_ID']}-Invoice.pdf";
            if (file_exists($folderPath)) {
                $invoices[] = [
                    'resv_id' => $order['LAO_RESERVATION_ID'],
                    'name' => "LAO{$order['LAO_ID']}-Invoice.pdf",
                    'url' => base_url($folderPath),
                    'type' => 'pdf',
                    'category' => 'invoice'
                ];
            }

            $folderPath = "assets/receipts/laundry-amenities-receipts/LAO{$order['LAO_ID']}-Receipt.pdf";
            if ($order['LAO_PAYMENT_STATUS'] == 'Paid' && file_exists($folderPath)) {
                $receipts[] = [
                    'resv_id' => $order['LAO_RESERVATION_ID'],
                    'name' => "LAO{$order['LAO_ID']}-Receipt.pdf",
                    'url' => base_url($folderPath),
                    'type' => 'pdf',
                    'category' => 'receipt'
                ];
            }
        }

        $concierge_requests = $this->ConciergeRepository->getConciergeRequests("CR_CUSTOMER_ID = $customer_id");
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

            $folderPath = "assets/receipts/concierge-receipts/CR{$concierge_request['CR_ID']}-Receipt.pdf";
            if ($concierge_request['CR_PAYMENT_STATUS'] == 'Paid' && file_exists($folderPath)) {
                $receipts[] = [
                    'resv_id' => $concierge_request['CR_RESERVATION_ID'],
                    'name' => "CR{$concierge_request['CR_ID']}-Receipt.pdf",
                    'url' => base_url($folderPath),
                    'type' => 'pdf',
                    'category' => 'receipt'
                ];
            }
        }
        
        $transport_requests = $this->TransportRequestRepository->getTransportRequests("TR_CUSTOMER_ID = $customer_id");
        foreach($transport_requests as $transport_request) {
            $folderPath = "assets/invoices/transport-request-invoices/TR{$transport_request['TR_ID']}-Invoice.pdf";
            if (file_exists($folderPath)) {
                $invoices[] = [
                    'resv_id' => $transport_request['TR_RESERVATION_ID'],
                    'name' => "TR{$transport_request['TR_ID']}-Invoice.pdf",
                    'url' => base_url($folderPath),
                    'type' => 'pdf',
                    'category' => 'invoice'
                ];
            }

            $folderPath = "assets/receipts/transport-request-receipts/TR{$transport_request['TR_ID']}-Receipt.pdf";
            if ($transport_request['TR_PAYMENT_STATUS'] == 'Paid' && file_exists($folderPath)) {
                $receipts[] = [
                    'resv_id' => $transport_request['TR_RESERVATION_ID'],
                    'name' => "TR{$transport_request['TR_ID']}-Receipt.pdf",
                    'url' => base_url($folderPath),
                    'type' => 'pdf',
                    'category' => 'receipt'
                ];
            }
        }

        $orders = $this->RestaurantRepository->getOrdersList("RO_CUSTOMER_ID = $customer_id");
        foreach($orders as $order) {
            $folderPath = "assets/invoices/restaurant-order-invoices/RO{$order['RO_ID']}-Invoice.pdf";
            if (file_exists($folderPath)) {
                $invoices[] = [
                    'resv_id' => $order['RO_RESERVATION_ID'],
                    'name' => "RO{$order['RO_ID']}-Invoice.pdf",
                    'url' => base_url($folderPath),
                    'type' => 'pdf',
                    'category' => 'invoice'
                ];
            }
            
            $folderPath = "assets/receipts/restaurant-order-receipts/RO{$order['RO_ID']}-Receipt.pdf";
            if ($order['RO_PAYMENT_STATUS'] == 'Paid' && file_exists($folderPath)) {
                $receipts[] = [
                    'resv_id' => $order['RO_RESERVATION_ID'],
                    'name' => "RO{$order['RO_ID']}-Receipt.pdf",
                    'url' => base_url($folderPath),
                    'type' => 'pdf',
                    'category' => 'receipt'
                ];
            }
        }
        
        $output = array_merge($identity_documents, $vaccine_documents, $invoices, $receipts, $registation_cards);
        return $this->respond(responseJson(200, false, ['msg' => 'All Documents'], $output));
    }
}