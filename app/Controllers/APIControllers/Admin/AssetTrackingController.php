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
            ->select("distinct(RESV_ID), RESV_ROOM as ROOM_NO, RM_ID as ROOM_ID,
                    concat(CUST_FIRST_NAME, ' ', CUST_LAST_NAME) as GUEST_NAME")
            ->join('FLXY_ROOM', 'RESV_ROOM = RM_NO', 'left')
            ->join('FLXY_CUSTOMER', 'RESV_NAME = CUST_ID', 'left')
            ->where('RESV_STATUS', 'Checked-In')
            ->findAll();



        foreach ($rooms as $index => $room) {
            $assets = $this->ReservationRoomAsset
                ->select("AS_ID, AS_ASSET, AS_PRICE, RA_QUANTITY, RRA_STATUS, RRA_TRACKING_REMARKS, RRA_ID,
                case when RRA_STATUS = 'Verified' then 1 else 0 end as IS_VERIFIED")
                ->join('FLXY_ROOM_ASSETS', 'RRA_ROOM_ID = RA_ROOM_ID and  RRA_ASSET_ID = RA_ASSET_ID', 'left')
                ->join('FLXY_ASSETS', 'RRA_ASSET_ID = AS_ID', 'left')
                ->where('RRA_RESERVATION_ID', $room['RESV_ID'])
                ->where('RRA_ROOM_ID', $room['ROOM_ID'])
                ->findAll();
            
            if(!count($assets)) {
                unset($rooms[$index]);
            }
            else{
                $rooms[$index]['RRA_STATUS'] = ($assets[0]['RRA_STATUS'] == 'Verified' || $assets[0]['RRA_STATUS'] == 'Discrepancy') 
                                                ? 'Verified' : $assets[0]['RRA_STATUS']; 
                $rooms[$index]['ASSETS'] = $assets;
            }
        }

        return $this->respond(responseJson(200, false, ['msg' => 'assets list'], $rooms));
    }

    public function submitForm()
    {
        $assets = $this->request->getVar('assets');

        foreach ($assets as $asset) {

            $status = 'Discrepancy';
            if ($asset->IS_VERIFIED)
                $status = 'Verified';

            $data = [
                'RRA_ID' => $asset->RRA_ID,
                'RRA_TRACKING_REMARKS' => $asset->RRA_TRACKING_REMARKS,
                'RRA_STATUS' => $status,
            ];

            $this->ReservationRoomAsset->save($data);
        }

        return $this->respond(responseJson(200, false, ['msg' => 'Form Submitted']));
    }
}
