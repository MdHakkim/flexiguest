<?php

namespace App\Controllers\Repositories;

use App\Controllers\BaseController;
use App\Libraries\ServerSideDataTable;
use App\Models\MenuCategory;
use App\Models\Restaurant;
use CodeIgniter\API\ResponseTrait;

class RestaurantRepository extends BaseController
{
    use ResponseTrait;

    private $Restaurant;
    private $MenuCategory;

    public function __construct()
    {
        $this->Restaurant = new Restaurant();
        $this->MenuCategory = new MenuCategory();
    }

    public function restaurantValidationRules()
    {
        return [
            'RE_RESTAURANT' => ['label' => 'restaurant name', 'rules' => 'required'],
        ];
    }

    public function allRestaurant()
    {
        $mine = new ServerSideDataTable();
        $tableName = 'FLXY_RESTAURANTS';
        $columns = 'RE_ID,RE_RESTAURANT,RE_CREATED_AT';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }

    public function restaurantById($id)
    {
        return $this->Restaurant->find($id);
    }

    public function storeRestaurant($user_id, $data)
    {
        $id = $data['id'];
        unset($data['id']);

        if (empty($id)) {
            $data['RE_CREATED_BY'] = $data['RE_UPDATED_BY'] = $user_id;
            $response = $this->Restaurant->insert($data);
        } else {
            $data['RE_UPDATED_BY'] = $user_id;
            $response = $this->Restaurant->update($id, $data);
        }

        if (!$response)
            return responseJson(500, false, ['msg' => "db insert/update not successful"]);

        if (empty($id))
            $msg = 'Restaurant has been created successflly.';
        else
            $msg = 'Restaurant has been updated successflly.';

        return responseJson(200, false, ['msg' => $msg]);
    }

    public function deleteRestaurant($restaurant_id)
    {
        return $this->Restaurant->delete($restaurant_id);
    }

    public function allRestaurants()
    {
        return $this->Restaurant->findAll();
    }

    public function menuCategoryValidationRules()
    {
        return [
            'MC_RESTAURANT_ID' => ['label' => 'restaurant', 'rules' => 'required', 'errors' => ['required' => 'Please select a restaurant.']],
            'MC_CATEGORY' => ['label' => 'category', 'rules' => 'required'],
        ];
    }

    public function allMenuCategory()
    {
        $mine = new ServerSideDataTable();
        $tableName = 'FLXY_MENU_CATEGORIES left join FLXY_RESTAURANTS on MC_RESTAURANT_ID = RE_ID';
        $columns = 'MC_ID,MC_CATEGORY,MC_RESTAURANT_ID,RE_RESTAURANT,MC_CREATED_AT';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }

    public function menuCategoryById($id)
    {
        return $this->MenuCategory->find($id);
    }

    public function storeMenuCategory($user_id, $data)
    {
        $id = $data['id'];
        unset($data['id']);

        if (empty($id)) {
            $data['MC_CREATED_BY'] = $data['MC_UPDATED_BY'] = $user_id;
            $response = $this->MenuCategory->insert($data);
        } else {
            $data['RE_UPDATED_BY'] = $user_id;
            $response = $this->MenuCategory->update($id, $data);
        }

        if (!$response)
            return responseJson(500, false, ['msg' => "db insert/update not successful"]);

        if (empty($id))
            $msg = 'Menu Category has been created successflly.';
        else
            $msg = 'Menu Category has been updated successflly.';

        return responseJson(200, false, ['msg' => $msg]);
    }

    public function deleteMenuCategory($menu_category_id)
    {
        return $this->MenuCategory->delete($menu_category_id);
    }
}