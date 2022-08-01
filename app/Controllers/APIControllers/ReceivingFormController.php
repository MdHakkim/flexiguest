<?php

namespace App\Controllers\APIControllers;

use App\Controllers\BaseController;
use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\ReservationRoomAsset;
use App\Models\RoomAsset;
use CodeIgniter\API\ResponseTrait;

class ReceivingFormController extends BaseController
{
    use ResponseTrait;

    private $AssetCategory;
    private $Asset;
    private $RoomAsset;
    private $ReservationRoomAsset;

    public function __construct()
    {
        $this->AssetCategory = new AssetCategory();
        $this->Asset = new Asset();
        $this->RoomAsset = new RoomAsset();
        $this->ReservationRoomAsset = new ReservationRoomAsset();
    }

    public function assetHandover()
    {
        $rules = [
            'reservation_id' => ['label' => 'reservation', 'rules' => 'required'],
            'room_id' => ['label' => 'room', 'rules' => 'required'],
        ];

        if (!$this->validate($rules))
            return $this->respond(responseJson(403, true, $this->validator->getErrors()));

        $reservation_id = $this->request->getVar('reservation_id');
        $room_id = $this->request->getVar('room_id');

        $room_assets = $this->ReservationRoomAsset
            ->select('RRA_STATUS, RRA_CREATED_AT')
            ->where('RRA_RESERVATION_ID', $reservation_id)
            ->where('RRA_ROOM_ID', $room_id)
            ->first();

        $result = responseJson(200, false, ['msg' => 'Asset handover'], $room_assets);
        return $this->respond($result);
    }

    public function getAssetsList()
    {

        $rules = [
            'reservation_id' => ['label' => 'reservation', 'rules' => 'required'],
            'room_id' => ['label' => 'room', 'rules' => 'required'],
        ];

        if (!$this->validate($rules))
            return $this->respond(responseJson(403, true, $this->validator->getErrors()));

        $reservation_id = $this->request->getVar('reservation_id');
        $room_id = $this->request->getVar('room_id');

        $room_assets = $this->ReservationRoomAsset
            ->select('AS_ASSET, RA_QUANTITY, RRA_REMARKS')
            ->where('RRA_RESERVATION_ID', $reservation_id)
            ->where('RRA_ROOM_ID', $room_id)
            ->join('FLXY_ROOM_ASSETS', 'RRA_ROOM_ASSET_ID = RA_ID', 'left')
            ->join('FLXY_ASSETS', 'RA_ASSET_ID = AS_ID', 'left')
            ->join('FLXY_ROOM', 'RM_ID = RRA_ROOM_ID', 'left')
            ->findAll();

        $result = responseJson(200, false, ['msg' => 'Assets handover list'], $room_assets);
        return $this->respond($result);
    }

    public function submitAssetHandoverForm()
    {
        $this->request->getVar();
    }
}
