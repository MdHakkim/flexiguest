<?php

namespace App\Models;

use CodeIgniter\Model;

class ConciergeRequest extends Model
{
    protected $table      = 'FLXY_CONCIERGE_REQUESTS';
    protected $primaryKey = 'CR_ID';
    protected $allowedFields = [
        'CR_OFFER_ID',
        'CR_QUANTITY',
        'CR_REMARKS',
        'CR_GUEST_NAME',
        'CR_GUEST_EMAIL',
        'CR_GUEST_PHONE',
        'CR_CUSTOMER_ID',
        'CR_RESERVATION_ID',
        'CR_TOTAL_AMOUNT',
        'CR_TAX_AMOUNT',
        'CR_NET_AMOUNT',
        'CR_STATUS', // In Progress, Approved, Rejected, Closed
        'CR_PREFERRED_DATE',
        'CR_PREFERRED_TIME',
        'CR_PAYMENT_METHOD', // Pay at Reception, Samsung Pay, Credit/Debit card
        'CR_PAYMENT_STATUS', // UnPaid, Paid
        'CR_CREATED_BY',
        'CR_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'CR_CREATED_AT';
    protected $updatedField  = 'CR_UPDATED_AT';
}
