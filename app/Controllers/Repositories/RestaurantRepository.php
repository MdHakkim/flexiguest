<?php

namespace App\Controllers\Repositories;

use App\Controllers\BaseController;
use App\Libraries\ServerSideDataTable;
use App\Models\MealType;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Restaurant;
use App\Models\RestaurantCart;
use App\Models\RestaurantOrder;
use App\Models\RestaurantOrderDetail;
use CodeIgniter\API\ResponseTrait;

class RestaurantRepository extends BaseController
{
    use ResponseTrait;

    private $Restaurant;
    private $MenuCategory;
    private $MenuItem;
    private $MealType;
    private $RestaurantOrder;
    private $RestaurantOrderDetail;
    private $RestaurantCart;

    public function __construct()
    {
        $this->Restaurant = new Restaurant();
        $this->MenuCategory = new MenuCategory();
        $this->MenuItem = new MenuItem();
        $this->MealType = new MealType();
        $this->RestaurantOrder = new RestaurantOrder();
        $this->RestaurantOrderDetail = new RestaurantOrderDetail();
        $this->RestaurantCart = new RestaurantCart();
    }

    public function restaurantValidationRules($data)
    {
        $rules = [
            'RE_RESTAURANT' => ['label' => 'restaurant name', 'rules' => 'required'],
        ];

        if (empty($data['id']) || !empty($data['RE_IMAGE_URL']))
            $rules['RE_IMAGE_URL'] = [
                'label' => 'restaurant image',
                'rules' => ['uploaded[RE_IMAGE_URL]', 'mime_in[RE_IMAGE_URL,image/png,image/jpg,image/jpeg]', 'max_size[RE_IMAGE_URL,5048]']
            ];

        return $rules;
    }

    public function allRestaurant()
    {
        $mine = new ServerSideDataTable();
        $tableName = 'FLXY_RESTAURANTS';
        $columns = 'RE_ID,RE_RESTAURANT,RE_IMAGE_URL,RE_CREATED_AT';
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

        if (!empty($data['RE_IMAGE_URL'])) {
            $image = $data['RE_IMAGE_URL'];
            $image_name = $image->getName();
            $directory = "assets/Uploads/restaurant/restaurant_images/";

            $response = documentUpload($image, $image_name, $user_id, $directory);

            if ($response['SUCCESS'] != 200)
                return responseJson(500, true, ['msg' => 'image not uploaded']);

            $data['RE_IMAGE_URL'] = $directory . $response['RESPONSE']['OUTPUT'];
        }


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

    public function menuCategoryValidationRules($data)
    {
        $rules = [
            'MC_RESTAURANT_ID' => ['label' => 'restaurant', 'rules' => 'required', 'errors' => ['required' => 'Please select a restaurant.']],
            'MC_CATEGORY' => ['label' => 'category', 'rules' => 'required'],
        ];

        if (empty($data['id']) || !empty($data['MC_IMAGE_URL']))
            $rules['MC_IMAGE_URL'] = [
                'label' => 'category image',
                'rules' => ['uploaded[MC_IMAGE_URL]', 'mime_in[MC_IMAGE_URL,image/png,image/jpg,image/jpeg]', 'max_size[MC_IMAGE_URL,5048]']
            ];

        return $rules;
    }

    public function allMenuCategory()
    {
        $mine = new ServerSideDataTable();
        $tableName = 'FLXY_MENU_CATEGORIES left join FLXY_RESTAURANTS on MC_RESTAURANT_ID = RE_ID';
        $columns = 'MC_ID,MC_CATEGORY,MC_IMAGE_URL,MC_RESTAURANT_ID,RE_RESTAURANT,MC_CREATED_AT';
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

        if (!empty($data['MC_IMAGE_URL'])) {
            $image = $data['MC_IMAGE_URL'];
            $image_name = $image->getName();
            $directory = "assets/Uploads/restaurant/menu_category_images/";

            $response = documentUpload($image, $image_name, $user_id, $directory);

            if ($response['SUCCESS'] != 200)
                return responseJson(500, true, ['msg' => 'image not uploaded']);

            $data['MC_IMAGE_URL'] = $directory . $response['RESPONSE']['OUTPUT'];
        }

        if (empty($id)) {
            $data['MC_CREATED_BY'] = $data['MC_UPDATED_BY'] = $user_id;
            $response = $this->MenuCategory->insert($data);
        } else {
            $data['MC_UPDATED_BY'] = $user_id;
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

    public function menuItemValidationRules($data)
    {
        $rules = [
            'MI_RESTAURANT_ID' => ['label' => 'restaurant', 'rules' => 'required', 'errors' => ['required' => 'Please select a restaurant.']],
            'MI_MENU_CATEGORY_ID' => ['label' => 'category', 'rules' => 'required', 'errors' => ['required' => 'Please select a category.']],
            'MI_MEAL_TYPE_ID' => ['label' => 'meal type', 'rules' => 'required', 'errors' => ['required' => 'Please select a meal type.']],
            'MI_ITEM' => ['label' => 'item', 'rules' => 'required'],
            'MI_PRICE' => ['label' => 'price', 'rules' => 'required'],
            'MI_IS_AVAILABLE' => ['label' => 'available', 'rules' => 'required', 'errors' => ['required' => 'Please select availability.']],
        ];

        if (empty($data['id']) || !empty($data['MI_IMAGE_URL']))
            $rules['MI_IMAGE_URL'] = [
                'label' => 'item image',
                'rules' => ['uploaded[MI_IMAGE_URL]', 'mime_in[MI_IMAGE_URL,image/png,image/jpg,image/jpeg]', 'max_size[MI_IMAGE_URL,5048]']
            ];

        return $rules;
    }

    public function allMenuItem()
    {
        $mine = new ServerSideDataTable();
        $tableName = 'FLXY_MENU_ITEMS left join FLXY_RESTAURANTS on MI_RESTAURANT_ID = RE_ID left join FLXY_MENU_CATEGORIES on MI_MENU_CATEGORY_ID = MC_ID left join FLXY_MEAL_TYPES on MI_MEAL_TYPE_ID = MT_ID';
        $columns = 'MI_ID,MI_RESTAURANT_ID,MI_MENU_CATEGORY_ID,MI_MEAL_TYPE_ID,MI_ITEM,MI_IMAGE_URL,MI_PRICE,MI_IS_AVAILABLE,MI_SEQUENCE,MI_DESCRIPTION,MI_CREATED_AT,RE_RESTAURANT,MC_CATEGORY,MT_TYPE';
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

        if (!empty($data['MI_IMAGE_URL'])) {
            $image = $data['MI_IMAGE_URL'];
            $image_name = $image->getName();
            $directory = "assets/Uploads/restaurant/menu_category_images/";

            $response = documentUpload($image, $image_name, $user_id, $directory);

            if ($response['SUCCESS'] != 200)
                return responseJson(500, true, ['msg' => 'image not uploaded']);

            $data['MI_IMAGE_URL'] = $directory . $response['RESPONSE']['OUTPUT'];
        }

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

    public function menuCategoriesByRestaurant($restaurant_ids)
    {
        return $this->MenuCategory->whereIn('MC_RESTAURANT_ID', $restaurant_ids)->findAll();
    }

    /** ------------------------------Meal Type------------------------------ */
    public function mealTypeValidationRules($data)
    {
        $rules = [
            'MT_TYPE' => ['label' => 'meal type', 'rules' => 'required'],
        ];

        if (empty($data['id']) || !empty($data['MT_IMAGE_URL']))
            $rules['MT_IMAGE_URL'] = [
                'label' => 'meal type image',
                'rules' => ['uploaded[MT_IMAGE_URL]', 'mime_in[MT_IMAGE_URL,image/png,image/jpg,image/jpeg]', 'max_size[MT_IMAGE_URL,5048]']
            ];

        return $rules;
    }

    public function allMealType()
    {
        $mine = new ServerSideDataTable();
        $tableName = 'FLXY_MEAL_TYPES';
        $columns = 'MT_ID,MT_TYPE,MT_IMAGE_URL,MT_CREATED_AT';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }

    public function mealTypeById($id)
    {
        return $this->MealType->find($id);
    }

    public function storeMealType($user_id, $data)
    {
        $id = $data['id'];
        unset($data['id']);

        if (!empty($data['MT_IMAGE_URL'])) {
            $image = $data['MT_IMAGE_URL'];
            $image_name = $image->getName();
            $directory = "assets/Uploads/restaurant/menu_type_images/";

            $response = documentUpload($image, $image_name, $user_id, $directory);

            if ($response['SUCCESS'] != 200)
                return responseJson(500, true, ['msg' => 'image not uploaded']);

            $data['MT_IMAGE_URL'] = $directory . $response['RESPONSE']['OUTPUT'];
        }

        if (empty($id)) {
            $data['MT_CREATED_BY'] = $data['MT_UPDATED_BY'] = $user_id;
            $response = $this->MealType->insert($data);
        } else {
            $data['MT_UPDATED_BY'] = $user_id;
            $response = $this->MealType->update($id, $data);
        }

        if (!$response)
            return responseJson(500, false, ['msg' => "db insert/update not successful"]);

        if (empty($id))
            $msg = 'Meal Type has been created successflly.';
        else
            $msg = 'Meal Type has been updated successflly.';

        return responseJson(200, false, ['msg' => $msg]);
    }

    public function deleteMealType($meal_type_id)
    {
        return $this->MealType->delete($meal_type_id);
    }

    public function allMealTypes()
    {
        return $this->MealType
            ->select("FLXY_MEAL_TYPES.*, (select count(MI_ID) from FLXY_MENU_ITEMS where MI_MEAL_TYPE_ID = MT_ID) as TYPE_COUNT")
            ->findAll();
    }

    /** ------------------------------API------------------------------ */
    public function menuCategories()
    {
        return $this->MenuCategory->findAll();
    }

    public function getMenuItems($where_condition)
    {
        return $this->MenuItem->where($where_condition)->findAll();
    }

    /** ***************Place Order*************** */
    public function placeOrderValidationRules()
    {
        $rules = [
            'ITEMS' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Please add atleast one item',
                ]
            ],
            'RO_PAYMENT_METHOD' => [
                'label' => 'payment method',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Please select a payment method.'
                ]
            ]
        ];

        return $rules;
    }

    public function createUpdateRestaurantOrder($data)
    {
        return $this->RestaurantOrder->save($data);
    }

    public function createUpdateOrderDetail($data)
    {
        return $this->RestaurantOrderDetail->save($data);
    }

    public function placeOrder($user, $data)
    {
        $data['RO_TOTAL_PAYABLE'] = 0;
        $data['RO_CUSTOMER_ID'] = $user['USR_CUST_ID'];

        $items = $data['ITEMS'];
        unset($data['ITEMS']);

        foreach ($items as $index => $item) {
            $menu_item = $this->menuItemById($item['MI_ID']);

            if (empty($menu_item))
                return responseJson(202, true, ['msg' => "Item is not available."]);

            if ($menu_item['MI_IS_AVAILABLE'] == 0)
                return responseJson(202, true, ['msg' => "{$menu_item['MI_ITEM']} is not available."]);

            if (empty($item['QUANTITY']) || $item['QUANTITY'] <= 0)
                return responseJson(202, true, ['msg' => "{$menu_item['MI_ITEM']}'s quantity should be greater than zero."]);

            $items[$index]['AMOUNT'] = $item['QUANTITY'] * $menu_item['MI_PRICE'];
            $data['RO_TOTAL_PAYABLE'] += $item['QUANTITY'] * $menu_item['MI_PRICE'];
        }

        $data['RO_CREATED_BY'] = $data['RO_UPDATED_BY'] = $user['USR_ID'];
        $order_id = $this->createUpdateRestaurantOrder($data);
        if (!$order_id)
            return responseJson(202, true, ['msg' => "Unable to create order"]);

        foreach ($items as $item) {
            $item_data = [
                'ROD_ORDER_ID' => $order_id,
                'ROD_MENU_ITEM_ID' => $item['MI_ID'],
                'ROD_QUANTITY' => $item['QUANTITY'],
                'ROD_AMOUNT' => $item['AMOUNT'],
            ];

            $item_data['ROD_CREATED_BY'] = $item_data['ROD_UPDATED_BY'] = $user['USR_ID'];
            $this->createUpdateOrderDetail($item_data);
        }

        if ($data['RO_PAYMENT_METHOD'] == 'Credit/Debit card') {
            $data = [
                'amount' => $data['RO_TOTAL_PAYABLE'],
                'model' => 'FLXY_RESTAURANT_ORDERS',
                'model_id' => $order_id,
                'reservation_id' => null,
            ];
        }

        return responseJson(200, false, ['msg' => 'Order Placed successfully.'], $data);
    }

    public function restaurantOrderById($id)
    {
        return $this->RestaurantOrder->find($id);
    }

    public function orderList($user)
    {
        $customer_id = $user['USR_CUST_ID'];

        $orders = $this->RestaurantOrder->where('RO_CUSTOMER_ID', $customer_id)->findAll();
        foreach ($orders as $index => $order) {
            $orders[$index]['order_details'] = $this->RestaurantOrderDetail
                ->join('FLXY_MENU_ITEMS', 'ROD_MENU_ITEM_ID = MI_ID', 'left')
                ->where('ROD_ORDER_ID', $order['RO_ID'])->findAll();
        }

        return $orders;
    }

    public function addToCart($user, $data)
    {
        
    }
}
