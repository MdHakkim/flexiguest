<?php

namespace App\Controllers;

use App\Controllers\Repositories\AssetCategoryRepository;
use App\Controllers\Repositories\RoomAssetRepository;
use App\Controllers\Repositories\RoomRepository;
use App\Libraries\DataTables\RoomAssetDataTable;
use CodeIgniter\API\ResponseTrait;

class RoomAssetController extends BaseController
{

    use ResponseTrait;

    private $AssetCategoryRepository;
    private $RoomRepository;
    private $RoomAssetRepository;

    public function __construct()
    {
        $this->AssetCategoryRepository = new AssetCategoryRepository();
        $this->RoomRepository = new RoomRepository();
        $this->RoomAssetRepository = new RoomAssetRepository();
    }

    public function roomAsset()
    {
        $data['title'] = getMethodName();
        $data['session'] = session();
        $data['rooms'] = $this->RoomRepository->allRooms();
        $data['asset_categories'] = $this->AssetCategoryRepository->allAssetCategories();

        return view('frontend/room_assets/room_asset', $data);
    }

    public function allRoomAssets()
    {
        $mine = new RoomAssetDataTable();
        $tableName = 'FLXY_ROOM_ASSETS left join FLXY_ROOM on RA_ROOM_ID = RM_ID';
        $columns = 'RA_ROOM_ID,RA_CREATED_AT,RM_NO';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }

    public function store()
    {
        $user = session('user');
        $data = $this->request->getPost();

        if (!$this->validate($this->RoomAssetRepository->validationRules()))
            return $this->respond(responseJson(403, true, $this->validator->getErrors()));

        $result = $this->RoomAssetRepository->createUpdateRoomAsset($user, $data);
        return $this->respond($result);
    }

    public function edit()
    {
        $room_id = $this->request->getPost('room_id');

        $assets = $this->RoomAssetRepository->roomAssetsByRoomId($room_id);
        if ($assets)
            return $this->respond(responseJson(200, false, ['msg' => "Room Assets."], $assets));

        return $this->respond(responseJson(404, true, ['msg' => "Room Assets not found."]));
    }

    // public function delete()
    // {
    //     $room_id = $this->request->getPost('room_id');

    //     $result = $this->RoomAssetRepository->deleteRoomAssets($room_id);
    //     $result = $result
    //         ? responseJson(200, false, ['msg' => 'Room Assets deleted successfully.'])
    //         : responseJson(500, true, "record not deleted.");

    //     return $this->respond($result);
    // }
}