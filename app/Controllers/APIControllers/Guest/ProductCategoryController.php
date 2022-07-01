<?php

namespace App\Controllers\APIControllers\Guest;

use App\Controllers\BaseController;
use App\Models\ProductCategory;
use CodeIgniter\API\ResponseTrait;

class ProductCategoryController extends BaseController
{
    use ResponseTrait;

    private $ProductCategory;

    public function __construct()
    {
        $this->ProductCategory = new ProductCategory();
    }

    public function allCategories()
    {
        $product_cateogries = $this->ProductCategory->findAll();

        return $this->respond(responseJson(200, false, ['category list'], $product_cateogries));
    }
}