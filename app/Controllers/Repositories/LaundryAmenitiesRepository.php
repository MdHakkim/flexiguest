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
}
