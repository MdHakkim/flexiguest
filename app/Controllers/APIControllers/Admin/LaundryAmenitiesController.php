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
        $user = $this->request->user;

        if ($user['USR_ROLE_ID'] == '3') {
            $orders = $this->LaundryAmenitiesOrderDetail
                ->join('FLXY_LAUNDRY_AMENITIES_ORDERS as lao', 'FLXY_LAUNDRY_AMENITIES_ORDER_DETAILS.LAOD_ORDER_ID = lao.LAO_ID')
                ->join('FLXY_PRODUCTS as pr', 'FLXY_LAUNDRY_AMENITIES_ORDER_DETAILS.LAOD_PRODUCT_ID = pr.PR_ID')
                ->where('LAOD_ATTENDANT_ID', $user['USR_ID'])
                ->orderBy('LAOD_ID', 'desc')
                ->findAll();
        } else {
            $orders = $this->LaundryAmenitiesOrderDetail
                ->join('FLXY_LAUNDRY_AMENITIES_ORDERS as lao', 'FLXY_LAUNDRY_AMENITIES_ORDER_DETAILS.LAOD_ORDER_ID = lao.LAO_ID')
                ->join('FLXY_PRODUCTS as pr', 'FLXY_LAUNDRY_AMENITIES_ORDER_DETAILS.LAOD_PRODUCT_ID = pr.PR_ID')
                ->orderBy('LAOD_ID', 'desc')
                ->findAll();
        }

        return $this->respond(responseJson(200, false, ['msg' => 'Orders list'], $orders));
    }

    public function updateDeliveryStatus()
    {
        $order_detail_id = $this->request->getVar('order_detail_id');
        $delivery_status = $this->request->getVar('delivery_status'); // status => New, Processing, Delivered, Rejected, Acknowledged
        $attendant_id = $this->request->getVar('attendant_id');

        $rules = ['delivery_status' => ['label' => 'delivery status', 'rules' => 'required|customInArray']];
        if ($delivery_status == 'Processing')
            $rules['attendant_id'] = ['label' => 'attendant', 'rules' => 'required'];

        $messages = [
            'delivery_status' => [
                'customInArray' => 'invalid status'
            ]
        ];

        if (!$this->validate($rules, $messages))
            return $this->respond(responseJson(403, true, $this->validator->getErrors()));

        $order_detail = $this->LaundryAmenitiesOrderDetail->find($order_detail_id);
        if (empty($order_detail))
            return $this->respond(responseJson(404, true, ['msg' => 'No order found.']));
        $old_delivery_status = $order_detail['LAOD_DELIVERY_STATUS'];

        $payment_status = $this->LaundryAmenitiesOrder->find($order_detail['LAOD_ORDER_ID']);
        if (empty($payment_status))
            return $this->respond(responseJson(404, true, ['msg' => 'No order found.']));

        if ($payment_status['LAO_PAYMENT_STATUS'] == 'UnPaid')
            return $this->respond(responseJson(202, true, ['msg' => 'Can\'t change status becasue payment is still pending for this order.']));

        if ($old_delivery_status == 'New' && $delivery_status != 'Processing' && $delivery_status != 'Rejected')
            return $this->respond(responseJson(202, true, ['msg' => 'Can\'t change status, becasue order is not in processing.']));

        if ($old_delivery_status == 'Processing' && $delivery_status != 'Delivered')
            return $this->respond(responseJson(202, true, ['msg' => 'Can\'t change status, becasue order is not delivered yet.']));

        if ($old_delivery_status == 'Delivered' || $old_delivery_status == 'Rejected')
            return $this->respond(responseJson(202, true, ['msg' => 'Can\'t change status, becasue this order is already Delivered or Rejected.']));

        $data = ['LAOD_DELIVERY_STATUS' => $delivery_status];
        if ($delivery_status == 'Processing')
            $data = [
                'LAOD_DELIVERY_STATUS' => $delivery_status,
                'LAOD_ATTENDANT_ID' => $attendant_id,
                'LAOD_ASSIGNED_AT'  => date('Y-m-d H:i:s')
            ];

        $this->LaundryAmenitiesOrderDetail->update($order_detail_id, $data);
        return $this->respond(responseJson(200, false, ['msg' => 'Status updated successfully.']));
    }
}
