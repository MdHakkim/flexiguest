<?php

namespace App\Controllers;

use App\Libraries\ServerSideDataTable;
use CodeIgniter\API\ResponseTrait;
use App\Models\PickupPoint;

class PickupPointController extends BaseController
{

    use ResponseTrait;

    private $PickupPoint;

    public function __construct()
    {
        $this->PickupPoint = new PickupPoint();
    }

    public function pickupPoint()
    {
        $data['title'] = getMethodName();
        $data['session'] = session();

        return view('frontend/transport/pickup_point', $data);
    }

    public function allPickupPoints()
    {
        $mine = new ServerSideDataTable();
        $tableName = 'FLXY_PICKUP_POINTS';
        $columns = 'PP_ID,PP_POINT,PP_SEQUENCE,PP_CREATED_AT';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }

    public function store()
    {
        $user_id = session('USR_ID');

        $id = $this->request->getPost('id');

        $rules = [
            'PP_POINT' => ['label' => 'point name', 'rules' => 'required'],
        ];

        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();
            return $this->respond(responseJson("403", true, $errors));
        }

        $data = $this->request->getPost();

        if(!empty($id)){
            $data['PP_UPDATED_BY'] = $user_id;
            $response = $this->PickupPoint->update($id, $data);
            $msg = "Pickup point updated successfully";
        }
        else{
            $data['PP_UPDATED_BY'] = $data['PP_CREATED_BY'] = $user_id;
            $response = $this->PickupPoint->insert($data);
            $msg = "Pickup Point added successfully";
        }

        $result = $response
            ? responseJson(200, false, ['msg' => $msg], $response = '')
            : responseJson(500, true, ['msg' => 'db insert/update not successful']);

        return $this->respond($result);
    }

    public function edit()
    {
        $id = $this->request->getPost('id');

        $pickup_point = $this->PickupPoint->where('PP_ID', $id)->first();

        if ($pickup_point)
            return $this->respond($pickup_point);

        return $this->respond(responseJson(404, true, ['msg' => "Transport not found"]));
    }

    public function delete()
    {
        $id = $this->request->getPost('id');

        $return = $this->PickupPoint->delete($id);
        $result = $return
            ? responseJson(200, false, ['msg' => 'Pickup Point deleted successfully'], $return)
            : responseJson(500, true, "record not deleted");

        return $this->respond($result);
    }
}