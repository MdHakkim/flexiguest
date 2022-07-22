<?php

namespace App\Controllers;

use App\Libraries\ServerSideDataTable;
use App\Models\DropoffPoint;
use CodeIgniter\API\ResponseTrait;

class DropoffPointController extends BaseController
{

    use ResponseTrait;

    private $DropoffPoint;

    public function __construct()
    {
        $this->DropoffPoint = new DropoffPoint();
    }

    public function dropoffPoint()
    {
        $data['title'] = getMethodName();
        $data['session'] = session();

        return view('frontend/transport/dropoff_point', $data);
    }

    public function allDropoffPoints()
    {
        $mine = new ServerSideDataTable();
        $tableName = 'FLXY_DROPOFF_POINTS';
        $columns = 'DP_ID,DP_POINT,DP_SEQUENCE,DP_CREATED_AT';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }

    public function store()
    {
        $user_id = session('USR_ID');

        $id = $this->request->getPost('id');

        $rules = [
            'DP_POINT' => ['label' => 'point name', 'rules' => 'required'],
        ];

        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();
            return $this->respond(responseJson("403", true, $errors));
        }

        $data = $this->request->getPost();

        if(!empty($id)){
            $data['DP_UPDATED_BY'] = $user_id;
            $response = $this->DropoffPoint->update($id, $data);
            $msg = "Dropoff point updated successfully";
        }
        else{
            $data['DP_UPDATED_BY'] = $data['DP_CREATED_BY'] = $user_id;
            $response = $this->DropoffPoint->insert($data);
            $msg = "Dropoff Point added successfully";
        }

        $result = $response
            ? responseJson(200, false, ['msg' => $msg], $response = '')
            : responseJson(500, true, ['msg' => 'db insert/update not successful']);

        return $this->respond($result);
    }

    public function edit()
    {
        $id = $this->request->getPost('id');

        $dropoff_point = $this->DropoffPoint->where('DP_ID', $id)->first();

        if ($dropoff_point)
            return $this->respond($dropoff_point);

        return $this->respond(responseJson(404, true, ['msg' => "Transport not found"]));
    }

    public function delete()
    {
        $id = $this->request->getPost('id');

        $return = $this->DropoffPoint->delete($id);
        $result = $return
            ? responseJson(200, false, ['msg' => 'Dropoff Point deleted successfully'], $return)
            : responseJson(500, true, "record not deleted");

        return $this->respond($result);
    }
}