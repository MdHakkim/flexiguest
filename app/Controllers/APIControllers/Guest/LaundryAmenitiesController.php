<?php

namespace App\Controllers\APIControllers\Guest;

use App\Controllers\BaseController;
use App\Controllers\Repositories\LaundryAmenitiesRepository;
use App\Controllers\Repositories\PaymentRepository;
use App\Models\LaundryAmenitiesOrder;
use App\Models\LaundryAmenitiesOrderDetail;
use App\Models\Product;
use CodeIgniter\API\ResponseTrait;

class LaundryAmenitiesController extends BaseController
{
    use ResponseTrait;

    private $LaundryAmenitiesRepository;
    private $PaymentRepository;
    private $Product;
    private $LaundryAmenitiesOrder;
    private $LaundryAmenitiesOrderDetail;

    public function __construct()
    {
        $this->LaundryAmenitiesRepository = new LaundryAmenitiesRepository();
        $this->PaymentRepository = new PaymentRepository();
        $this->Product = new Product();
        $this->LaundryAmenitiesOrder = new LaundryAmenitiesOrder();
        $this->LaundryAmenitiesOrderDetail = new LaundryAmenitiesOrderDetail();
    }

    public function placeOrder()
    {
        $user = $this->request->user;
        $validate = $this->validate($this->LaundryAmenitiesRepository->validationRules());

        if (!$validate)
            return $this->respond(responseJson(403, true, $this->validator->getErrors()));

        $data = json_decode(json_encode($this->request->getVar()), true);
        $result = $this->LaundryAmenitiesRepository->placeOrder($user, $data);

        if ($result['SUCCESS'] == 200 && $data['payment_method'] == 'Credit/Debit card') {
            $data = $result['RESPONSE']['OUTPUT'];
            $result = $this->PaymentRepository->createPaymentIntent($user, $data);
        }

        return $this->respond($result);
    }

    public function listOrders()
    {
        $customer_id = $this->request->user['USR_CUST_ID'];

        $orders = $this->LaundryAmenitiesOrder
            ->select('FLXY_LAUNDRY_AMENITIES_ORDERS.*, rm.RM_NO')
            ->join('FLXY_ROOM as rm', 'FLXY_LAUNDRY_AMENITIES_ORDERS.LAO_ROOM_ID = rm.RM_ID')
            ->where('LAO_CUSTOMER_ID', $customer_id)
            ->orderBy('LAO_ID', 'desc')
            ->findAll();

        foreach ($orders as $index => $order) {
            $order_details = $this->LaundryAmenitiesOrderDetail
                ->select('FLXY_LAUNDRY_AMENITIES_ORDER_DETAILS.*, pr.PR_NAME')
                ->join('FLXY_PRODUCTS as pr', 'FLXY_LAUNDRY_AMENITIES_ORDER_DETAILS.LAOD_PRODUCT_ID = pr.PR_ID')
                ->where('LAOD_ORDER_ID', $order['LAO_ID'])
                ->findAll();

            if (count($order_details) == 1)
                $orders[$index]['LAO_DELIVERY_STATUS'] = $order_details[0]['LAOD_DELIVERY_STATUS'];
            else {

                $orders[$index]['LAO_DELIVERY_STATUS'] = 'Delivered';
                foreach ($order_details as $order_detail) {
                    if ($order_detail['LAOD_DELIVERY_STATUS'] == 'New' || $order_detail['LAOD_DELIVERY_STATUS'] == 'Processing') {
                        $orders[$index]['LAO_DELIVERY_STATUS'] = 'New';
                        break;
                    }

                    if ($order_detail['LAOD_DELIVERY_STATUS'] == 'Cancelled') {
                        $orders[$index]['LAO_DELIVERY_STATUS'] = 'Cancelled';
                        break;
                    }
                }
            }

            $orders[$index]['order_details'] = $order_details;
        }

        return $this->respond(responseJson(200, false, ['msg' => 'order list'], $orders));
    }

    public function downloadInvoice()
    {
        $order_id = $this->request->getVar('order_id');

        $file_name = "assets/laundry-amenities-invoices/LAO{$order_id}-Invoice.pdf";
        if (file_exists($file_name))
            $result = responseJson(200, false, ['msg' => 'Invoice.'], ['invoice' => base_url($file_name)]);
        else
            $result = responseJson(202, true, ['msg' => 'Invoice not available.']);

        return $this->respond($result);
    }

    public function acknowledgedDelivery()
    {
        $order_detail_id = $this->request->getVar('order_detail_id');
        $delivery_status = 'Acknowledged'; // status => New, Processing, Delivered, Rejected, Acknowledged

        $order_detail = $this->LaundryAmenitiesOrderDetail->find($order_detail_id);
        if (empty($order_detail))
            return $this->respond(responseJson(404, true, ['msg' => 'No order found.']));

        if ($order_detail['LAOD_DELIVERY_STATUS'] != 'Delivered' && $order_detail['LAOD_DELIVERY_STATUS'] != 'Acknowledged')
            return $this->respond(responseJson(404, true, ['msg' => 'This item is not delivered yet.']));

        $this->LaundryAmenitiesOrderDetail->update($order_detail_id, ['LAOD_DELIVERY_STATUS' => $delivery_status]);
        return $this->respond(responseJson(200, false, ['msg' => 'Status updated to Acknowledged successfully.']));
    }

    public function cancelOrder()
    {
        $order_id = $this->request->getVar('order_id');

        $order = $this->LaundryAmenitiesOrder->find($order_id);
        if (empty($order))
            return $this->respond(responseJson(404, true, ['msg' => 'No order found.']));
        if ($order['LAO_PAYMENT_STATUS'] == 'Paid')
            return $this->respond(responseJson(202, true, ['msg' => 'Can\'t cancel because Payment is completed for this order.']));

        $order_details = $this->LaundryAmenitiesOrderDetail->where('LAOD_ORDER_ID', $order_id)->findAll();
        foreach ($order_details as $order_detail) {
            if ($order_detail['LAOD_DELIVERY_STATUS'] != 'New')
                return $this->respond(responseJson(202, true, ['msg' => 'Can\'t cancel because order is alreadt in processing or delivered.']));
        }

        foreach ($order_details as $order_detail) {
            $order_detail['LAOD_DELIVERY_STATUS'] = 'Cancelled';
            $this->LaundryAmenitiesOrderDetail->save($order_detail);
        }

        return $this->respond(responseJson(200, false, ['msg' => 'Order is cancelled.']));
    }
}
