<?php

namespace App\Controllers\Repositories;

use App\Controllers\BaseController;
use App\Models\PaymentTransaction;
use CodeIgniter\API\ResponseTrait;

class PaymentRepository extends BaseController
{
    use ResponseTrait;

    private $PaymentTransaction;

    public function __construct()
    {
        // This is your test secret API key.
        \Stripe\Stripe::setApiKey('sk_test_51Lg1MuA6gmHSIFPihgF6i18MSWFmCtqCs6OZPGgkfypw8cRPMRl2Q6lQyxOoCglwSgzBnZaLHCqN41Q5k4hVU6yF00H19RITcI');

        $this->PaymentTransaction = new PaymentTransaction();
    }

    public function createUpdateTransaction($data)
    {
        return $this->PaymentTransaction->save($data);
    }

    public function createPaymentIntent($amount)
    {
        try {
            $amount = floatval($amount) * 100;

            // Create a PaymentIntent with amount and currency
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $amount,
                'currency' => 'aed',
                // 'automatic_payment_methods' => [
                //     'enabled' => true,
                // ],
            ]);

            $output = [
                'client_secret' => $paymentIntent->client_secret,
            ];

            return responseJson(200, false, ['msg' => 'client secret'], $output);
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
        $endpoint_secret = 'we_1Lh5IuA6gmHSIFPiTJnxy2zh';

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
