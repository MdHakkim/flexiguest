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
        'PYM_CREDIT_LIMIT',
        'PYM_CARD_LENGTH',
        'PYM_CARD_PREFIX',
        'PYM_ENABLE_DISABLE',
        'PYM_DISPLAY_SEQUENCE',
        'PYM_CREATED_BY',
        'PYM_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'PYM_CREATED_AT';
    protected $updatedField  = 'PYM_UPDATED_AT';
}
