<?php

namespace App\Models;

use CodeIgniter\Model;

class Reservation extends Model
{
    protected $table      = 'FLXY_RESERVATION';
    protected $primaryKey = 'RESV_ID';

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'RESV_CREATE_DT';
    protected $updatedField  = 'RESV_UPDATE_DT';
}
