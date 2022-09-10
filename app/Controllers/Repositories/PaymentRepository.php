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
            return responseJson(500, true, ['msg' => 'Something went wrong.'], $e->getMessage());
            // http_response_code(500);
            // echo json_encode(['error' => $e->getMessage()]);
        }
    }
}