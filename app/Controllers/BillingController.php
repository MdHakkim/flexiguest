<?php

namespace App\Controllers;

use App\Controllers\Repositories\BillingRepository;
use CodeIgniter\API\ResponseTrait;

class BillingController extends BaseController
{

    use ResponseTrait;

    private $BillingRepository;

    public function __construct()
    {
        $this->BillingRepository = new BillingRepository();
    }

    public function billing()
    {
        $data['title'] = getMethodName();
        $data['session'] = session();

        $data['reservation_id'] = $this->request->getVar('reservation_id');
        $data['confirm_password'] = session('confirm_password') ?? false;
        // unset($_SESSION['confirm_password']);

        return view('frontend/accounts/billing', $data);
    }
}
