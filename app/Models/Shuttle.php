<?php

namespace App\Models;

use CodeIgniter\Model;

class Shuttle extends Model
{
    protected $table      = 'FLXY_SHUTTLE';
    protected $primaryKey = 'SHUTL_ID';

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'SHUTL_CREATE_DT';
    protected $updatedField  = 'SHUTL_UPDATE_DT';
}
