<?php

namespace App\Controllers\Repositories;

use App\Controllers\BaseController;
use App\Models\AssetCategory;
use CodeIgniter\API\ResponseTrait;

class AssetCategoryRepository extends BaseController
{
    use ResponseTrait;

    private $AssetCategory;

    public function __construct()
    {
        $this->AssetCategory = new AssetCategory();
    }

    public function validationRules()
    {
        return [
            'AC_CATEGORY' => ['label' => 'category name', 'rules' => 'required'],
        ];
    }

    public function createUpdateCategory($user, $data)
    {
        $user_id = $user['USR_ID'];

        if (!empty($data['AC_ID']))
            $data['AC_UPDATED_BY'] = $user_id;
        else
            $data['AC_UPDATED_BY'] = $data['AC_CREATED_BY'] = $user_id;

        $response = $this->AssetCategory->save($data);

        return $response
            ? responseJson(200, false, ['msg' => 'Asset Category created/updated successfully'], $response = '')
            : responseJson(500, true, ['msg' => 'db insert/update not successfull']);
    }

    public function assetCategoryById($id)
    {
        return $this->AssetCategory->find($id);
    }

    public function deleteAssetCategory($id)
    {
        return $this->AssetCategory->delete($id);
    }

    public function allAssetCategories()
    {
        return $this->AssetCategory->findAll();
    }
}
