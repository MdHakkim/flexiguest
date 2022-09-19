<?php

namespace App\Controllers;

use App\Controllers\Repositories\RestaurantRepository;
use CodeIgniter\API\ResponseTrait;

class RestaurantController extends BaseController
{

    use ResponseTrait;

    private $RestaurantRepository;

    public function __construct()
    {
        $this->RestaurantRepository = new RestaurantRepository();
    }

    public function Restaurant()
    {
        $data['title'] = getMethodName();
        $data['session'] = session();

        return view('frontend/restaurant/restaurant', $data);
    }

    public function allRestaurant()
    {
        $this->RestaurantRepository->allRestaurant();
    }

    public function storeRestaurant()
    {
        $user_id = session('USR_ID');

        if (!$this->validate($this->RestaurantRepository->restaurantValidationRules()))
            return $this->respond(responseJson(403, true, $this->validator->getErrors()));

        $data = $this->request->getPost();
        
        $result = $this->RestaurantRepository->storeRestaurant($user_id, $data);
        return $this->respond($result);
    }

    public function editRestaurant()
    {
        $id = $this->request->getPost('id');

        $restaurant = $this->RestaurantRepository->restaurantById($id);

        if ($restaurant)
            return $this->respond($restaurant);

        return $this->respond(responseJson(404, true, ['msg' => "Restaurant not found"]));
    }

    public function deleteRestaurant()
    {
        $restaurant_id = $this->request->getPost('id');

        $result = $this->RestaurantRepository->deleteRestaurant($restaurant_id);

        $result = $result
            ? responseJson(200, false, ['msg' => "Restaurant deleted successfully."])
            : responseJson(500, true, ['msg' => "Restaurant not deleted"]);

        return $this->respond($result);
    }

    public function menuCategory()
    {
        $data['title'] = getMethodName();
        $data['session'] = session();

        $data['restaurants'] = $this->RestaurantRepository->allRestaurants();

        return view('frontend/restaurant/menu_category', $data);
    }

    public function allMenuCategory()
    {
        $this->RestaurantRepository->allMenuCategory();
    }

    public function storeMenuCategory()
    {
        $user_id = session('USR_ID');

        if (!$this->validate($this->RestaurantRepository->menuCategoryValidationRules()))
            return $this->respond(responseJson(403, true, $this->validator->getErrors()));

        $data = $this->request->getPost();
        
        $result = $this->RestaurantRepository->storeMenuCategory($user_id, $data);
        return $this->respond($result);
    }

    public function editMenuCategory()
    {
        $id = $this->request->getPost('id');

        $restaurant = $this->RestaurantRepository->menuCategoryById($id);

        if ($restaurant)
            return $this->respond($restaurant);

        return $this->respond(responseJson(404, true, ['msg' => "Menu Category not found"]));
    }

    public function deleteMenuCategory()
    {
        $menu_category_id = $this->request->getPost('id');

        $result = $this->RestaurantRepository->deleteMenuCategory($menu_category_id);

        $result = $result
            ? responseJson(200, false, ['msg' => "Menu Category deleted successfully."])
            : responseJson(500, true, ['msg' => "Menu Category not deleted"]);

        return $this->respond($result);
    }
}