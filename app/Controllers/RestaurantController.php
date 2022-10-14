<?php

namespace App\Controllers;

use App\Controllers\Repositories\DepartmentRepository;
use App\Controllers\Repositories\PaymentRepository;
use App\Controllers\Repositories\ReservationRepository;
use App\Controllers\Repositories\RestaurantRepository;
use App\Controllers\Repositories\UserRepository;
use CodeIgniter\API\ResponseTrait;

class RestaurantController extends BaseController
{

    use ResponseTrait;

    private $ReservationRepository;
    private $RestaurantRepository;
    private $PaymentRepository;
    private $UserRepository;
    private $DepartmentRepository;

    public function __construct()
    {
        $this->ReservationRepository = new ReservationRepository();
        $this->RestaurantRepository = new RestaurantRepository();
        $this->PaymentRepository = new PaymentRepository();
        $this->UserRepository = new UserRepository();
        $this->DepartmentRepository = new DepartmentRepository();
    }

    /** ------------------------------Restaurant------------------------------ */
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

        if ($this->request->getFile('RE_IMAGE_URL'))
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

    public function allRestaurants()
    {
        $result = $this->RestaurantRepository->allRestaurants();

        return $this->respond(responseJson(200, false, ['msg' => 'restaurants'], $result));
    }

    /** ------------------------------Menu Category------------------------------ */
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
        if ($this->request->getFile('MC_IMAGE_URL'))
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

    public function menuCategoriesByRestaurant()
    {
        $restaurant_ids = $this->request->getVar('restaurant_ids');
        $result = $this->RestaurantRepository->menuCategoriesByRestaurant($restaurant_ids);

        return $this->respond(responseJson(200, false, ['msg' => 'list'], $result));
    }

    public function menuCategories()
    {
        $result = $this->RestaurantRepository->menuCategories();

        return $this->respond(responseJson(200, false, ['msg' => 'categories'], $result));
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
        if ($this->request->getFile('MT_IMAGE_URL'))
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
        if ($this->request->getFile('MI_IMAGE_URL'))
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

    public function getMenuItems()
    {
        $data = json_decode(json_encode($this->request->getVar()), true);

        $where_condition = "1 = 1";
        if (!empty($data['item_ids'])) {
            $ids = implode(",", $data['item_ids']);
            $where_condition .= " AND MI_ID in ($ids)";
        }

        if (!empty($data['category_ids'])) {
            $ids = implode(",", $data['category_ids']);
            $where_condition .= " AND MI_MENU_CATEGORY_ID in ($ids)";
        }

        if (!empty($data['meal_type_ids'])) {
            $ids = implode(",", $data['meal_type_ids']);
            $where_condition .= " AND MI_MEAL_TYPE_ID in ($ids)";
        }

        if (!empty($data['item'])) {
            $where_condition .= " AND MI_ITEM like '%{$data['item']}%'";
        }

        $result = $this->RestaurantRepository->getMenuItems($where_condition);

        return $this->respond(responseJson(200, false, ['msg' => 'item list'], $result));
    }

    /** ------------------------------Order------------------------------ */
    public function order()
    {
        $data['title'] = getMethodName();
        $data['session'] = session();

        $where_condition = "RESV_STATUS = 'Checked-In'";
        $data['reservations'] = $this->ReservationRepository->allReservations($where_condition);
        $data['restaurants'] = $this->RestaurantRepository->allRestaurants();
        $data['meal_types'] = $this->RestaurantRepository->allMealTypes();
        $data['departments'] = $this->DepartmentRepository->allDepartments();

        return view('frontend/restaurant/order', $data);
    }

    public function allOrder()
    {
        $this->RestaurantRepository->allOrder();
    }

    public function placeOrder()
    {
        $user = $this->request->user ?? session('user');

        $data = json_decode(json_encode($this->request->getVar()), true);

        if (!$this->validate($this->RestaurantRepository->placeOrderValidationRules($data)))
            return $this->respond(responseJson(403, true, $this->validator->getErrors()));

        $result = $this->RestaurantRepository->placeOrder($user, $data);
        if (!isWeb() && empty($data['RO_ID']) && $result['SUCCESS'] == 200 && $data['RO_PAYMENT_METHOD'] == 'Credit/Debit card') {
            $data = $result['RESPONSE']['OUTPUT'];
            $result = $this->PaymentRepository->createPaymentIntent($user, $data);
        }

        return $this->respond($result);
    }

    public function orderList()
    {
        $user = $this->request->user;

        $result = $this->RestaurantRepository->orderList($user);
        return $this->respond(responseJson(200, false, ['msg' => 'order list'], $result));
    }

    public function editOrder()
    {
        $id = $this->request->getPost('id');

        $restaurant_order = $this->RestaurantRepository->restaurantOrderById($id, true);

        if ($restaurant_order)
            return $this->respond(responseJson(200, false, ['msg' => 'order'], $restaurant_order));

        return $this->respond(responseJson(404, true, ['msg' => "Order not found"]));
    }

    public function deleteOrder()
    {
        $order_id = $this->request->getPost('id');

        $result = $this->RestaurantRepository->deleteOrder($order_id);

        $result = $result
            ? responseJson(200, false, ['msg' => "Order deleted successfully."])
            : responseJson(500, true, ['msg' => "Order not deleted"]);

        return $this->respond($result);
    }

    public function updateRestaurantOrderStatus()
    {
        $user = $this->request->user;
        $data = json_decode(json_encode($this->request->getVar()), true);

        $data = [
            'RO_ID' => $data['RO_ID'],
            'RO_ATTENDANT_ID' => $data['RO_ATTENDANT_ID'] ?? null,
            'RO_DELIVERY_STATUS' => $data['RO_DELIVERY_STATUS'],
        ];

        //  validate order
        $order = $this->RestaurantRepository->restaurantOrderById($data['RO_ID']);
        if (empty($order))
            return $this->respond(responseJson(404, true, ['msg' => "Order not found"]));

        //  for guest
        if ($user['USR_ROLE'] == 'GUEST') {
            unset($data['RO_ATTENDANT_ID']);

            if ($data['RO_DELIVERY_STATUS'] != 'Cancelled')
                return $this->respond(responseJson(202, true, ['msg' => "Invalid request."]));

            if ($order['RO_DELIVERY_STATUS'] != 'New' || $order['RO_DELIVERY_STATUS'] != 'Cancelled' || $order['RO_PAYMENT_STATUS'] == 'Paid')
                return $this->respond(responseJson(202, true, ['msg' => "Can't cancel the order."]));
        }

        // validate attendant id
        if (!empty($data['RO_ATTENDANT_ID'])) {
            $attendee = $this->UserRepository->userById($data['RO_ATTENDANT_ID']);
            if (empty($attendee))
                return $this->respond(responseJson(404, true, ['msg' => "Attendee not found"]));
        } else {
            unset($data['RO_ATTENDANT_ID']);
        }

        if ($order['RO_DELIVERY_STATUS'] == 'New' && $data['RO_DELIVERY_STATUS'] == 'Delivered')
            return $this->respond(responseJson(202, true, ['msg' => "First assign an attendee to the order."]));

        $result = $this->RestaurantRepository->createUpdateRestaurantOrder($data);
        if (!$result)
            return $this->respond(responseJson(500, true, ['msg' => "Unable to assign"]));

        return $this->respond(responseJson(200, true, ['msg' => "Order updated successfully."]));
    }

    /** ------------------------------Main Screen------------------------------ */
    public function mainScreen()
    {
        $data['meal_types'] = $this->RestaurantRepository->allMealTypes();
        $data['menu_categories'] = $this->RestaurantRepository->menuCategories();
        $data['restaurants'] = $this->RestaurantRepository->allRestaurants();

        foreach ($data['restaurants'] as $index => $restaurant) {
            $data['restaurants'][$index]['menu_items'] = $this->RestaurantRepository->getMenuItems("MI_RESTAURANT_ID = {$restaurant['RE_ID']}");
        }

        return $this->respond(responseJson(200, false, ['msg' => 'main screen'], $data));
    }

    /** ------------------------------Cart------------------------------ */
    public function addToCart()
    {
        $user = $this->request->user;

        $data = json_decode(json_encode($this->request->getVar()), true);

        $result = $this->RestaurantRepository->addToCart($user, $data);
        return $this->respond($result);
    }
}
