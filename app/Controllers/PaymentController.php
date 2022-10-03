<?php

namespace App\Controllers;

use App\Controllers\Repositories\ConciergeRepository;
use App\Controllers\Repositories\LaundryAmenitiesRepository;
use App\Controllers\Repositories\PaymentRepository;
use App\Controllers\Repositories\ReservationRepository;
use App\Controllers\Repositories\RestaurantRepository;
use App\Controllers\Repositories\TransportRequestRepository;
use CodeIgniter\API\ResponseTrait;

class PaymentController extends BaseController
{

    use ResponseTrait;

    private $PaymentRepository;
    private $LaundryAmenitiesRepository;
    private $ConciergeRepository;
    private $TransportRequestRepository;
    private $ReservationRepository;
    private $RestaurantRepository;

    public function __construct()
    {
        $this->PaymentRepository = new PaymentRepository();
        $this->LaundryAmenitiesRepository = new LaundryAmenitiesRepository();
        $this->ConciergeRepository = new ConciergeRepository();
        $this->TransportRequestRepository = new TransportRequestRepository();
        $this->ReservationRepository = new ReservationRepository();
        $this->RestaurantRepository = new RestaurantRepository();
    }

    public function createPaymentIntent()
    {
        $data = (array) $this->request->getVar();
        $user = $this->request->user;

        if ($data['model'] == 'FLXY_LAUNDRY_AMENITIES_ORDERS') {
            $order = $this->LaundryAmenitiesRepository->orderById($data['model_id']);
            if (empty($order))
                return $this->respond(responseJson(404, true, ['msg' => 'Order not found']));

            $data['amount'] = $order['LAO_TOTAL_PAYABLE'];
            $data['reservation_id'] = $order['LAO_RESERVATION_ID'];
        } else if ($data['model'] == 'FLXY_CONCIERGE_REQUESTS') {

            $request = $this->ConciergeRepository->getConciergeRequest("CR_ID = {$data['model_id']}");
            if (empty($request))
                return $this->respond(responseJson(404, true, ['msg' => 'Concierge Request not found']));

            $data['amount'] = $request['CR_TOTAL_AMOUNT'];
            $data['reservation_id'] = $request['CR_RESERVATION_ID'];
        } else if ($data['model'] == 'FLXY_TRANSPORT_REQUESTS') {

            $request = $this->TransportRequestRepository->getTransportRequest("TR_ID = {$data['model_id']}");
            if (empty($request))
                return $this->respond(responseJson(404, true, ['msg' => 'Transport Request not found.']));

            $data['amount'] = $request['TR_TOTAL_AMOUNT'];
            $data['reservation_id'] = $request['TR_RESERVATION_ID'];
        } else if ($data['model'] == 'FLXY_RESERVATION') {

            $request = $this->ReservationRepository->reservationById($data['model_id']);
            if (empty($request))
                return $this->respond(responseJson(404, true, ['msg' => 'Reservation not found.']));

            $data['amount'] = $request['RESV_RATE'];
            $data['reservation_id'] = $request['RESV_ID'];
        } else if ($data['model'] == 'FLXY_RESTAURANT_ORDERS') {

            $request = $this->RestaurantRepository->restaurantOrderById($data['model_id']);
            if (empty($request))
                return $this->respond(responseJson(404, true, ['msg' => 'Order not found.']));

            $data['amount'] = $request['RO_TOTAL_PAYABLE'];
            $data['reservation_id'] = null;
        }

        $result = $this->PaymentRepository->createPaymentIntent($user, $data);
        return $this->respond($result);
    }

    public function webhook()
    {
        // Replace this endpoint secret with your endpoint's unique secret
        // If you are testing with the CLI, find the secret by running 'stripe listen'
        // If you are using an endpoint defined with the API or dashboard, look in your webhook settings
        // at https://dashboard.stripe.com/webhooks

        $endpoint_secret = 'whsec_KM4iN5MYtNp8DivaaTpc4qcIQKjnJjue';
        // whsec_8ea018b171cb3fbd22d77fe43124c15ac9676c9fa897a3694576aa21937a138d

        $payload = @file_get_contents('php://input');
        // $payload = json_encode($this->request->getVar());
        $event = null;

        try {
            $event = \Stripe\Event::constructFrom(
                json_decode($payload, true)
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            echo '⚠️  Webhook error while parsing basic request.';
            http_response_code(400);
            exit();
        }

        if ($endpoint_secret) { 
            // Only verify the event if there is an endpoint secret defined
            // Otherwise use the basic decoded event
            $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
            try {
                $event = \Stripe\Webhook::constructEvent(
                    $payload,
                    $sig_header,
                    $endpoint_secret
                );
            } catch (\Stripe\Exception\SignatureVerificationException $e) {
                // Invalid signature
                echo '⚠️  Webhook error while validating signature.';
                http_response_code(400);
                exit();
            }
        }

        // Handle the event
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object; // contains a \Stripe\PaymentIntent
                // Then define and call a method to handle the successful payment intent.
                $this->paymentSucceded($event);
                break;

            case 'payment_intent.canceled': 
                $this->paymentCancelled($event);
                break;

            case 'payment_method.attached':
                $paymentMethod = $event->data->object; // contains a \Stripe\PaymentMethod
                // Then define and call a method to handle the successful attachment of a PaymentMethod.
                

                break;
            default:
                // Unexpected event type
                error_log('Received unknown event type');
        }

        http_response_code(200);
        exit;
    }

    // public function webhook()
    // {
    //     $data = $this->request->getVar();

    //     // $this->PaymentRepository->webhook($data);
    //     if ($data->type == 'payment_intent.processing') {
    //     } else if ($data->type == 'payment_intent.succeeded') {
    //         $this->paymentSucceded($data);
    //     } else if ($data->type == 'payment_intent.canceled') {
    //         $this->paymentCancelled($data);
    //     } else if ($data->type == 'payment_intent.payment_failed') {
    //     }


    //     return $this->response->setStatusCode(200);
    // }

    public function paymentSucceded($data)
    {
        $payment_obj_id = $data->id;

        $obj_data = $data->data->object;
        $meta_data = $obj_data->metadata;
        $charge_data = $obj_data->charges->data[0];

        $payment_method = $obj_data->payment_method_types[0];
        $balance_transaction_id = $charge_data->balance_transaction;

        $this->PaymentRepository->createUpdateTransaction([
            'PT_RESERVATION_ID' => $meta_data->reservation_id,
            'PT_CUSTOMER_ID' => $meta_data->customer_id,
            'PT_PAYMENT_METHOD' => $payment_method,
            'PT_PAYMENT_OBJECT_ID' => $payment_obj_id,
            'PT_TRANSACTION_NO' => $balance_transaction_id,
            'PT_AMOUNT' => $meta_data->amount,
            'PT_MODEL' => $meta_data->model,
            'PT_MODEL_ID' => $meta_data->model_id,
            'PT_CREATED_BY' => $meta_data->user_id,
            'PT_UPDATED_BY' => $meta_data->user_id
        ]);

        if ($meta_data->model == 'FLXY_LAUNDRY_AMENITIES_ORDERS') {

            $this->LaundryAmenitiesRepository->updateOrderById([
                'LAO_ID' => $meta_data->model_id,
                'LAO_PAYMENT_STATUS' => 'Paid',
                'LAO_UPDATED_AT' => date('Y-m-d H:i:s'),
                'LAO_UPDATED_BY' => $meta_data->user_id,
            ]);
        } else if ($meta_data->model == 'FLXY_CONCIERGE_REQUESTS') {

            $this->ConciergeRepository->updateConciergeRequestById([
                'CR_ID' => $meta_data->model_id,
                'CR_PAYMENT_STATUS' => 'Paid',
                'CR_UPDATED_AT' => date('Y-m-d H:i:s'),
                'CR_UPDATED_BY' => $meta_data->user_id,
            ]);
        } else if ($meta_data->model == 'FLXY_TRANSPORT_REQUESTS') {

            $this->TransportRequestRepository->updateTransportRequestById([
                'TR_ID' => $meta_data->model_id,
                'TR_PAYMENT_STATUS' => 'Paid',
                'TR_UPDATED_AT' => date('Y-m-d H:i:s'),
                'TR_UPDATED_BY' => $meta_data->user_id,
            ]);
        } else if ($meta_data->model == 'FLXY_RESERVATION') {

            $this->ReservationRepository->updateReservation([
                'RESV_PAYMENT_STATUS' => 'Paid',
                'RESV_UPDATE_DT' => date('Y-m-d H:i:s'),
                'RESV_UPDATE_UID' => $meta_data->user_id,
            ], "RESV_ID = {$meta_data->model_id}");
        } else if ($meta_data->model == 'FLXY_RESTAURANT_ORDERS') {

            $this->RestaurantRepository->createUpdateRestaurantOrder([
                'RO_ID' => $meta_data->model_id,
                'RO_PAYMENT_STATUS' => 'Paid',
                'RO_UPDATED_AT' => date('Y-m-d H:i:s'),
                'RO_UPDATED_BY' => $meta_data->user_id,
            ]);
        }
    }

    public function paymentCancelled($data)
    {
        $obj_data = $data->data->object;
        $meta_data = $obj_data->metadata;

        if ($meta_data->model == 'FLXY_LAUNDRY_AMENITIES_ORDERS') {
            if (!empty($this->LaundryAmenitiesRepository->orderById($meta_data->model_id)))
                $this->LaundryAmenitiesRepository->updateOrderById([
                    'LAO_ID' => $meta_data->model_id,
                    'LAO_PAYMENT_STATUS' => 'UnPaid',
                    'LAO_UPDATED_AT' => date('Y-m-d H:i:s'),
                    'LAO_UPDATED_BY' => $meta_data->user_id,
                ]);
        }
    }
}
