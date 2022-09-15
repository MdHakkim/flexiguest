<?php

namespace App\Controllers\APIControllers\Guest;

use App\Controllers\BaseController;
use App\Controllers\Repositories\LaundryAmenitiesRepository;
use App\Models\LaundryAmenitiesOrder;
use App\Models\LaundryAmenitiesOrderDetail;
use App\Models\Product;
use CodeIgniter\API\ResponseTrait;

class LaundryAmenitiesController extends BaseController
{
    use ResponseTrait;

    private $LaundryAmenitiesRepository;
    private $Product;
    private $LaundryAmenitiesOrder;
    private $LaundryAmenitiesOrderDetail;

    public function __construct()
    {
        $this->LaundryAmenitiesRepository = new LaundryAmenitiesRepository();
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

        $data = (array) $this->request->getVar();
        $result = $this->LaundryAmenitiesRepository->placeOrder($user, $data);

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

            $orders[$index]['order_details'] = $order_details;
        }

        return $this->respond(responseJson(200, false, ['msg' => 'order list'], $orders));
    }

    public function downloadInvoice()
    {
        $customer_id = $this->request->user['USR_CUST_ID'];
        $order_id = $this->request->getVar('order_id');

        $order = $this->LaundryAmenitiesOrder
            ->select('FLXY_LAUNDRY_AMENITIES_ORDERS.*, 
            fr.RESV_ARRIVAL_DT, fr.RESV_DEPARTURE,
            fc.CUST_FIRST_NAME, fc.CUST_MIDDLE_NAME, fc.CUST_LAST_NAME, fc.CUST_ADDRESS_1, fc.CUST_ADDRESS_2, fc.CUST_ADDRESS_3,
            fc.CUST_COUNTRY, fc.CUST_STATE, fc.CUST_CITY,
            co.cname as COUNTRY_NAME,
            st.sname as STATE_NAME,
            ci.ctname as CITY_NAME,
            rm.RM_NO, rm.RM_DESC')
            ->join('FLXY_RESERVATION as fr', 'FLXY_LAUNDRY_AMENITIES_ORDERS.LAO_RESERVATION_ID = fr.RESV_ID', 'left')
            ->join('FLXY_CUSTOMER as fc', 'FLXY_LAUNDRY_AMENITIES_ORDERS.LAO_CUSTOMER_ID = fc.CUST_ID', 'left')
            ->join('FLXY_ROOM as rm', 'FLXY_LAUNDRY_AMENITIES_ORDERS.LAO_ROOM_ID = rm.RM_ID', 'left')
            ->join('COUNTRY as co', 'fc.CUST_COUNTRY = co.iso2', 'left')
            ->join('STATE as st', 'fc.CUST_STATE = st.state_code', 'left')
            ->join('CITY as ci', 'fc.CUST_CITY = ci.id', 'left')
            ->where('LAO_ID', $order_id)
            ->where('LAO_CUSTOMER_ID', $customer_id)
            ->first();

        if (empty($order))
            return $this->respond(responseJson(404, true, ['msg' => 'No order found.']));

        $order['order_details'] = $this->LaundryAmenitiesOrderDetail
            ->select('FLXY_LAUNDRY_AMENITIES_ORDER_DETAILS.*, pr.PR_NAME, pr.PR_PRICE')
            ->join('FLXY_PRODUCTS as pr', 'FLXY_LAUNDRY_AMENITIES_ORDER_DETAILS.LAOD_PRODUCT_ID = pr.PR_ID')
            ->where('LAOD_ORDER_ID', $order['LAO_ID'])
            ->findAll();

        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml(view('Templates/LaundryAmenitiesInvoiceTemplate', ['order' => $order]));
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $file_name = "assets/laundry-amenities-invoices/ORD{$order['LAO_ID']}-Invoice.pdf";
        file_put_contents($file_name, $dompdf->output());

        return $this->respond(responseJson(200, false, ['msg' => 'Invoice'], ['invoice' => base_url($file_name)]));
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
