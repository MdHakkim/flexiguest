<?php

namespace App\Controllers\Repositories;

use App\Controllers\BaseController;
use App\Models\RestaurantTable;
use CodeIgniter\API\ResponseTrait;

class RestaurantTableRepository extends BaseController
{
    use ResponseTrait;

    private $RestaurantTable;

    public function __construct()
    {
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
