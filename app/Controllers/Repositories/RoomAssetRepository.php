<?php

namespace App\Controllers\Repositories;

use App\Controllers\BaseController;
use App\Models\RoomAsset;
use CodeIgniter\API\ResponseTrait;

class RoomAssetRepository extends BaseController
{
    use ResponseTrait;

    private $RoomAsset;

    public function __construct()
    {
        $this->RoomAsset = new RoomAsset();
    }

    public function validationRules()
    {
        return [
            'RA_ROOM_ID' => [
                'label' => 'room',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Please select a room.'
                ]
            ],
            'RA_ASSETS' => [
                'label' => 'asset',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Please add atleast one asset.',
                ]
            ],
        ];
    }

    public function createUpdateRoomAsset($user, $data)
    {
        $user_id = $user['USR_ID'];

        $this->deleteRoomAssets($data['RA_ROOM_ID']);
        foreach ($data['RA_ASSETS'] as $asset) {
            $data = [
                'RA_ROOM_ID' => $data['RA_ROOM_ID'],
                'RA_ASSET_ID' => $asset['RA_ASSET_ID'],
                'RA_QUANTITY' => $asset['RA_QUANTITY'],
                'RA_CREATED_BY' => $user_id,
                'RA_UPDATED_BY' => $user_id
            ];

            $this->RoomAsset->save($data);
        }

        return responseJson(200, false, ['msg' => 'Room Assets created/updated successfully'], $response = '');
    }

    public function roomAssetsByRoomId($room_id)
    {
        return $this->RoomAsset->where('RA_ROOM_ID', $room_id)->findAll();
    }

    public function deleteRoomAssets($room_id)
    {
        return $this->RoomAsset->where('RA_ROOM_ID', $room_id)->delete();
    }
}
