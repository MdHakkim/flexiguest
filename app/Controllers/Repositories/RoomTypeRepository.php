<?php

namespace App\Controllers\Repositories;

use App\Controllers\BaseController;
use App\Models\RoomType;
use App\Models\UpgradeRoomRequest;
use CodeIgniter\API\ResponseTrait;

class RoomTypeRepository extends BaseController
{
    use ResponseTrait;

    private $RoomType;

    public function __construct()
    {
        $this->RoomType = new RoomType();
    }

    public function roomTypes()
    {
        return $this->RoomType->findAll();
    }
}
