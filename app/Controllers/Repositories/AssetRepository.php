<?php

namespace App\Controllers\Repositories;

use App\Controllers\BaseController;
use App\Models\Asset;
use CodeIgniter\API\ResponseTrait;

class AssetRepository extends BaseController
{
    use ResponseTrait;

    private $Asset;

    public function __construct()
    {
        $this->Asset = new Asset();
    }

    public function validationRules()
    {
        return [
            'AS_ASSET_CATEGORY_ID' => ['label' => 'category', 'rules' => 'required', 'errors' => ['required' => 'Please select a category.']],
            'AS_ASSET' => ['label' => 'asset', 'rules' => 'required'],
            'AS_PRICE' => ['label' => 'price', 'rules' => 'required'],
        ];
    }

    public function createUpdateAsset($user, $data)
    {
        $user_id = $user['USR_ID'];

        if (!empty($data['AS_ID']))
            $data['AS_UPDATED_BY'] = $user_id;
        else
            $data['AS_UPDATED_BY'] = $data['AS_CREATED_BY'] = $user_id;

        $response = $this->Asset->save($data);

        return $response
            ? responseJson(200, false, ['msg' => 'Asset created/updated successfully'], $response = '')
            : responseJson(500, true, ['msg' => 'db insert/update not successfull']);
    }

    public function assetById($id)
    {
        return $this->Asset->find($id);
    }

    public function deleteAsset($id)
    {
        return $this->Asset->delete($id);
    }

    public function assetByCategories($category_ids)
    {   
        return $this->Asset->whereIn('AS_ASSET_CATEGORY_ID', $category_ids)->findAll();
    }
}
