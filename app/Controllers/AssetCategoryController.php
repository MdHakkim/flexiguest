<?php

namespace App\Controllers;

use App\Controllers\Repositories\AssetCategoryRepository;
use App\Controllers\Repositories\AssetRepository;
use App\Libraries\ServerSideDataTable;
use CodeIgniter\API\ResponseTrait;

class AssetCategoryController extends BaseController
{

    use ResponseTrait;

    private $AssetCategoryRepository;
    private $AssetRepository;

    public function __construct()
    {
        $this->AssetCategoryRepository = new AssetCategoryRepository();
        $this->AssetRepository = new AssetRepository();
    }

    public function assetCategory()
    {
        $data['title'] = getMethodName();
        $data['session'] = session();

        return view('frontend/room_assets/asset_category', $data);
    }

    public function allAssetCategories()
    {
        $mine = new ServerSideDataTable();
        $tableName = 'FLXY_ASSET_CATEGORIES';
        $columns = 'AC_ID,AC_CATEGORY,AC_CREATED_AT';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }

    public function store()
    {
        $user = session('user');
        $data = $this->request->getPost();

        if (!$this->validate($this->AssetCategoryRepository->validationRules()))
            return $this->respond(responseJson(403, true, $this->validator->getErrors()));

        $result = $this->AssetCategoryRepository->createUpdateCategory($user, $data);
        return $this->respond($result);
    }

    public function edit()
    {
        $id = $this->request->getPost('id');

        $asset_category = $this->AssetCategoryRepository->assetCategoryById($id);
        if ($asset_category)
            return $this->respond(responseJson(200, false, ['msg' => "Asset Category"], $asset_category));

        return $this->respond(responseJson(404, true, ['msg' => "Asset Category not found"]));
    }

    public function delete()
    {
        $id = $this->request->getPost('id');

        $check = $this->AssetRepository->assetByCategories([$id]);
        if(!empty($check))
            return $this->respond(responseJson(202, true, ['mag' => 'This category cannot be deleted because there are assets associated with it.']));

        $result = $this->AssetCategoryRepository->deleteAssetCategory($id);
        $result = $result
            ? responseJson(200, false, ['msg' => 'Asset Category deleted successfully'])
            : responseJson(500, true, "record not deleted");

        return $this->respond($result);
    }
}