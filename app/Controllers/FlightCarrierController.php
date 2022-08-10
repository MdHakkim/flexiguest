<?php

namespace App\Controllers;

use App\Libraries\ServerSideDataTable;
use App\Models\FlightCarrier;
use CodeIgniter\API\ResponseTrait;

class FlightCarrierController extends BaseController
{

    use ResponseTrait;

    private $FlightCarrier;

    public function __construct()
    {
        $this->FlightCarrier = new FlightCarrier();
    }

    public function flightCarrier()
    {
        $data['title'] = getMethodName();
        $data['session'] = session();

        return view('frontend/transport/flight_carrier', $data);
    }

    public function allFlightCarriers()
    {
        $mine = new ServerSideDataTable();
        $tableName = 'FLXY_FLIGHT_CARRIERS';
        $columns = 'FC_ID,FC_FLIGHT_CARRIER,FC_FLIGHT_CODE,FC_SEQUENCE,FC_CREATED_AT';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }

    public function store()
    {
        $user_id = session('USR_ID');

        $id = $this->request->getPost('id');

        $rules = [
            'FC_FLIGHT_CARRIER' => ['label' => 'flight carrier name', 'rules' => 'required'],
            'FC_FLIGHT_CODE' => ['label' => 'flight carrier code', 'rules' => 'required'],
        ];

        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();
            return $this->respond(responseJson("403", true, $errors));
        }

        $data = $this->request->getPost();

        if(!empty($id)){
            $data['FC_UPDATED_BY'] = $user_id;
            $response = $this->FlightCarrier->update($id, $data);
            $msg = "Flight Carrier updated successfully";
        }
        else{
            $data['FC_UPDATED_BY'] = $data['FC_CREATED_BY'] = $user_id;
            $response = $this->FlightCarrier->insert($data);
            $msg = "Flight Carrier added successfully";
        }

        $result = $response
            ? responseJson(200, false, ['msg' => $msg], $response = '')
            : responseJson(500, true, ['msg' => 'db insert/update not successful']);

        return $this->respond($result);
    }

    public function edit()
    {
        $id = $this->request->getPost('id');

        $flight_carrier = $this->FlightCarrier->where('FC_ID', $id)->first();

        if ($flight_carrier)
            return $this->respond($flight_carrier);

        return $this->respond(responseJson(404, true, ['msg' => "Flight Carrier not found"]));
    }

    public function delete()
    {
        $id = $this->request->getPost('id');

        $return = $this->FlightCarrier->delete($id);
        $result = $return
            ? responseJson(200, false, ['msg' => 'Flight Carrier deleted successfully'], $return)
            : responseJson(500, true, "record not deleted");

        return $this->respond($result);
    }
}