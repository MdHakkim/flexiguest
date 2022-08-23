<?php

namespace App\Models;

use CodeIgniter\Model;

class Guideline extends Model
{
    protected $table      = 'FLXY_GUIDELINES';
    protected $primaryKey = 'GL_ID';
    protected $allowedFields = [
        'GL_TITLE',
        'GL_COVER_IMAGE',
        'GL_DESCRIPTION',
        'GL_BODY',
        'GL_IS_ENABLED',
        'GL_CREATED_BY',
        'GL_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'GL_CREATED_AT';
    protected $updatedField  = 'GL_UPDATED_AT';
}
