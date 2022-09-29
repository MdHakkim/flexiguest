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

        $data = $this->request->getPost();

        if($this->request->getFile('RE_IMAGE_URL'))
            $data['RE_IMAGE_URL'] = $this->request->getFile('RE_IMAGE_URL');

        if (!$this->validate($this->RestaurantRepository->restaurantValidationRules($data)))
            return $this->respond(responseJson(403, true, $this->validator->getErrors()));
        
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

        $data = $this->request->getPost();
        if($this->request->getFile('MC_IMAGE_URL'))
            $data['MC_IMAGE_URL'] = $this->request->getFile('MC_IMAGE_URL');

        if (!$this->validate($this->RestaurantRepository->menuCategoryValidationRules($data)))
            return $this->respond(responseJson(403, true, $this->validator->getErrors()));
        
        $result = $this->RestaurantRepository->storeMenuCategory($user_id, $data);
        return $this->respond($result);
    }

    public function editMenuCategory()
    {
        $id = $this->request->getPost('id');

        $menu_category = $this->RestaurantRepository->menuCategoryById($id);

        if ($menu_category)
            return $this->respond($menu_category);

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

    /** ------------------------------Menu Item------------------------------ */ 

    public function menuItem()
    {
        $data['title'] = getMethodName();
        $data['session'] = session();

        $data['restaurants'] = $this->RestaurantRepository->allRestaurants();
        $data['meal_types'] = $this->RestaurantRepository->allMealTypes();

        return view('frontend/restaurant/menu_item', $data);
    }

    public function allMenuItem()
    {
        $this->RestaurantRepository->allMenuItem();
    }

    public function storeMenuItem()
    {
        $user_id = session('USR_ID');

        $data = $this->request->getPost();
        if($this->request->getFile('MI_IMAGE_URL'))
            $data['MI_IMAGE_URL'] = $this->request->getFile('MI_IMAGE_URL');

        if (!$this->validate($this->RestaurantRepository->menuItemValidationRules($data)))
            return $this->respond(responseJson(403, true, $this->validator->getErrors()));
        
        $result = $this->RestaurantRepository->storeMenuItem($user_id, $data);
        return $this->respond($result);
    }

    public function editMenuItem()
    {
        $id = $this->request->getPost('id');

        $restaurant = $this->RestaurantRepository->menuItemById($id);

        if ($restaurant)
            return $this->respond($restaurant);

        return $this->respond(responseJson(404, true, ['msg' => "Menu Item not found"]));
    }

    public function deleteMenuItem()
    {
        $menu_item_id = $this->request->getPost('id');

        $result = $this->RestaurantRepository->deleteMenuItem($menu_item_id);

        $result = $result
            ? responseJson(200, false, ['msg' => "Menu Item deleted successfully."])
            : responseJson(500, true, ['msg' => "Menu Item not deleted"]);

        return $this->respond($result);
    }

    public function menuCategoriesByRestaurant()
    {
        $restaurant_id = $this->request->getVar('restaurant_id');
        $result = $this->RestaurantRepository->menuCategoriesByRestaurant($restaurant_id);

        return $this->respond(responseJson(200, false, ['msg' => 'list'], $result));
    }
    
    /** ------------------------------Meal Type------------------------------ */ 
    public function mealType()
    {
        $data['title'] = getMethodName();
        $data['session'] = session();

        return view('frontend/restaurant/meal_type', $data);
    }

    public function allMealType()
    {
        $this->RestaurantRepository->allMealType();
    }

    public function storeMealType()
    {
        $user_id = session('USR_ID');

        $data = $this->request->getPost();
        if($this->request->getFile('MT_IMAGE_URL'))
            $data['MT_IMAGE_URL'] = $this->request->getFile('MT_IMAGE_URL');

        if (!$this->validate($this->RestaurantRepository->mealTypeValidationRules($data)))
            return $this->respond(responseJson(403, true, $this->validator->getErrors()));
        
        $result = $this->RestaurantRepository->storeMealType($user_id, $data);
        return $this->respond($result);
    }

    public function editMealType()
    {
        $id = $this->request->getPost('id');

        $meal_type = $this->RestaurantRepository->mealTypeById($id);

        if ($meal_type)
            return $this->respond($meal_type);

        return $this->respond(responseJson(404, true, ['msg' => "Meal Type not found"]));
    }

    public function deleteMealType()
    {
        $meal_type_id = $this->request->getPost('id');

        $result = $this->RestaurantRepository->deleteMealType($meal_type_id);

        $result = $result
            ? responseJson(200, false, ['msg' => "Meal Type deleted successfully."])
            : responseJson(500, true, ['msg' => "Meal Type not deleted"]);

        return $this->respond($result);
    }

    /** ------------------------------API------------------------------ */ 
    public function allRestaurants()
    {
        $result = $this->RestaurantRepository->allRestaurants();

        return $this->respond(responseJson(200, false, ['msg' => 'restaurants'], $result));
    }

    public function menuCategories()
    {
        $result = $this->RestaurantRepository->menuCategories();

        return $this->respond(responseJson(200, false, ['msg' => 'categories'], $result));
    }

    public function mainScreen()
    {
        $data['meal_types'] = $this->RestaurantRepository->allMealTypes();
        $data['menu_categories'] = $this->RestaurantRepository->menuCategories();
        $data['restaurants'] = $this->RestaurantRepository->allRestaurants();

        foreach($data['restaurants'] as $index => $restaurant) {
            $data['restaurants'][$index]['menu_items'] = $this->RestaurantRepository->getMenuItem("MI_RESTAURANT_ID = {$restaurant['RE_ID']}");
        }

        return $this->respond(responseJson(200, false, ['msg' => 'main screen'], $data));
    }
}