<?php

namespace App\Controllers\Repositories;

use App\Controllers\BaseController;
use App\Libraries\ServerSideDataTable;
use App\Models\TaskAssignment;
use CodeIgniter\API\ResponseTrait;

class TaskAssignmentRepository extends BaseController
{
    use ResponseTrait;

    private $TaskAssignment;

    public function __construct()
    {
        $this->TaskAssignment = new TaskAssignment();
    }

    /** ------------------------------Task Assignment------------------------------ */
    public function taskAssignmentValidationRules($data)
    {
        $rules = [
            'HKTAO_TASK_CODE' => ['label' => 'Task code', 'rules' => 'required'],
        ];

      
        return $rules;
    }

    public function allTaskAssignments()
    {
        $mine = new ServerSideDataTable();
        $tableName = 'FLXY_HK_TASKASSIGNMENT_OVERVIEW';
        $columns = 'HKTAO_ID,HKTAO_TASK_DATE,HKTAO_TASK_CODE,HKTAO_AUTO,HKTAO_TOTAL_SHEETS,HKTAO_TOTAL_CREDIT,HKTAO_CREATED_AT,HKTAO_CREATED_BY';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }

    public function taskAssignmentById($id)
    {
        return $this->TaskAssignment->find($id);
    }

    public function insertTaskAssignment($user_id, $data)
    {
        $id = $data['id'];
        unset($data['id']);

       
        if (empty($id)) {
            $data['HKTAO_CREATED_BY'] = $data['RE_UPDATED_BY'] = $user_id;
            $response = $this->TaskAssignment->insert($data);
        } else {
            $data['HKTAO_UPDATED_BY'] = $user_id;
            $response = $this->TaskAssignment->update($id, $data);
        }

        if (!$response)
            return responseJson(500, false, ['msg' => "db insert/update not successful"]);

        if (empty($id))
            $msg = 'Task Assignment has been created successflly.';
        else
            $msg = 'Task Assignment has been updated successflly.';

        return responseJson(200, false, ['msg' => $msg]);
    }

    public function deleteTaskAssignment($taskassignment_id)
    {
        return $this->TaskAssignment->delete($taskassignment_id);
    }

    public function allTaskAssignment()
    {
        return $this->TaskAssignment->findAll();
    }

    
}
