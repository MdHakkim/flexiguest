<?php

namespace App\Controllers\Repositories;

use App\Controllers\BaseController;
use App\Models\Reservation;
use App\Models\ReservationRoomAsset;
use App\Models\RoomAsset;
use CodeIgniter\API\ResponseTrait;

class ReservationAssetRepository extends BaseController
{
    use ResponseTrait;

    private $Reservation;
    private $RoomAsset;
    private $ReservationRoomAsset;

    public function __construct()
    {
        $this->Reservation = new Reservation();
        $this->RoomAsset = new RoomAsset();
        $this->ReservationRoomAsset = new ReservationRoomAsset();
    }

    public function attachAssetList($user_id, $reservation_id)
    {
        $reservation = $this->Reservation
            ->select('RESV_ID, RM_ID')
            ->join('FLXY_ROOM', 'RESV_ROOM = RM_NO', 'left')
            ->where('RESV_ID', $reservation_id)
            ->first();

        if (empty($reservation))
            return;

        $room_id = $reservation['RM_ID'];

        // assets already added
        $already_exist = $this->ReservationRoomAsset
            ->where('RRA_RESERVATION_ID', $reservation_id)
            ->where('RRA_ROOM_ID', $room_id)
            ->first();

        if (!empty($already_exist))
            return;

        $assets = $this->RoomAsset->where('RA_ROOM_ID', $room_id)->findAll();

        foreach ($assets as $asset) {
            $data = [
                'RRA_RESERVATION_ID' => $reservation_id,
                'RRA_ROOM_ID' => $room_id,
                'RRA_ASSET_ID' => $asset['RA_ASSET_ID'],
                'RRA_QUANTITY' => $asset['RA_QUANTITY'],
                'RRA_CREATED_BY' => $user_id,
                'RRA_UPDATED_BY' => $user_id
            ];

            $this->ReservationRoomAsset->insert($data);
        }
    }

    public function getReservationAssets($where_condition)
    {
        return $this->ReservationRoomAsset->where($where_condition)->findAll();
    }
}
