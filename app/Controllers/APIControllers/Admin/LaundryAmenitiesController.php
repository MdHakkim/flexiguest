<?php

namespace App\Controllers\APIControllers\Admin;

use App\Controllers\BaseController;
use App\Models\LaundryAmenitiesOrder;
use App\Models\LaundryAmenitiesOrderDetail;
use App\Models\Product;
use CodeIgniter\API\ResponseTrait;

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

    public function ordersList()
    {
        $orders = $this->LaundryAmenitiesOrderDetail
            ->join('FLXY_LAUNDRY_AMENITIES_ORDERS as lao', 'FLXY_LAUNDRY_AMENITIES_ORDER_DETAILS.LAOD_ORDER_ID = lao.LAO_ID')
            ->join('FLXY_PRODUCTS as pr', 'FLXY_LAUNDRY_AMENITIES_ORDER_DETAILS.LAOD_PRODUCT_ID = pr.PR_ID')
            ->orderBy('LAOD_ID', 'desc')
            ->findAll();

        foreach ($orders as $index => $order) {
            $orders[$index]['PR_IMAGE'] = base_url($order['PR_IMAGE']);
        }

        return $this->respond(responseJson(200, false, ['msg' => 'Orders list'], $orders));
    }

    public function updateDeliveryStatus()
    {
        $order_detail_id = $this->request->getVar('order_detail_id');
        $delivery_status = $this->request->getVar('delivery_status'); // status => New, Processing, Delivered, Rejected, Acknowledged

        $order_detail = $this->LaundryAmenitiesOrderDetail->find($order_detail_id);
        if(empty($order_detail))
            return $this->respond(responseJson(404, true, ['msg' => 'No order found.']));
        
        $payment_status = $this->LaundryAmenitiesOrder->find($order_detail['LAOD_ORDER_ID']);
        if(empty($payment_status))
            return $this->respond(responseJson(404, true, ['msg' => 'No order found.']));

        if($payment_status['LAO_PAYMENT_STATUS'] == 'UnPaid' || $payment_status['LAO_PAYMENT_STATUS'] == 'Payment Processing')
            return $this->respond(responseJson(202, true, ['msg' => 'Can\'t change status becasue payment is still pending for this order.']));
        
        $this->LaundryAmenitiesOrderDetail->update($order_detail_id, ['LAOD_DELIVERY_STATUS' => $delivery_status]);
        return $this->respond(responseJson(200, false, ['msg' => 'Status updated successfully.']));
    }
}
