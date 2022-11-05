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
    private $publishable_key;

    public function __construct()
    {
        // This is your test secret API key.
        \Stripe\Stripe::setApiKey('sk_test_51Lg1MuA6gmHSIFPihgF6i18MSWFmCtqCs6OZPGgkfypw8cRPMRl2Q6lQyxOoCglwSgzBnZaLHCqN41Q5k4hVU6yF00H19RITcI');

        $this->PaymentTransaction = new PaymentTransaction();
        $this->Customer = new Customer();
        $this->publishable_key = 'pk_test_51Lg1MuA6gmHSIFPivTkgXTIRKfoo3XtJDCWP2I5a94nrh2d9geu5n91SSfVxQbrBY70VgF9j218PDSxdbmRg4mDa00AsaSKQ3s';
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
                ],
                'receipt_email' => $user['CUST_EMAIL']
            ]);

            $output = [
                'client_secret' => $paymentIntent->client_secret,
                'ephemeral_key' => $ephemeral_key->secret,
                'customer_id' => $customer_id,
                'publishable_key' => $this->publishable_key
            ];

            return responseJson(200, false, ['msg' => 'payment intent created successfully.'], $output);
        } catch (\Exception $e) {
            return responseJson(500, true, ['msg' => $e->getMessage()]);
        }
    }
}
