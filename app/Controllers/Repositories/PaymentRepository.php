<?php

namespace App\Controllers\Repositories;

use App\Controllers\BaseController;
use App\Models\Customer;
use App\Models\PaymentTransaction;
use CodeIgniter\API\ResponseTrait;

class PaymentRepository extends BaseController
{
    use ResponseTrait;

    private $PaymentTransaction;
    private $Customer;

    public function __construct()
    {
        // This is your test secret API key.
        \Stripe\Stripe::setApiKey('sk_test_51Lg1MuA6gmHSIFPihgF6i18MSWFmCtqCs6OZPGgkfypw8cRPMRl2Q6lQyxOoCglwSgzBnZaLHCqN41Q5k4hVU6yF00H19RITcI');

        $this->PaymentTransaction = new PaymentTransaction();
        $this->Customer = new Customer();
    }

    public function createUpdateTransaction($data)
    {
        return $this->PaymentTransaction->save($data);
    }

    public function retrievePaymentMethod($user)
    {
        try {
            $response = \Stripe\Customer::allPaymentMethods(
                $user['CUST_STRIPE_CUSTOMER_ID'],
                ['type' => 'card']
            );
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        $payment_method_id = null;
        if (isset($response['data']) && count($response['data']))
            $payment_method_id = $response['data'][0]['id'];

        return $payment_method_id;
    }

    // public function attachPaymentMethod($user, $payment_method_id)
    // {
    //     try {
    //         \Stripe\PaymentMethod::attach(
    //             $payment_method_id,
    //             ['customer' => $user['CUST_STRIPE_CUSTOMER_ID']]
    //         );
    //     } catch (\Exception $e) {
    //         return $e->getMessage();
    //     }

    //     return true;
    // }

    public function createPaymentIntent($user, $amount)
    {
        try {
            \Stripe\SetupIntent::create(['usage' => 'on_session']);

            // $res = \Stripe\SetupIntent::all(['limit' => 3]);
            // echo json_encode($res);
            // die();

            $amount = floatval($amount) * 100;

            // Use an existing Customer ID if this is a returning customer.
            if (is_null($customer_id = $user['CUST_STRIPE_CUSTOMER_ID'])) {
                $customer = \Stripe\Customer::create([
                    'name' => $user['CUST_FIRST_NAME'] . ' ' . $user['CUST_LAST_NAME'],
                    'email' => $user['CUST_EMAIL'],
                    'description' => "AED $amount received from " . $user['CUST_FIRST_NAME'] . ' ' . $user['CUST_LAST_NAME']
                ]);

                $user['CUST_STRIPE_CUSTOMER_ID'] = $customer_id = $customer->id;
                $this->Customer->update($user['CUST_ID'], ['CUST_STRIPE_CUSTOMER_ID' => $customer_id]);
            }

            // $payment_method_id = $this->retrievePaymentMethod($user);
            // if ($payment_method_id)
            //     $this->attachPaymentMethod($user, $payment_method_id);

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
                // 'setup_future_usage' => 'on_session',
                // 'payment_method_types' => ['card'],
                // 'automatic_payment_methods' => [
                // 'enabled' => true,
                // ],
            ]);

            $output = [
                'client_secret' => $paymentIntent->client_secret,
                'ephemeral_key' => $ephemeral_key->secret,
                'customer_id' => $customer_id
            ];

            return responseJson(200, false, ['msg' => 'payment intent created successfully.'], $output);
        } catch (\Exception $e) {
            return responseJson(500, true, ['msg' => $e->getMessage()]);
            // http_response_code(500);
            // echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function webhook()
    {
        $this->writeToFile('called');
        // Replace this endpoint secret with your endpoint's unique secret
        // If you are testing with the CLI, find the secret by running 'stripe listen'
        // If you are using an endpoint defined with the API or dashboard, look in your webhook settings
        // at https://dashboard.stripe.com/webhooks

        // $endpoint_secret = 'we_1Lh72rA6gmHSIFPiokiCpuzh';
        $endpoint_secret = 'whsec_lOnchTfrLaXyvHhGrIGwzdIK6PVpwZBi';
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
            $this->writeToFile('⚠️  Webhook error while parsing basic request.');
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
                $this->writeToFile('⚠️  Webhook error while validating signature.');
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
                $this->writeToFile('paymentIntent');

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
    }

    public function writeToFile($text)
    {
        $myfile = fopen("text.txt", "w") or die("Unable to open file!");
        fwrite($myfile, $text);
        fclose($myfile);
    }
}
