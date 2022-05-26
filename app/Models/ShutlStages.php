<?php

namespace App\Models;

use CodeIgniter\Model;

class ShutlStages extends Model
{
    protected $table      = 'FLXY_SHUTL_STAGES';
    protected $primaryKey = 'SHUTL_STAGE_ID';

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'SHUTL_CREATE_DT';
    protected $updatedField  = 'SHUTL_UPDATE_DT';
}
