<?php

namespace App\Models;

use CodeIgniter\Model;

class ReservationTransaction extends Model
{
    protected $table      = 'FLXY_RESERVATION_TRANSACTIONS';
    protected $primaryKey = 'RTR_ID';
    protected $allowedFields = [
        'RTR_RESERVATION_ID',
        'RTR_TRANSACTION_CODE_ID',
        'RTR_PAYMENT_METHOD_ID',
        'RTR_TRANSACTION_TYPE',
        'RTR_AMOUNT',
        'RTR_QUANTITY',
        'RTR_CHECK_NO',
        'RTR_CARD_NUMBER',
        'RTR_CARD_EXPIRY',
        'RTR_WINDOW',
        'RTR_SUPPLEMENT',
        'RTR_REFERENCE',
        'RTR_CREATED_BY',
        'RTR_UPDATED_BY',
        'RTR_DELETED_AT',
        'RTR_DELETED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'RTR_CREATED_AT';
    protected $updatedField  = 'RTR_UPDATED_AT';
}
