<?php

namespace App\Models;

use CodeIgniter\Model;

class Customer extends Model
{
    protected $table      = 'FLXY_CUSTOMER';
    protected $primaryKey = 'CUST_ID';
    protected $allowedFields = [
        'CUST_FIRST_NAME',
        'CUST_MIDDLE_NAME',
        'CUST_LAST_NAME',
        'CUST_LANG',
        'CUST_TITLE',
        'CUST_DOB',
        'CUST_PASSPORT',
        'CUST_ADDRESS_1',
        'CUST_ADDRESS_2',
        'CUST_ADDRESS_3',
        'CUST_COUNTRY',
        'CUST_STATE',
        'CUST_STATE_ID',
        'CUST_CITY',
        'CUST_EMAIL',
        'CUST_MOBILE_CODE',
        'CUST_MOBILE',
        'CUST_PHONE',
        'CUST_CLIENT_ID',
        'CUST_POSTAL_CODE',
        'CUST_VIP',
        'CUST_NATIONALITY',
        'CUST_COR',
        'CUST_BUS_SEGMENT',
        'CUST_COMMUNICATION',
        'CUST_COMMUNICATION_DESC',
        'CUST_CREATE_UID',
        'CUST_UPDATE_UID',
        'CUST_ACTIVE',
        'CUST_COMP_CODE',
        'CUST_DOC_EXPIRY',
        'CUST_GENDER',
        'CUST_DOC_TYPE',
        'CUST_DOC_NUMBER',
        'CUST_DOC_ISSUE',
        'CUST_STRIPE_CUSTOMER_ID',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'CUST_CREATE_DT';
    protected $updatedField  = 'CUST_UPDATE_DT';
}
