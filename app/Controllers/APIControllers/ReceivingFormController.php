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
        $reservation_id = $this->request->getVar('reservation_id');
        $room_id = $this->request->getVar('room_id');

        $room_assets = $this->ReservationRoomAsset
            ->select('RRA_ID, AS_ASSET, RA_QUANTITY, RRA_REMARKS')
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
        $assets = $this->request->getVar('assets');

        foreach($assets as $asset) {
            $data = [
                'RRA_ID' => $asset->RRA_ID,
                'RRA_REMARKS' => $asset->RRA_REMARKS,
                'RRA_STATUS' => 'Completed',
            ];

            $this->ReservationRoomAsset->save($data);
        }

        return $this->respond(responseJson(200, false, ['msg' => 'Form Submitted']));
    }
}
