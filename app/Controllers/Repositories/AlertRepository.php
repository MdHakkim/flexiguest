<?php

namespace App\Controllers\Repositories;

use App\Controllers\BaseController;
use App\Libraries\DataTables\AlertDataTable;
use App\Models\Alert;
use CodeIgniter\API\ResponseTrait;

class AlertRepository extends BaseController
{
    use ResponseTrait;

    private $Alert;

    public function __construct()
    {
        $this->Alert = new Alert();
    }

    public function validationRules()
    {
        return [
            'AL_DEPARTMENT_IDS' => [
                'label' => 'departments',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Please select a department'
                ]
            ],
            'AL_USER_IDS' => [
                'label' => 'users',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Please select a user'
                ]
            ],
            'AL_MESSAGE' => ['label' => 'message', 'rules' => 'required'],
        ];
    }

    public function allAlerts()
    {
        $mine = new AlertDataTable();
        $tableName = 'FLXY_ALERTS';
        $columns = 'AL_ID,AL_DEPARTMENT_IDS,AL_USER_IDS,AL_MESSAGE,AL_CREATED_AT';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }

    public function alertById($id)
    {
        return $this->Alert->find($id);
    }

    public function storeAlert($user_id, $data)
    {
        $id = $data['id'];
        unset($data['id']);

        $data['AL_DEPARTMENT_IDS'] = json_encode($data['AL_DEPARTMENT_IDS']);
        $data['AL_USER_IDS'] = json_encode($data['AL_USER_IDS']);

        if (empty($id)) {
            $data['AL_CREATED_BY'] = $data['AL_UPDATED_BY'] = $user_id;
            $response = $this->Alert->insert($data);
        } else {
            $data['AL_UPDATED_BY'] = $user_id;
            $response = $this->Alert->update($id, $data);
        }

        if (!$response)
            return responseJson(500, false, ['msg' => "db insert/update not successful"]);

        if (empty($id))
            $msg = 'Alert has been created successflly.';
        else
            $msg = 'Alert has been updated successflly.';

        return responseJson(200, false, ['msg' => $msg]);
    }

    public function deleteAlert($alert_id)
    {
        return $this->Alert->delete($alert_id);
    }
}
