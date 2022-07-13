<?php

namespace App\Controllers\APIControllers\Guest;

use App\Controllers\BaseController;
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

    public function __construct()
    {
        $this->Documents = new Documents();
        $this->VaccineDetail = new VaccineDetail();
        $this->Reservation = new Reservation();
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

                    $attachments[$j] = [
                        'RESV_ID' => $document['DOC_RESV_ID'],
                        'NAME' => $name, 
                        'URL' => $url, 
                        'TYPE' => $type,
                        'CATEGORY_TYPE' => 'documents',
                    ];
                }
            }
            $identity_documents[] = $attachments;
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

                    $attachments[$j] = [
                        'RESV_ID' => $vaccine_detail['VACC_RESV_ID'],
                        'NAME' => $name, 
                        'URL' => $url, 
                        'TYPE' => $type,
                        'CATEGORY_TYPE' => 'vaccine',
                    ];
                }
            }
            $vaccine_documents[] = $attachments;
        }

        $invoices = [];
        $reservations = $this->Reservation->where('RESV_NAME', $customer_id)->where('RESV_STATUS', 'Checked-Out')->findAll();
        foreach($reservations as $reservation) {
            $folderPath = "assets/reservation-invoices/RES{$reservation['RESV_ID']}-Invoice.pdf";
            if (file_exists($folderPath)) {
                $invoices[] = [
                    'RESV_ID' => $reservation['RESV_ID'],
                    'NAME' => "RES{$reservation['RESV_ID']}-Invoice.pdf",
                    'URL' => base_url($folderPath),
                    'TYPE' => 'pdf',
                    'CATEGORY_TYPE' => 'invoice'
                ];
            }
        }
        
        $output = array_merge($identity_documents, $vaccine_documents, $invoices);
        return $this->respond(responseJson(200, false, ['msg' => 'All Documents'], $output));
    }
}