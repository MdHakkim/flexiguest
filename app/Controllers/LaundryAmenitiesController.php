<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\CurlRequestLibrary;
use App\Libraries\EmailLibrary;
use App\Models\LaundryAmenitiesOrder;
use App\Models\LaundryAmenitiesOrderDetail;
use App\Models\Product;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\CURLRequest;

class LaundryAmenitiesController extends BaseController
{
    use ResponseTrait;

    private $Product;
    private $LaundryAmenitiesOrder;
    private $LaundryAmenitiesOrderDetail;

    public function __construct()
    {
        $this->Product = new Product();
        $this->LaundryAmenitiesOrder = new LaundryAmenitiesOrder();
        $this->LaundryAmenitiesOrderDetail = new LaundryAmenitiesOrderDetail();
    }

    public function checkUnattendedItems()
    {
        $current_datetime = date('Y-m-d H:i:s');

        $order_details = $this->LaundryAmenitiesOrderDetail
            ->join('FLXY_PRODUCTS as pr', 'FLXY_LAUNDRY_AMENITIES_ORDER_DETAILS.LAOD_PRODUCT_ID = pr.PR_ID', 'left')
            ->where("(LAOD_DELIVERY_STATUS = 'New' or LAOD_DELIVERY_STATUS = 'Processing') and LAOD_EXPIRY_DATETIME < '$current_datetime'")
            ->findAll();

        foreach ($order_details as $order_detail) {
            $data['order_detail'] = $order_detail;
            $data['from_email'] = 'notifications@farnek.com';
            $data['from_name'] = 'FlexiGuest | Hitek';

            $data['to_email'] = 'abubakar.safder@farnek.com';
            $data['to_name'] = 'abubakar';

            $data['subject'] = 'Alert! Unattended Request.';
            $data['html'] = view('EmailTemplates/RequestUnattendedAlert', $data);

            // $data['url'] = 'https://api.github.com/user';
            // $data['method'] = 'GET';
            // $data['body'] = [];

            // $curl_request = new CurlRequestLibrary();
            // $curl_request->makeRequest($data);

            $email_library = new EmailLibrary();
            echo $emailResp = $email_library->commonEmail($data);
        }
    }
}
