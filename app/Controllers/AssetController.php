<?php

namespace App\Controllers;

use App\Controllers\Repositories\AssetCategoryRepository;
use App\Controllers\Repositories\AssetRepository;
use App\Controllers\Repositories\ReservationAssetRepository;
use App\Controllers\Repositories\RoomAssetRepository;
use App\Libraries\ServerSideDataTable;
use CodeIgniter\API\ResponseTrait;

class AssetController extends BaseController
{

    use ResponseTrait;

    private $AssetCategoryRepository;
    private $AssetRepository;
    private $RoomAssetRepository;
    private $ReservationAssetRepository;

    public function __construct()
    {
        $this->AssetCategoryRepository = new AssetCategoryRepository();
        $this->AssetRepository = new AssetRepository();
        $this->RoomAssetRepository = new RoomAssetRepository();
        $this->ReservationAssetRepository = new ReservationAssetRepository();
    }

    public function asset()
    {
        $data['title'] = getMethodName();
        $data['session'] = session();
        $data['asset_categories'] = $this->AssetCategoryRepository->allAssetCategories();

        return view('frontend/room_assets/asset', $data);
    }

    public function allAssets()
    {
        $mine = new ServerSideDataTable();
        $tableName = 'FLXY_ASSETS left join FLXY_ASSET_CATEGORIES on AS_ASSET_CATEGORY_ID = AC_ID';
        $columns = 'AS_ID,AS_ASSET_CATEGORY_ID,AS_ASSET,AS_PRICE,AS_CREATED_AT,AC_CATEGORY';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }

    public function store()
    {
        $user = session('user');
        $data = $this->request->getPost();

        if (!$this->validate($this->AssetRepository->validationRules()))
            return $this->respond(responseJson(403, true, $this->validator->getErrors()));

        $result = $this->AssetRepository->createUpdateAsset($user, $data);
        return $this->respond($result);
    }

    public function edit()
    {
        $id = $this->request->getPost('id');

        $asset = $this->AssetRepository->assetById($id);
        if ($asset)
            return $this->respond(responseJson(200, false, ['msg' => "Asset"], $asset));

        return $this->respond(responseJson(404, true, ['msg' => "Asset not found"]));
    }

    public function delete()
    {
        $id = $this->request->getPost('id');

        $check = $this->RoomAssetRepository->roomAssetsByAssetId($id);
        if (!empty($check))
            return $this->respond(responseJson(202, true, ['mag' => 'It cannot be deleted because it is assigned to some rooms.']));

        $check = $this->ReservationAssetRepository->getReservationAssets("RRA_ASSET_ID = $id");
        if (!empty($check))
            return $this->respond(responseJson(202, true, ['mag' => 'It cannot be deleted because it is assigned to some reservation rooms.']));

        $result = $this->AssetRepository->deleteAsset($id);
        $result = $result
            ? responseJson(200, false, ['msg' => 'Asset deleted successfully'])
            : responseJson(500, true, "record not deleted");

        return $this->respond($result);
    }

    public function assetByCategories()
    {
        $category_ids = $this->request->getVar('category_ids');
        $result = $this->AssetRepository->assetByCategories($category_ids);

        return $this->respond(responseJson(200, false, ['msg' => 'categories'], $result));
    }
}
