<?php

namespace App\Controllers\APIControllers\Guest;

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

    public function placeOrder()
    {
        $user = $this->request->user;
        $user_id = $user['USR_ID'];
        $customer_id = $user['USR_CUST_ID'];

        $params = $this->request->getVar();
        $total_payable = 0;

        $products = $this->request->getVar('products');
        foreach ($products as $product) {
            $pr = $this->Product->find($product->id);
            if (empty($pr))
                return $this->respond(responseJson(404, true, ['msg' => 'Invalid Product']));

            // $products[$index]['amount'] = $product->quanitity * $pr['PR_PRICE'];
            $product->amount = $product->quantity * $pr['PR_PRICE'];
            $total_payable += ($product->quantity * $pr['PR_PRICE']);
        }

        $order_id = $this->LaundryAmenitiesOrder->insert([
            'LAO_RESERVATION_ID' => $params->reservation_id,
            'LAO_CUSTOMER_ID' => $customer_id,
            'LAO_ROOM_ID' => $params->room_id,
            'LAO_TOTAL_PAYABLE' => $total_payable,
            'LAO_PAYMENT_METHOD' => $params->payment_method, // Pay at Reception, Samsung Pay, Credit/Debit card
            'LAO_CREATED_BY' => $user_id,
            'LAO_UPDATED_BY' => $user_id
        ]);

        if (!$order_id)
            return $this->respond(responseJson(500, true, ['msg' => 'Unable to place order.']));

        foreach ($products as $product) {
            $this->LaundryAmenitiesOrderDetail->insert([
                'LAOD_ORDER_ID' => $order_id,
                'LAOD_PRODUCT_ID' => $product->id,
                'LAOD_QUANTITY' => $product->quantity,
                'LAOD_AMOUNT' => $product->amount,
                'LAOD_CREATED_BY' => $user_id,
                'LAOD_UPDATED_BY' => $user_id
            ]);
        }

        return $this->respond(responseJson(200, false, ['msg' => 'Order placed successfully.']));
    }

    public function listOrders()
    {
        $customer_id = $this->request->user['USR_CUST_ID'];
        
        $orders = $this->LaundryAmenitiesOrder
            ->select('FLXY_LAUNDRY_AMENITIES_ORDERS.*, rm.RM_NO')
            ->join('FLXY_ROOM as rm', 'FLXY_LAUNDRY_AMENITIES_ORDERS.LAO_ROOM_ID = rm.RM_ID')
            ->where('LAO_CUSTOMER_ID', $customer_id)
            ->findAll();

        foreach ($orders as $index => $order) {
            $orders[$index]['order_details'] = $this->LaundryAmenitiesOrderDetail
                ->select('FLXY_LAUNDRY_AMENITIES_ORDER_DETAILS.*, pr.PR_NAME')
                ->join('FLXY_PRODUCTS as pr', 'FLXY_LAUNDRY_AMENITIES_ORDER_DETAILS.LAOD_PRODUCT_ID = pr.PR_ID')
                ->where('LAOD_ORDER_ID', $order['LAO_ID'])
                ->findAll();
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

        if(empty($order))
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
}
