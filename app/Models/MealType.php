<?php

namespace App\Models;

use CodeIgniter\Model;

class MealType extends Model
{
    protected $table      = 'FLXY_MEAL_TYPES';
    protected $primaryKey = 'MT_ID';
    protected $allowedFields = [
        'MT_TYPE',
        'MT_IMAGE_URL',
        'MT_CREATED_BY',
        'MT_UPDATED_BY',
    ];

    protected $useAutoIncrement = true;

    protected $useTimestamps = true;
    protected $createdField  = 'MT_CREATED_AT';
    protected $updatedField  = 'MT_UPDATED_AT';
}
