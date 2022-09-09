<?php

namespace App\Models;

use CodeIgniter\Model;

class Feedback extends Model
{
    protected $table      = 'FLXY_FEEDBACK';
    protected $primaryKey = 'FB_ID';
    protected $allowedFields = [
        'FB_CUST_ID',
        'FB_RATINGS',
        'FB_DESCRIPTION',
        'FB_MODEL',
        'FB_MODEL_ID',
        'FB_CREATE_UID',
        'FB_UPDATE_UID',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'FB_CREATE_DT';
    protected $updatedField  = 'FB_UPDATE_DT';
}
