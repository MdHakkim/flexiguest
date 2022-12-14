<?php

namespace App\Controllers;

use App\Controllers\Repositories\UpgradeRoomRequestRepository;
use App\Libraries\ServerSideDataTable;
use CodeIgniter\API\ResponseTrait;

class UpgradeRoomRequestController extends BaseController
{

    use ResponseTrait;

    private $UpgradeRoomRequestRepository;

    public function __construct()
    {
        $this->UpgradeRoomRequestRepository = new UpgradeRoomRequestRepository();
    }

    public function upgradeRoomRequest()
    {
        $data['title'] = getMethodName();
        $data['session'] = session();

        return view('frontend/update_room_request', $data);
    }

    public function allRequests()
    {
        $mine = new ServerSideDataTable();
        $tableName = 'FLXY_UPGRADE_ROOM_REQUESTS left join FLXY_ROOM_TYPE on URR_ROOM_TYPE_ID = RM_TY_ID';
        $columns = 'URR_ID,URR_CUSTOMER_ID,URR_RESERVATION_ID,URR_ROOM_TYPE_ID,URR_STATUS,URR_CREATED_AT,RM_TY_DESC';
        $mine->generate_DatatTable($tableName, $columns);
        exit;
    }

    public function submitRequest()
    {
        $user = $this->request->user;
        $data = json_decode(json_encode($this->request->getVar()), true);

        if (!$this->validate($this->UpgradeRoomRequestRepository->validationRules()))
            return $this->respond(responseJson(403, true, $this->validator->getErrors()));

        $result = $this->UpgradeRoomRequestRepository->createOrUpdateUpgradeRequest($user, $data);
        return $this->respond($result);
    }
}