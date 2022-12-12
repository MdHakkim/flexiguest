<?php

namespace App\Controllers\Repositories;

use App\Controllers\BaseController;
use App\Models\Maintenance;
use App\Models\MaintenanceRequestComment;
use CodeIgniter\API\ResponseTrait;

class MaintenanceRepository extends BaseController
{
    use ResponseTrait;

    private $Maintenance;
    private $MaintenanceRequestComment;

    public function __construct()
    {
        $this->Maintenance = new Maintenance();
        $this->MaintenanceRequestComment = new MaintenanceRequestComment();
    }

    public function getComments($maintenance_request_id)
    {
        $comments = $this->MaintenanceRequestComment
            ->select('FLXY_MAINTENANCE_REQUEST_COMMENTS.*, USR_NAME')
            ->join('FLXY_USERS', 'MRC_USER_ID = USR_ID', 'left')
            ->where('MRC_MAINTENANCE_REQUEST_ID', $maintenance_request_id)
            ->findAll();

        foreach($comments as $index => $comment) {
            $comments[$index]['MRC_CREATED_AT'] = date('Y-m-d H:i A', strtotime($comment['MRC_CREATED_AT']));
        }

        return $comments;
    }

    public function allMaintenanceRequest($where_condition = "1 = 1")
    {
        return $this->Maintenance->where($where_condition)->findAll();
    }
}
