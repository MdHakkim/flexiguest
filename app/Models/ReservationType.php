<?php

namespace App\Models;

use CodeIgniter\Model;

class ReservationType extends Model
{
    protected $table      = 'FLXY_RESERVATION_TYPE';
    protected $primaryKey = 'RESV_TY_ID';
    protected $allowedFields = [
        'RESV_TY_CODE',
        'RESV_TY_DESC',
        'RESV_TY_DEDUCT_INV',
        'RESV_TY_SEQ',
    ];

    protected $useAutoIncrement = true;

    // protected $useTimestamps = true;
    // protected $createdField  = 'AL_CREATED_AT';
    // protected $updatedField  = 'AL_UPDATED_AT';
}
