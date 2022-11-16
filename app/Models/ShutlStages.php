<?php

namespace App\Models;

use CodeIgniter\Model;

class ShutlStages extends Model
{
    protected $table      = 'FLXY_SHUTL_STAGES';
    protected $primaryKey = 'SHUTL_STAGE_ID';
    protected $allowedFields = [
        'SHUTL_STAGE_NAME',
        'SHUTL_STAGE_IMAGE',
        'SHUTL_CREATE_UID',
        'SHUTL_UPDATE_UID',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'SHUTL_CREATE_DT';
    protected $updatedField  = 'SHUTL_UPDATE_DT';
}
