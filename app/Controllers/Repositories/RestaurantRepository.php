<?php

namespace App\Controllers\Repositories;

use App\Controllers\BaseController;
use App\Libraries\ServerSideDataTable;
use App\Models\MealType;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Restaurant;
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

    public function __construct()
    {
        $this->Restaurant = new Restaurant();
        $this->MenuCategory = new MenuCategory();
        $this->MenuItem = new MenuItem();
        $this->MealType = new MealType();
        $this->RestaurantOrder = new RestaurantOrder();
        $this->RestaurantOrderDetail = new RestaurantOrderDetail();
    }

    /** ------------------------------Restaurant------------------------------ */
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

    /** ------------------------------Menu Category------------------------------ */
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

    public function menuCategoriesByRestaurant($restaurant_ids)
    {
        return $this->MenuCategory->whereIn('MC_RESTAURANT_ID', $restaurant_ids)->findAll();
    }

    public function menuCategories()
    {
        return $this->MenuCategory->findAll();
    }

    /** ------------------------------Menu Item------------------------------ */
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

    public function getMenuItems($where_condition)
    {
        return $this->MenuItem->where($where_condition)->findAll();
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

    /** ***************Place Order*************** */
    public function placeOrderValidationRules()
    {
        $rules = [
            'RO_RESERVATION_ID' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Please select a reservation'
                ]
            ],
            'RO_ROOM_ID' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Please select a reservation'
                ]
            ],
            'RO_ITEMS' => [
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

        if (isWeb()) {
            $rules['RO_CUSTOMER_ID'] = [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Please select a reservation'
                ]
            ];
        }

        return $rules;
    }

    public function createUpdateRestaurantOrder($data)
    {
        if (empty($data['RO_ID']))
            return $this->RestaurantOrder->insert($data);

        $this->RestaurantOrder->save($data);
        return $data['RO_ID'];
    }

    public function createUpdateOrderDetail($data)
    {
        return $this->RestaurantOrderDetail->save($data);
    }

    public function deleteOrderDetails($where_condition)
    {
        return $this->RestaurantOrderDetail->where($where_condition)->delete();
    }

    public function placeOrder($user, $data)
    {
        $data['RO_TOTAL_PAYABLE'] = 0;
        $data['RO_CUSTOMER_ID'] = $data['RO_CUSTOMER_ID'] ?? $user['USR_CUST_ID'];

        $items = $data['RO_ITEMS'];
        unset($data['RO_ITEMS']);

        foreach ($items as $index => $item) {
            $menu_item = $this->menuItemById($item['MI_ID']);

            if (empty($menu_item))
                return responseJson(202, true, ['msg' => "Item is not available."]);

            if ($menu_item['MI_IS_AVAILABLE'] == 0)
                return responseJson(202, true, ['msg' => "{$menu_item['MI_ITEM']} is not available."]);

            if (empty($item['MI_QUANTITY']) || $item['MI_QUANTITY'] <= 0)
                return responseJson(202, true, ['msg' => "{$menu_item['MI_ITEM']}'s quantity should be greater than zero."]);

            $items[$index]['AMOUNT'] = $item['MI_QUANTITY'] * $menu_item['MI_PRICE'];
            $data['RO_TOTAL_PAYABLE'] += $item['MI_QUANTITY'] * $menu_item['MI_PRICE'];
        }

        $data['RO_CREATED_BY'] = $data['RO_UPDATED_BY'] = $user['USR_ID'];
        $order_id = $this->createUpdateRestaurantOrder($data);
        if (!$order_id)
            return responseJson(202, true, ['msg' => "Unable to create order"]);

        if (!empty($data['RO_ID']))
            $this->deleteOrderDetails("ROD_ORDER_ID = {$data['RO_ID']}");

        foreach ($items as $item) {
            $item_data = [
                'ROD_ORDER_ID' => $order_id,
                'ROD_MENU_ITEM_ID' => $item['MI_ID'],
                'ROD_QUANTITY' => $item['MI_QUANTITY'],
                'ROD_AMOUNT' => $item['AMOUNT'],
            ];

            $item_data['ROD_CREATED_BY'] = $item_data['ROD_UPDATED_BY'] = $user['USR_ID'];
            $this->createUpdateOrderDetail($item_data);
        }

        if (empty($data['RO_ID']))
            $this->generateOrderInvoice($order_id);


        if (!isWeb() && empty($data['RO_ID']) && $data['RO_PAYMENT_METHOD'] == 'Credit/Debit card') {
            $data = [
                'amount' => $data['RO_TOTAL_PAYABLE'],
                'model' => 'FLXY_RESTAURANT_ORDERS',
                'model_id' => $order_id,
                'reservation_id' => $data['RO_RESERVATION_ID'],
            ];
        }

        return responseJson(200, false, ['msg' => 'Order Placed successfully.'], $data);
    }

    public function restaurantOrderById($id, $with_details = false)
    {
        $order = $this->RestaurantOrder
            ->select('*, 
                USR_DEPARTMENT as RO_DEPARTMENT_ID, 
                co.cname as COUNTRY_NAME,
                st.sname as STATE_NAME,
                ci.ctname as CITY_NAME')
            ->join('FLXY_RESERVATION', 'RO_RESERVATION_ID = RESV_ID', 'left')
            ->join('FLXY_CUSTOMER', 'RO_CUSTOMER_ID = CUST_ID', 'left')
            ->join('FlXY_USERS', 'RO_ATTENDANT_ID = USR_ID', 'left')
            ->join('FLXY_ROOM', 'RESV_ROOM = RM_NO', 'left')
            ->join('COUNTRY as co', 'CUST_COUNTRY = co.iso2', 'left')
            ->join('STATE as st', 'CUST_STATE = st.state_code', 'left')
            ->join('CITY as ci', 'CUST_CITY = ci.id', 'left')
            ->find($id);

        if (empty($order))
            return null;

        if ($with_details) {
            $order['restaurant_ids'] = [];
            $order['category_ids'] = [];
            $order['meal_type_ids'] = [];
            $order['selected_item_ids'] = [];
            $order['selected_items'] = [];

            $order['order_details'] = $this->RestaurantOrderDetail
                ->join('FLXY_MENU_ITEMS', 'ROD_MENU_ITEM_ID = MI_ID', 'left')
                ->where('ROD_ORDER_ID', $id)
                ->findAll();

            foreach ($order['order_details'] as $detail) {
                if (!in_array($detail['MI_RESTAURANT_ID'], $order['restaurant_ids']))
                    $order['restaurant_ids'][] = $detail['MI_RESTAURANT_ID'];

                if (!in_array($detail['MI_MENU_CATEGORY_ID'], $order['category_ids']))
                    $order['category_ids'][] = $detail['MI_MENU_CATEGORY_ID'];

                if (!in_array($detail['MI_MEAL_TYPE_ID'], $order['meal_type_ids']))
                    $order['meal_type_ids'][] = $detail['MI_MEAL_TYPE_ID'];

                $order['selected_item_ids'][] = strval($detail['ROD_MENU_ITEM_ID']);

                $order['selected_items'][] = [
                    'id' => strval($detail['ROD_MENU_ITEM_ID']),
                    'item' => $detail['MI_ITEM'],
                    'price' => $detail['MI_PRICE'],
                    'quantity' => $detail['ROD_QUANTITY']
                ];
            }
        }

        return $order;
    }

    public function orderList($user)
    {
        $where_condition = "1 = 1";
        if ($user['USR_ROLE_ID'] == "2")
            $where_condition = "RO_CUSTOMER_ID = {$user['USR_CUST_ID']}";

        else if ($user['USR_ROLE_ID'] == "3")
            $where_condition = "RO_ATTENDANT_ID = {$user['USR_ID']}";

        $orders = $this->RestaurantOrder
            ->select("FLXY_RESTAURANT_ORDERS.*, concat(USR_FIRST_NAME, ' ', USR_LAST_NAME) as RO_ATTENDANT_NAME, RM_NO, concat(CUST_FIRST_NAME, ' ', CUST_LAST_NAME) as GUEST_NAME")
            ->join('FlXY_USERS', 'RO_ATTENDANT_ID = USR_ID', 'left')
            ->join('FLXY_ROOM', 'RO_ROOM_ID = RM_ID', 'left')
            ->join('FLXY_CUSTOMER', 'RO_CUSTOMER_ID = CUST_ID', 'left')
            ->where($where_condition)
            ->orderBy('RO_ID', 'desc')
            ->findAll();
        foreach ($orders as $index => $order) {
            $orders[$index]['order_details'] = $this->RestaurantOrderDetail
                ->join('FLXY_MENU_ITEMS', 'ROD_MENU_ITEM_ID = MI_ID', 'left')
                ->join('FLXY_RESTAURANTS', 'MI_RESTAURANT_ID = RE_ID', 'left')
                ->where('ROD_ORDER_ID', $order['RO_ID'])
                ->findAll();
        }

        return $orders;
    }

    public function getOrdersList($where_condition)
    {
        return $this->RestaurantOrder->where($where_condition)->findAll();
    }

    public function allOrder()
    {
        $mine = new ServerSideDataTable();
        $tableName = 'FLXY_RESTAURANT_ORDERS left join FLXY_ROOM on RO_ROOM_ID = RM_ID left join FLXY_CUSTOMER on RO_CUSTOMER_ID = CUST_ID';
        $columns = 'RO_ID,RO_RESERVATION_ID,RO_ROOM_ID,RO_CUSTOMER_ID,RO_TOTAL_PAYABLE,RO_DELIVERY_STATUS,RO_PAYMENT_STATUS,RO_PAYMENT_METHOD,RO_CREATED_AT,RM_NO,CUST_FIRST_NAME,CUST_LAST_NAME';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }

    public function deleteOrder($order_id)
    {
        $this->RestaurantOrderDetail->where('ROD_ORDER_ID', $order_id)->delete();
        return $this->RestaurantOrder->delete($order_id);
    }

    public function generateOrderInvoice($order_id, $transaction_id = null)
    {
        $order = $this->restaurantOrderById($order_id, true);
        if (empty($order))
            return null;

        $order['transaction_id'] = $transaction_id;

        $view = 'Templates/restaurant_order_invoice_template';
        if (empty($transaction_id))
            $file_name = "assets/invoices/restaurant-order-invoices/RO{$order_id}-Invoice.pdf";
        else
            $file_name = "assets/receipts/restaurant-order-receipts/RO{$order_id}-Receipt.pdf";

        generateInvoice($file_name, $view, ['data' => $order]);
        return $file_name;
    }
}
