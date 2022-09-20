<?php

namespace App\Controllers\Repositories;

use App\Controllers\BaseController;
use App\Libraries\ServerSideDataTable;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Restaurant;
use CodeIgniter\API\ResponseTrait;

class RestaurantRepository extends BaseController
{
    use ResponseTrait;

    private $Restaurant;
    private $MenuCategory;
    private $MenuItem;

    public function __construct()
    {
        $this->Restaurant = new Restaurant();
        $this->MenuCategory = new MenuCategory();
        $this->MenuItem = new MenuItem();
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

    public function menuItemValidationRules()
    {
        return [
            'MI_RESTAURANT_ID' => ['label' => 'restaurant', 'rules' => 'required', 'errors' => ['required' => 'Please select a restaurant.']],
            'MI_MENU_CATEGORY_ID' => ['label' => 'category', 'rules' => 'required', 'errors' => ['required' => 'Please select a category.']],
            'MI_ITEM' => ['label' => 'item', 'rules' => 'required'],
            'MI_PRICE' => ['label' => 'price', 'rules' => 'required'],
            'MI_QUANTITY' => ['label' => 'quantity', 'rules' => 'required'],
        ];
    }

    public function allMenuItem()
    {
        $mine = new ServerSideDataTable();
        $tableName = 'FLXY_MENU_ITEMS left join FLXY_RESTAURANTS on MI_RESTAURANT_ID = RE_ID left join FLXY_MENU_CATEGORIES on MI_MENU_CATEGORY_ID = MC_ID';
        $columns = 'MI_ID,MI_RESTAURANT_ID,MI_MENU_CATEGORY_ID,MI_ITEM,MI_PRICE,MI_QUANTITY,MI_SEQUENCE,MI_CREATED_AT,RE_RESTAURANT,MC_CATEGORY';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }

    public function menuItemById($id)
    {
        return $this->MenuItem->find($id);
    }

    public function storeMenuItem($user_id, $data)
    {
        $id = $data['id'];
        unset($data['id']);

        if (empty($id)) {
            $data['MI_CREATED_BY'] = $data['MI_UPDATED_BY'] = $user_id;
            $response = $this->MenuItem->insert($data);
        } else {
            $data['MI_UPDATED_BY'] = $user_id;
            $response = $this->MenuItem->update($id, $data);
        }

        if (!$response)
            return responseJson(500, false, ['msg' => "db insert/update not successful"]);

        if (empty($id))
            $msg = 'Menu Item has been created successflly.';
        else
            $msg = 'Menu Item has been updated successflly.';

        return responseJson(200, false, ['msg' => $msg]);
    }

    public function deleteMenuItem($menu_item_id)
    {
        return $this->MenuItem->delete($menu_item_id);
    }

    public function menuCategoriesByRestaurant($restaurant_id)
    {
        return $this->MenuCategory->where('MC_RESTAURANT_ID', $restaurant_id)->findAll();
    }
}