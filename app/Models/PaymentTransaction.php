<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentTransaction extends Model
{
    protected $table      = 'FLXY_PAYMENT_TRANSACTIONS';
    protected $primaryKey = 'PT_ID';
    protected $allowedFields = [
        'PT_RESERVATION_ID',
        'PT_CUSTOMER_ID',
        'PT_PAYMENT_METHOD',
        'PT_PAYMENT_OBJECT_ID',
        'PT_TRANSACTION_NO',
        'PT_AMOUNT',
        'PT_MODEL',
        'PT_MODEL_ID',
        'PT_CREATED_BY',
        'PT_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'PT_CREATED_AT';
    protected $updatedField  = 'PT_UPDATED_AT';
}
