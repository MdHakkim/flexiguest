<?php

namespace App\Controllers;

use App\Controllers\Repositories\RestaurantRepository;
use App\Controllers\Repositories\RestaurantTableRepository;
use App\Libraries\ServerSideDataTable;
use CodeIgniter\API\ResponseTrait;

class RestaurantTableController extends BaseController
{

    use ResponseTrait;

    private $RestaurantRepository;
    private $RestaurantTableRepository;

    public function __construct()
    {
        $this->RestaurantRepository = new RestaurantRepository();
        $this->RestaurantTableRepository = new RestaurantTableRepository();
    }

    public function table()
    {
        $data['title'] = getMethodName();
        $data['session'] = session();
        $data['restaurants'] = $this->RestaurantRepository->allRestaurants();

        return view('frontend/restaurant/table', $data);
    }

    public function allTables()
    {
        $mine = new ServerSideDataTable();
        $tableName = 'FLXY_RESTAURANT_TABLES left join FLXY_RESTAURANTS on RT_RESTAURANT_ID = RE_ID';
        $columns = 'RT_ID,RT_RESTAURANT_ID,RT_TABLE_NO,RT_NO_OF_SEATS,RT_CREATED_AT,RE_RESTAURANT';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }

    public function store()
    {
        $user = session('user');

        $data = $this->request->getPost();

        if (!$this->validate($this->RestaurantTableRepository->validationRules($data)))
            return $this->respond(responseJson(403, true, $this->validator->getErrors()));

        $result = $this->RestaurantTableRepository->storeRestaurantTable($user, $data);
        return $this->respond($result);
    }

    public function edit()
    {
        $id = $this->request->getPost('id');
        $restaurant_table = $this->RestaurantTableRepository->restaurantTableById($id);

        if ($restaurant_table)
            return $this->respond($restaurant_table);

        return $this->respond(responseJson(404, true, ['msg' => "Restaurant table not found"]));
    }

    public function delete()
    {
        $restaurant_table_id = $this->request->getPost('id');

        $result = $this->RestaurantTableRepository->deleteRestaurantTable($restaurant_table_id);

        $result = $result
            ? responseJson(200, false, ['msg' => "Restaurant table deleted successfully."])
            : responseJson(500, true, ['msg' => "Restaurant table not deleted"]);

        return $this->respond($result);
    }
}
