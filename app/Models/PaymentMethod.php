<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentMethod extends Model
{
    protected $table      = 'FLXY_PAYMENT';
    protected $primaryKey = 'PYM_ID';
    protected $allowedFields = [
        'PYM_CODE',
        'PYM_DESC',
        'PYM_TXN_CODE',
    ];

    protected $useAutoIncrement = true;

    // protected $useTimestamps = true;
    // protected $createdField  = 'AL_CREATED_AT';
    // protected $updatedField  = 'AL_UPDATED_AT';
}
