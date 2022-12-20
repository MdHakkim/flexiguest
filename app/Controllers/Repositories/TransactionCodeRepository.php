<?php

namespace App\Controllers\Repositories;

use App\Controllers\BaseController;
use App\Models\TransactionCode;
use CodeIgniter\API\ResponseTrait;

class TransactionCodeRepository extends BaseController
{
    use ResponseTrait;

    private $TransactionCode;

    public function __construct()
    {
        $this->TransactionCode = new TransactionCode();
    }

    public function transactionCodes()
    {
        return $this->TransactionCode->findAll();
    }
}