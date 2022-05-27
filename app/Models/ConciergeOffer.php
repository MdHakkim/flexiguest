<?php

namespace App\Models;

use CodeIgniter\Model;

class ConciergeOffer extends Model
{
    protected $table      = 'concierge_offers';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'title',
        'description',
        'cover_image',
        'valid_from_date',
        'valid_to_date',
        'pms_code',
        'ext_ref',
        'provider_logo',
        'provider_title',
        'provider_sub_title',
        'provider_email',
        'provider_phone',
        'contact_email',
        'contact_phone',
        'location',
        'actual_price',
        'offer_price',
        'currency_id',
        'tax_rate',
        'tax_amount',
        'net_price',
        'min_quantity',
        'max_quantity',
        'min_age',
        'max_age',
        'created_by',
        'updated_by',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
