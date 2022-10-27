<?php

namespace App\Models;

use CodeIgniter\Model;

class ForgetPasswordToken extends Model
{
    protected $table      = 'FLXY_FORGET_PASSWORD_TOKENS';
    protected $primaryKey = 'FPT_ID';
    protected $allowedFields = [
        'FPT_USER_ID',
        'FPT_TOKEN',
        'FPT_EXPIRE_AT',
        'FPT_CREATED_BY',
        'FPT_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'FPT_CREATED_AT';
    protected $updatedField  = 'FPT_UPDATED_AT';
}
