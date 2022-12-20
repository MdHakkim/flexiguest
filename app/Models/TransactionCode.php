<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionCode extends Model
{
    protected $table      = 'FLXY_TRANSACTION_CODE';
    protected $primaryKey = 'TR_CD_ID';
    protected $allowedFields = [
        'TR_CD_CODE',
        'TR_CD_DESC',
        'TC_SGR_ID',
        'TR_TY_ID',
        'TR_CD_ADJ',
        'TR_CD_DEF_PRICE',
        'TR_CD_MIN_AMT',
        'TR_CD_MAX_AMT',
        'TR_CD_MANUAL_POST',
        'TR_CD_REVN_GRP',
        'TR_CD_MEMBERSHIP',
        'TR_CD_PAIDOUT',
        'TR_CD_GENRT_INCL',
        'TR_CD_INCL_DEPOSIT_RULE',
        'TR_CD_CHECK_NO_MANDATORY',
        'TR_CD_STATUS',
    ];

    protected $useAutoIncrement = true;

    // protected $useTimestamps = true;
    // protected $createdField  = 'AL_CREATED_AT';
    // protected $updatedField  = 'AL_UPDATED_AT';
}
