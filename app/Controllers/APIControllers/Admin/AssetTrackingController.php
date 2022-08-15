<?php

namespace App\Controllers\APIControllers\Admin;

use App\Controllers\BaseController;
use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\Reservation;
use App\Models\ReservationRoomAsset;
use App\Models\Room;
use App\Models\RoomAsset;
use CodeIgniter\API\ResponseTrait;

class AssetTrackingController extends BaseController
{
    use ResponseTrait;

    private $DB;
    private $Reservation;
    private $Room;
    private $AssetCategory;
    private $Asset;
    private $RoomAsset;
    private $ReservationRoomAsset;

    public function __construct()
    {
        $this->DB = \Config\Database::connect();
        $this->Reservation = new Reservation();
        $this->Room = new Room();
        $this->AssetCategory = new AssetCategory();
        $this->Asset = new Asset();
        $this->RoomAsset = new RoomAsset();
        $this->ReservationRoomAsset = new ReservationRoomAsset();
    }

    public function getAssets()
    {
        $rooms = $this->Reservation
            ->select("distinct(RESV_ID), RESV_ROOM as ROOM_NO, RRA_ROOM_ID as ROOM_ID, 
                    concat(CUST_FIRST_NAME, ' ', CUST_LAST_NAME) as GUEST_NAME,
                    RRA_STATUS")
            ->join('FLXY_RESERVATION_ROOM_ASSETS', 'RESV_ID = RRA_RESERVATION_ID', 'left')
            ->join('FLXY_CUSTOMER', 'RESV_NAME = CUST_ID', 'left')
            ->where('RESV_STATUS', 'Checked-In')
            ->where('RRA_STATUS', 'Completed')
            ->findAll();

        foreach ($rooms as $index => $room) {
            $rooms[$index]['ASSETS'] = $this->ReservationRoomAsset
                ->select('AS_ID, AS_ASSET, RA_QUANTITY, RRA_STATUS, RRA_TRACKING_REMARKS')
                ->join('FLXY_ROOM_ASSETS', 'RRA_ROOM_ID = RA_ROOM_ID', 'left')
                ->join('FLXY_ASSETS', 'RRA_ASSET_ID = AS_ID', 'left')
                ->where('RRA_RESERVATION_ID', $room['RESV_ID'])
                ->where('RRA_ROOM_ID', $room['ROOM_ID'])
                ->findAll();
        }

        return $this->respond(responseJson(200, false, ['msg' => 'assets list'], $rooms));
    }
}
