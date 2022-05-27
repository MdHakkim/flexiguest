<?php

namespace App\Models;

use CodeIgniter\Model;

class Currency extends Model
{
    protected $table      = 'FLXY_CURRENCY';
    protected $primaryKey = 'CUR_ID';
    protected $allowedFields = [
        'CUR_CODE',
        'CUR_DESC',
        'CUR_DECIMAL',
        'CUR_SEQ',
        'CUR_STATUS',
        'CUR_DELETED',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'CUR_CREATED';
    protected $updatedField  = 'CUR_UPDATED';
}
