<?php

namespace App\Models;

use CodeIgniter\Model;

class ConciergeOffer extends Model
{
    protected $table      = 'FLXY_CONCIERGE_OFFERS';
    protected $primaryKey = 'CO_ID';
    protected $allowedFields = [
        'CO_TITLE',
        'CO_DESCRIPTION',
        'CO_COVER_IMAGE',
        'CO_VALID_FROM_DATE',
        'CO_VALID_TO_DATE',
        'CO_PMS_CODE',
        'CO_EXT_REF',
        'CO_PROVIDER_LOGO',
        'CO_PROVIDER_TITLE',
        'CO_PROVIDER_SUB_TITLE',
        'CO_PROVIDER_EMAIL',
        'CO_PROVIDER_PHONE',
        'CO_CONTACT_EMAIL',
        'CO_CONTACT_PHONE',
        'CO_LOCATION',
        'CO_ACTUAL_PRICE',
        'CO_OFFER_PRICE',
        'CO_CURRENCY_ID',
        'CO_TAX_RATE',
        'CO_TAX_AMOUNT',
        'CO_NET_PRICE',
        'CO_MIN_QUANTITY',
        'CO_MAX_QUANTITY',
        'CO_MIN_AGE',
        'CO_MAX_AGE',
        'CO_STATUS',
        'CO_CREATED_BY',
        'CO_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'CO_CREATED_AT';
    protected $updatedField  = 'CO_UPDATED_AT';
}
