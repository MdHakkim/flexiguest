<?php

namespace App\Controllers;

use App\Libraries\ServerSideDataTable;
use CodeIgniter\API\ResponseTrait;
use App\Models\Transport;

class TransportController extends BaseController
{

    use ResponseTrait;

    private $Transport;

    public function __construct()
    {
        $this->Transport = new Transport();
    }

    public function transport()
    {
        $data['title'] = getMethodName();
        $data['session'] = session();

        return view('frontend/transport', $data);
    }

    public function allTransports()
    {
        $mine = new ServerSideDataTable();
        $tableName = 'FLXY_TRANSPORT';
        $columns = 'TR_ID,TR_TRANSPORT_CODE,TR_LABEL,TR_DESCRIPTION,TR_PHONE,TR_DISTANCE,TR_DISTANCE_UNIT,TR_MIN_PRICE,TR_MAX_PRICE,TR_COMMENTS,TR_DISPLAY_SEQUENCE,TR_CREATED_AT';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }

    public function store()
    {
        $user_id = session('USR_ID');

        $id = $this->request->getPost('id');

        $rules = [
            'TR_TRANSPORT_CODE' => ['label' => 'Transport code', 'rules' => 'required'],
            'TR_LABEL' => ['label' => 'Label', 'rules' => 'required'],
            'TR_DESCRIPTION' => ['label' => 'Description', 'rules' => 'required'],
            'TR_PHONE' => ['label' => 'Phone', 'rules' => 'required'],
            'TR_DISTANCE' => ['label' => 'Distance', 'rules' => 'required'],
            'TR_DISTANCE_UNIT' => ['label' => 'Distance unit', 'rules' => 'required'],
            'TR_MIN_PRICE' => ['label' => 'Min price', 'rules' => 'required'],
            'TR_MAX_PRICE' => ['label' => 'Max price', 'rules' => 'required'],
        ];

        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();
            return $this->respond(responseJson("403", true, $errors));
        }

        $data = $this->request->getPost();

        if(!empty($id)){
            $data['TR_UPDATED_BY'] = $user_id;
            $response = $this->Transport->update($id, $data);
            $msg = "Transport updated successfully";
        }
        else{
            $data['TR_UPDATED_BY'] = $data['TR_CREATED_BY'] = $user_id;
            $response = $this->Transport->insert($data);
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

        $transport = $this->Transport->where('TR_ID', $id)->first();

        if ($transport)
            return $this->respond($transport);

        return $this->respond(responseJson(404, true, ['msg' => "Transport not found"]));
    }

    public function delete()
    {
        $id = $this->request->getPost('id');

        $return = $this->Transport->delete($id);
        $result = $return
            ? responseJson(200, false, ['msg' => 'Transport deleted successfully'], $return)
            : responseJson(500, true, "record not deleted");

        return $this->respond($result);
    }
}
