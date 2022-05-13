<?php

namespace App\Models;

use CodeIgniter\Model;

class Reservation extends Model
{
    protected $table      = 'FLXY_VACCINE_DETAILS';
    protected $primaryKey = 'VACCINE_ID';

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'VACC_CREATE_DT';
    protected $updatedField  = 'VACC_UPDATE_DT';
}
