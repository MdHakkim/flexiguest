<?php

namespace App\Models;

use CodeIgniter\Model;

class Reservation extends Model
{
    protected $table      = 'FLXY_RESERVATION';
    protected $primaryKey = 'RESV_ID';
    protected $allowedFields = [
        'RESV_ARRIVAL_DT',
        'RESV_NIGHT',
        'RESV_ADULTS',
        'RESV_CHILDREN',
        'RESV_DEPARTURE',
        'RESV_NO_F_ROOM',
        'RESV_NAME',
        'RESV_MEMBER_TY',
        'RESV_COMPANY',
        'RESV_AGENT',
        'RESV_BLOCK',
        'RESV_MEMBER_NO',
        'RESV_CORP_NO',
        'RESV_IATA_NO',
        'RESV_CLOSED',
        'RESV_DAY_USE',
        'RESV_PSEUDO',
        'RESV_RATE_CLASS',
        'RESV_RATE_CATEGORY',
        'RESV_RATE_CODE',
        'RESV_ROOM_CLASS',
        'RESV_FEATURE',
        'RESV_PACKAGES',
        'RESV_PURPOSE_STAY',
        'RESV_STATUS',
        'RESV_RM_TYPE',
        'RESV_ROOM',
        'RESV_RATE',
        'RESV_ETA',
        'RESV_CO_TIME',
        'RESV_RTC',
        'RESV_FIXED_RATE',
        'RESV_RESRV_TYPE',
        'RESV_MARKET',
        'RESV_SOURCE',
        'RESV_ORIGIN',
        'RESV_PAYMENT_TYPE',
        'RESV_SPECIALS',
        'RESV_COMMENTS',
        'RESV_ITEM_INVT',
        'RESV_BOKR_LAST',
        'RESV_BOKR_FIRST',
        'RESV_BOKR_EMAIL',
        'RESV_BOKR_PHONE',
        'RESV_CONFIRM_YN',
        'RESV_CREATE_UID',
        'RESV_CREATE_DT',
        'RESV_UPDATE_UID',
        'RESV_UPDATE_DT',
        'RESV_NO',
        'RESV_COMP_CODE',
        'RESV_C_O_TIME',
        'RESV_TAX_TYPE',
        'RESV_EXEMPT_NO',
        'RESV_PICKUP_YN',
        'RESV_TRANSPORT_TYP',
        'RESV_STATION_CD',
        'RESV_CARRIER_CD',
        'RESV_TRANSPORT_NO',
        'RESV_ARRIVAL_DT_PK',
        'RESV_PICKUP_TIME',
        'RESV_DROPOFF_YN',
        'RESV_TRANSPORT_TYP_DO',
        'RESV_STATION_CD_DO',
        'RESV_CARRIER_CD_DO',
        'RESV_TRANSPORT_NO_DO',
        'RESV_ARRIVAL_DT_DO',
        'RESV_DROPOFF_TIME',
        'RESV_GUST_TY',
        'RESV_EXT_PURP_STAY',
        'RESV_ENTRY_PONT',
        'RESV_PROFILE',
        'RESV_NAME_ON_CARD',
        'RESV_EXT_PRINT_RT',
        'RESV_SHARE_RATE'
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'RESV_CREATE_DT';
    protected $updatedField  = 'RESV_UPDATE_DT';
}
