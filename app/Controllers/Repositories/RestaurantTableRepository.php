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
use App\Models\RestaurantTable;
use CodeIgniter\API\ResponseTrait;

class RestaurantTableRepository extends BaseController
{
    use ResponseTrait;

    private $Restaurant;
    private $MenuCategory;
    private $MenuItem;
    private $MealType;
    private $RestaurantOrder;
    private $RestaurantOrderDetail;

    private $RestaurantTable;

    public function __construct()
    {
        $this->Restaurant = new Restaurant();
        $this->MenuCategory = new MenuCategory();
        $this->MenuItem = new MenuItem();
        $this->MealType = new MealType();
        $this->RestaurantOrder = new RestaurantOrder();
        $this->RestaurantOrderDetail = new RestaurantOrderDetail();

        $this->RestaurantTable = new RestaurantTable();
    }

    public function validationRules($data)
    {
        $rules = [
            'RT_RESTAURANT_ID' => ['label' => 'restaurant', 'rules' => 'required', 'errors' => ['required' => 'please select a restaurant.']],
            'RT_TABLE_NO' => ['label' => 'table no', 'rules' => 'required'],
            'RT_NO_OF_SEATS' => ['label' => 'No of Seats', 'rules' => 'required'],
        ];

        return $rules;
    }

    public function storeRestaurantTable($user, $data)
    {
        if (empty($data['RT_ID'])) {
            unset($data['RT_ID']);
            $data['RT_CREATED_BY'] = $data['RT_UPDATED_BY'] = $user['USR_ID'];
        } else
            $data['RT_UPDATED_BY'] = $user['USR_ID'];

        $response = $this->RestaurantTable->save($data);

        if (!$response)
            return responseJson(500, false, ['msg' => "db insert/update not successful"]);

        return responseJson(200, false, ['msg' => 'Restaurant table create/updated successfully.']);
    }

    public function restaurantTableById($id)
    {
        return $this->RestaurantTable->find($id);
    }

    public function deleteRestaurantTable($id)
    {
        return $this->RestaurantTable->delete($id);
    }
}
