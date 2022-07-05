<?php

namespace App\Controllers\APIControllers\Guest;

use App\Controllers\BaseController;
use App\Models\Product;
use CodeIgniter\API\ResponseTrait;

class ProductController extends BaseController
{
    use ResponseTrait;

    private $Product;

    public function __construct()
    {
        $this->Product = new Product();
    }

    public function allProducts()
    {
        $products = $this->Product->where('PR_QUANTITY >', 0)->findAll();
        foreach ($products as $index => $product) {
            $products[$index]['PR_IMAGE'] = base_url($product['PR_IMAGE']);
        }

        return $this->respond(responseJson(200, false, ['msg' => 'Product List'], $products));
    }
}
