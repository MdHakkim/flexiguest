<?php

namespace App\Models;

use CodeIgniter\Model;

class VaccineDetail extends Model
{
    protected $table      = 'FLXY_VACCINE_DETAILS';
    protected $primaryKey = 'VACC_ID';
    protected $allowedFields = [
        'VACC_CUST_ID',
        'VACC_DETAILS',
        'VACC_LAST_DT',
        'VACC_TYPE',
        'VACC_NAME',
        'VACC_ISSUED_COUNTRY',
        'VACC_IS_VERIFY',
        'VACC_CREATE_UID',
        'VACC_UPDATE_UID',
        'VACC_FILE_PATH',
        'VACC_RESV_ID',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'VACC_CREATE_DT';
    protected $updatedField  = 'VACC_UPDATE_DT';
}
