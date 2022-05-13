<?php

namespace App\Models;

use CodeIgniter\Model;

class Customer extends Model
{
    protected $table      = 'FLXY_CUSTOMER';
    protected $primaryKey = 'CUST_ID';

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'CUST_CREATE_DT';
    protected $updatedField  = 'CUST_UPDATE_DT';
}
