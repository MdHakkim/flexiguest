<?php

namespace App\Controllers;

use App\Controllers\Repositories\AssetCategoryRepository;
use App\Controllers\Repositories\ReservationAssetRepository;
use App\Controllers\Repositories\RoomRepository;
use App\Libraries\DataTables\ReservationAssetDataTable;
use CodeIgniter\API\ResponseTrait;

class ReservationAssetController extends BaseController
{

    use ResponseTrait;

    private $AssetCategoryRepository;
    private $RoomRepository;
    private $ReservationAssetRepository;

    public function __construct()
    {
        $this->AssetCategoryRepository = new AssetCategoryRepository();
        $this->RoomRepository = new RoomRepository();
        $this->ReservationAssetRepository = new ReservationAssetRepository();
    }

    public function reservationAsset()
    {
        $data['title'] = getMethodName();
        $data['session'] = session();

        $data['rooms'] = $this->RoomRepository->allRooms();
        $data['asset_categories'] = $this->AssetCategoryRepository->allAssetCategories();

        return view('frontend/room_assets/reservation_asset', $data);
    }

    public function allReservationAssets()
    {
        $mine = new ReservationAssetDataTable();
        $tableName = 'FLXY_RESERVATION_ROOM_ASSETS left join FLXY_ROOM on RRA_ROOM_ID = RM_ID';
        $columns = 'RRA_RESERVATION_ID,RRA_ROOM_ID,RRA_CREATED_AT,RM_NO';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }
}