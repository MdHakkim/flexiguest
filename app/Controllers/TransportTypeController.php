<?php

namespace App\Controllers;

use App\Libraries\ServerSideDataTable;
use CodeIgniter\API\ResponseTrait;
use App\Models\TransportType;

class TransportTypeController extends BaseController
{

    use ResponseTrait;

    private $TransportType;

    public function __construct()
    {
        $this->TransportType = new TransportType();
    }

    public function transportType()
    {
        $data['title'] = getMethodName();
        $data['session'] = session();

        return view('frontend/transport/transport_type', $data);
    }

    public function allTransportTypes()
    {
        $mine = new ServerSideDataTable();
        $tableName = 'FLXY_TRANSPORT_TYPES';
        $columns = 'TT_ID,TT_TRANSPORT_CODE,TT_LABEL,TT_DESCRIPTION,TT_PHONE,TT_DISTANCE,TT_DISTANCE_UNIT,TT_MIN_PRICE,TT_MAX_PRICE,TT_COMMENTS,TT_DISPLAY_SEQUENCE,TT_CREATED_AT';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }

    public function store()
    {
        $user_id = session('USR_ID');

        $id = $this->request->getPost('id');

        $rules = [
            'TT_TRANSPORT_CODE' => ['label' => 'Transport code', 'rules' => 'required'],
            'TT_LABEL' => ['label' => 'Label', 'rules' => 'required'],
            'TT_DESCRIPTION' => ['label' => 'Description', 'rules' => 'required'],
            'TT_PHONE' => ['label' => 'Phone', 'rules' => 'required'],
            'TT_DISTANCE' => ['label' => 'Distance', 'rules' => 'required'],
            'TT_DISTANCE_UNIT' => ['label' => 'Distance unit', 'rules' => 'required'],
            'TT_MIN_PRICE' => ['label' => 'Min price', 'rules' => 'required'],
            'TT_MAX_PRICE' => ['label' => 'Max price', 'rules' => 'required'],
        ];

        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();
            return $this->respond(responseJson("403", true, $errors));
        }

        $data = $this->request->getPost();

        if(!empty($id)){
            $data['TT_UPDATED_BY'] = $user_id;
            $response = $this->TransportType->update($id, $data);
            $msg = "Transport updated successfully";
        }
        else{
            $data['TT_UPDATED_BY'] = $data['TT_CREATED_BY'] = $user_id;
            $response = $this->TransportType->insert($data);
            $msg = "Transport added successfully";
        }

        $result = $response
            ? responseJson(200, false, ['msg' => $msg], $response = '')
            : responseJson(500, true, ['msg' => 'db insert/update not successful']);

        return $this->respond($result);
    }

    public function edit()
    {
        $id = $this->request->getPost('id');

        $transport = $this->TransportType->where('TT_ID', $id)->first();

        if ($transport)
            return $this->respond($transport);

        return $this->respond(responseJson(404, true, ['msg' => "Transport not found"]));
    }

    public function delete()
    {
        $id = $this->request->getPost('id');

        $return = $this->TransportType->delete($id);
        $result = $return
            ? responseJson(200, false, ['msg' => 'Transport deleted successfully'], $return)
            : responseJson(500, true, "record not deleted");

        return $this->respond($result);
    }
}
