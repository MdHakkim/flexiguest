<?php

namespace App\Controllers\Repositories;

use App\Controllers\BaseController;
use App\Models\Customer;
use App\Models\PaymentTransaction;
use CodeIgniter\API\ResponseTrait;

class PaymentRepository extends BaseController
{
    use ResponseTrait;

    private $LaundryAmenitiesRepository;
    private $PaymentTransaction;
    private $Customer;

    public function __construct()
    {
        // This is your test secret API key.
        \Stripe\Stripe::setApiKey('sk_test_51Lg1MuA6gmHSIFPihgF6i18MSWFmCtqCs6OZPGgkfypw8cRPMRl2Q6lQyxOoCglwSgzBnZaLHCqN41Q5k4hVU6yF00H19RITcI');

        $this->LaundryAmenitiesRepository = new LaundryAmenitiesRepository();

        $this->PaymentTransaction = new PaymentTransaction();
        $this->Customer = new Customer();
    }

    public function createUpdateTransaction($data)
    {
        return $this->PaymentTransaction->save($data);
    }

    public function createPaymentIntent($user, $data)
    {
        try {
            $original_amount = $data['amount'];
            $amount = floatval($original_amount) * 100;

            // Use an existing Customer ID if this is a returning customer.
            if (is_null($customer_id = $user['CUST_STRIPE_CUSTOMER_ID'])) {
                $customer = \Stripe\Customer::create([
                    'name' => $user['CUST_FIRST_NAME'] . ' ' . $user['CUST_LAST_NAME'],
                    'email' => $user['CUST_EMAIL'],
                    'description' => "AED $original_amount received from " . $user['CUST_FIRST_NAME'] . ' ' . $user['CUST_LAST_NAME']
                ]);

                $user['CUST_STRIPE_CUSTOMER_ID'] = $customer_id = $customer->id;
                $this->Customer->update($user['CUST_ID'], ['CUST_STRIPE_CUSTOMER_ID' => $customer_id]);
            }

            $ephemeral_key = \Stripe\EphemeralKey::create(
                [
                    'customer' => $customer_id,
                ],
                [
                    'stripe_version' => '2022-08-01',
                ]
            );

            // Create a PaymentIntent with amount and currency
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $amount,
                'currency' => 'aed',
                'customer' => $customer_id,
                'metadata' => [
                    'reservation_id' => $data['reservation_id'],
                    'customer_id' => $user['CUST_ID'],
                    'amount' => $original_amount,
                    'model' => $data['model'],
                    'model_id' => $data['model_id'],
                    'user_id' => $user['USR_ID'],
                ]
            ]);

            if($data['model'] == 'FLXY_LAUNDRY_AMENITIES_ORDERS') {
                $this->LaundryAmenitiesRepository->createUpdateOrder([
                    'LAO_ID' => $data['model_id'],
                    'LAO_PAYMENT_STATUS' => 'Payment Initiated',
                    'LAO_UPDATED_AT' => date('Y-m-d H:i:s'),
                    'LAO_UPDATED_BY' => $user['USR_ID'],
                ]);
            }

            $output = [
                'client_secret' => $paymentIntent->client_secret,
                'ephemeral_key' => $ephemeral_key->secret,
                'customer_id' => $customer_id
            ];

            return responseJson(200, false, ['msg' => 'payment intent created successfully.'], $output);
        } catch (\Exception $e) {
            return responseJson(500, true, ['msg' => $e->getMessage()]);
        }
    }

    public function webhook()
    {
        // Replace this endpoint secret with your endpoint's unique secret
        // If you are testing with the CLI, find the secret by running 'stripe listen'
        // If you are using an endpoint defined with the API or dashboard, look in your webhook settings
        // at https://dashboard.stripe.com/webhooks

        // $endpoint_secret = 'we_1Lh72rA6gmHSIFPiokiCpuzh';
        $endpoint_secret = 'whsec_KM4iN5MYtNp8DivaaTpc4qcIQKjnJjue';
        // whsec_8ea018b171cb3fbd22d77fe43124c15ac9676c9fa897a3694576aa21937a138d

        $payload = @file_get_contents('php://input');
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
                // handlePaymentIntentSucceeded($paymentIntent);
                break;

            case 'payment_method.attached':
                $paymentMethod = $event->data->object; // contains a \Stripe\PaymentMethod
                // Then define and call a method to handle the successful attachment of a PaymentMethod.
                // handlePaymentMethodAttached($paymentMethod);
                break;
            default:
                // Unexpected event type
                error_log('Received unknown event type');
        }

        http_response_code(200);
        exit;
    }

    public function paymentSucceded($data)
    {
        $payment_obj_id = $data['id'];

        $obj_data = $data['data']['object'];
        $meta_data = $obj_data['metadata'];
        $charge_data = $obj_data['charges']['data'][0];

        $payment_method = $obj_data['payment_method_types'][0];
        $balance_transaction_id = $charge_data['balance_transaction'];

        $this->createUpdateTransaction([
            'PT_RESERVATION_ID' => $meta_data['reservation_id'],
            'PT_CUSTOMER_ID' => $meta_data['customer_id'],
            'PT_PAYMENT_METHOD' => $payment_method,
            'PT_PAYMENT_OBJECT_ID' => $payment_obj_id,
            'PT_TRANSACTION_NO' => $balance_transaction_id,
            'PT_AMOUNT' => $meta_data['amount'],
            'PT_MODEL' => $meta_data['model'],
            'PT_MODEL_ID' => $meta_data['model_id'],
            'PT_CREATED_BY' => $meta_data['user_id'],
            'PT_UPDATED_BY' => $meta_data['user_id']
        ]);

        if($meta_data['model'] == 'FLXY_LAUNDRY_AMENITIES_ORDERS') {
            $this->LaundryAmenitiesRepository->createUpdateOrder([
                'LAO_ID' => $meta_data['model_id'],
                'LAO_PAYMENT_STATUS' => 'Paid',
                'LAO_UPDATED_AT' => date('Y-m-d H:i:s'),
                'LAO_UPDATED_BY' => $meta_data['user_id'],
            ]);
        }
    }

    public function paymentCancelled($data)
    {
        $obj_data = $data['data']['object'];
        $meta_data = $obj_data['metadata'];

        if($meta_data['model'] == 'FLXY_LAUNDRY_AMENITIES_ORDERS') {
            $this->LaundryAmenitiesRepository->createUpdateOrder([
                'LAO_ID' => $meta_data['model_id'],
                'LAO_PAYMENT_STATUS' => 'UnPaid',
                'LAO_UPDATED_AT' => date('Y-m-d H:i:s'),
                'LAO_UPDATED_BY' => $meta_data['user_id'],
            ]);
        }
    }

    public function localWebhook($data)
    {
        if($data['type'] == 'payment_intent.succeded')
        {   
            $this->paymentSucceded($data);
        }
        else if($data['type'] == 'payment_intent.canceled') {
            $this->paymentCancelled($data);
        }
    }
}
