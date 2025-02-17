<?php

namespace App\Controllers\Repositories;

use App\Controllers\BaseController;
use App\Models\LaundryAmenitiesOrder;
use App\Models\LaundryAmenitiesOrderDetail;
use App\Models\Product;
use CodeIgniter\API\ResponseTrait;

class LaundryAmenitiesRepository extends BaseController
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

    public function validationRules()
    {
        return [
            'room_id' => [
                'label' => 'room',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Please select a room.'
                ]
            ],
            'products' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Please add atleast one product',
                ]
            ],
            'reservation_id' => ['label' => 'reservation', 'rules' => 'required'],
            'payment_method' => [
                'label' => 'payment method',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Please select a payment method.'
                ]
            ]
        ];
    }

    public function updateOrderById($data)
    {
        return $this->LaundryAmenitiesOrder->save($data);
    }

    public function orderById($id)
    {
        return $this->LaundryAmenitiesOrder->find($id);
    }

    public function placeOrder($user, $data)
    {
        $user_id = $user['USR_ID'];
        $customer_id = $user['USR_CUST_ID'];
        $total_payable = 0;

        foreach ($data['products'] as $index => $product) {
            $pr = $this->Product->find($product['id']);
            if (empty($pr))
                return responseJson(404, true, ['msg' => 'Invalid Product']);

            if ($pr['PR_QUANTITY'] == 0)
                return responseJson(202, true, ['msg' => "{$pr['PR_NAME']} is out of stock."]);
            else if ($product['quantity'] > $pr['PR_QUANTITY'])
                return responseJson(202, true, ['msg' => "We have {$pr['PR_QUANTITY']} {$pr['PR_NAME']} left in stock. So you can order {$pr['PR_QUANTITY']} {$pr['PR_NAME']} only."]);

            $data['products'][$index]['expiry_date'] = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s')) + ($pr['PR_ESCALATED_HOURS'] * 60 * 60) + ($pr['PR_ESCALATED_MINS'] * 60));
            $data['products'][$index]['amount'] = $product['quantity'] * $pr['PR_PRICE'];
            $total_payable += ($product['quantity'] * $pr['PR_PRICE']);
        }

        $order_id = $this->LaundryAmenitiesOrder->insert([
            'LAO_RESERVATION_ID' => $data['reservation_id'],
            'LAO_CUSTOMER_ID' => $customer_id,
            'LAO_ROOM_ID' => $data['room_id'],
            'LAO_TOTAL_PAYABLE' => $total_payable,
            'LAO_PAYMENT_METHOD' => $data['payment_method'], // Pay at Reception, Samsung Pay, Credit/Debit card
            'LAO_CREATED_BY' => $user_id,
            'LAO_UPDATED_BY' => $user_id
        ]);

        if (!$order_id)
            return responseJson(500, true, ['msg' => 'Unable to place order.']);

        foreach ($data['products'] as $product) {
            $pr = $this->Product->find($product['id']);
            $pr['PR_QUANTITY'] = $pr['PR_QUANTITY'] - $product['quantity'];
            $this->Product->save($pr);

            $this->LaundryAmenitiesOrderDetail->insert([
                'LAOD_ORDER_ID' => $order_id,
                'LAOD_PRODUCT_ID' => $product['id'],
                'LAOD_QUANTITY' => $product['quantity'],
                'LAOD_AMOUNT' => $product['amount'],
                'LAOD_EXPIRY_DATETIME' => $product['expiry_date'],
                'LAOD_CREATED_BY' => $user_id,
                'LAOD_UPDATED_BY' => $user_id
            ]);
        }

        $this->generateOrderInvoice($order_id);

        if ($data['payment_method'] == 'Credit/Debit card') {
            $data = [
                'amount' => $total_payable,
                'model' => 'FLXY_LAUNDRY_AMENITIES_ORDERS',
                'model_id' => $order_id,
                'reservation_id' => $data['reservation_id'],
            ];
        }

        return responseJson(200, false, ['msg' => 'Order placed successfully.'], $data);
    }

    public function getLAOrders($where_condition = "1 = 1")
    {
        return $this->LaundryAmenitiesOrder
            ->where($where_condition)
            ->findAll();
    }

    public function getLAOrder($where_condition)
    {
        $order = $this->LaundryAmenitiesOrder
            ->select('*, 
                co.cname as COUNTRY_NAME,
                st.sname as STATE_NAME,
                ci.ctname as CITY_NAME')
            ->join('FLXY_RESERVATION', 'LAO_RESERVATION_ID = RESV_ID', 'left')
            ->join('FLXY_CUSTOMER', 'LAO_CUSTOMER_ID = CUST_ID', 'left')
            ->join('FLXY_ROOM', 'RESV_ROOM = RM_NO', 'left')
            ->join('COUNTRY as co', 'CUST_COUNTRY = co.iso2', 'left')
            ->join('STATE as st', 'CUST_STATE = st.state_code', 'left')
            ->join('CITY as ci', 'CUST_CITY = ci.id', 'left')
            ->where($where_condition)
            ->first();

        if(empty($order))
            return null;

        $order['order_details'] = $this->LaundryAmenitiesOrderDetail
            ->select('FLXY_LAUNDRY_AMENITIES_ORDER_DETAILS.*, pr.PR_NAME, pr.PR_PRICE')
            ->join('FLXY_PRODUCTS as pr', 'FLXY_LAUNDRY_AMENITIES_ORDER_DETAILS.LAOD_PRODUCT_ID = pr.PR_ID')
            ->where('LAOD_ORDER_ID', $order['LAO_ID'])
            ->findAll();

        return $order;
    }

    public function generateOrderInvoice($order_id, $transaction_id = null)
    {
        $order = $this->getLAOrder("LAO_ID = $order_id");
        if(empty($order))
            return null;

        $order['transaction_id'] = $transaction_id;

        $view = 'Templates/LaundryAmenitiesInvoiceTemplate';
        if (empty($transaction_id))
            $file_name = "assets/invoices/laundry-amenities-invoices/LAO{$order_id}-Invoice.pdf";
        else
            $file_name = "assets/receipts/laundry-amenities-receipts/LAO{$order_id}-Receipt.pdf";

        generateInvoice($file_name, $view, ['data' => $order]);
        return $file_name;
    }

    public function laundryAmenitiesRevenue()
    {
        $revenue = 0;
        $orders = $this->getLAOrders("LAO_PAYMENT_STATUS = 'Paid'");
        foreach($orders as $order) {
            $revenue += $this->LaundryAmenitiesOrderDetail
                ->select('sum(LAOD_AMOUNT) as total_amount')
                ->where("LAOD_ORDER_ID = {$order['LAO_ID']} and LAOD_DELIVERY_STATUS in ('New','Processing','Delivered','Acknowledged')")
                ->first()['total_amount'];
        }

        return $revenue;
    }
}
